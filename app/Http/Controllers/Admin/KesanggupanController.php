<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kesanggupan;
use App\Models\Tahap;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DraftTeamsExport;

class KesanggupanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kesanggupans = Kesanggupan::query()
            ->with('tahap')
            ->whereHas('tahap', function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderByDesc('id')
            ->get();

        return view('menu.admin.kesanggupan.index', compact('kesanggupans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tahap_id' => ['required', 'integer', 'exists:tahaps,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'kesediaan' => ['required', 'boolean'],
            'kesanggupan' => ['nullable', 'integer'],
            'alasan' => ['nullable', 'string'],
        ]);

        $tahap = Tahap::query()->findOrFail($data['tahap_id']);
        $allowed = $tahap->allowed_kesanggupan ?? [];

        if ((bool) $data['kesediaan'] === false) {
            $data['alasan'] = $request->validate([
                'alasan' => ['required', 'string', 'min:5'],
            ])['alasan'];

            $data['kesanggupan'] = null;
        } else {
            $request->validate([
                'kesanggupan' => ['required', 'integer', 'in:' . implode(',', array_map('intval', $allowed))],
            ]);

            $data['alasan'] = null;
        }

        Kesanggupan::create($data);

        return redirect()->route('admin.kesanggupan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Generate draft teams for a given tahap using existing rules.
     */
    public function generateTeams(Request $request, $tahapId)
    {
        $tahap = Tahap::findOrFail($tahapId);

        // Pastikan tahap sudah melewati end_date sebelum generate
        if (! $tahap->end_date || $tahap->end_date->gt(now())) {
            return back()->with('error', 'Generate teams hanya dapat dilakukan setelah tahap berakhir (end_date).');
        }

        // basic validation
        $data = $request->validate([
            'team_size' => ['nullable', 'integer', 'min:2', 'max:3'],
        ]);

        DB::beginTransaction();
        try {
            // create a new run
            $run = TeamGenerationRun::create([
                'tahap_id' => $tahap->id,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // collect eligible asesor user ids and kesanggupan values
            $eligibleRows = Kesanggupan::where('tahap_id', $tahap->id)
                ->where('kesediaan', true)
                ->get();

            $eligibleUserIds = $eligibleRows->pluck('user_id')->unique()->values()->all();

            if (empty($eligibleUserIds)) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada asesor yang eligible untuk tahap ini');
            }

            // map user_id => kesanggupan count
            $kesMap = [];
            foreach ($eligibleRows as $r) {
                $kesMap[$r->user_id] = (int) ($r->kesanggupan ?? 0);
            }

            // load user models with details
            $users = User::whereIn('id', $eligibleUserIds)->with('detail')->get();

            // run matching algorithm (Stage 1..3)
            $groups = $this->generateTeamsByRules($users, $kesMap);

            // persist groups into drafts
            foreach ($groups as $idx => $group) {
                $team = TeamDraft::create([
                    'run_id' => $run->id,
                    'team_code' => 'T' . ($idx + 1),
                ]);

                foreach ($group as $userId) {
                    TeamDraftMember::create([
                        'run_id' => $run->id,
                        'team_draft_id' => $team->id,
                        'user_id' => $userId,
                        'is_manual' => false,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.kesanggupan.team-draft', ['tahap' => $tahap->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate teams: ' . $e->getMessage());
        }
    }

    /**
     * Matching helper implementing Stage 1-3 pairing rules described by user.
     * Input: Collection of User (with detail), map user_id => kesanggupan integer
     * Output: array of groups (each group is array of user_ids)
     */
    private function generateTeamsByRules($users, $kesMap)
    {
        // prepare data array
        $byId = [];
        foreach ($users as $u) {
            $byId[$u->id] = [
                'user' => $u,
                'kes' => max(0, (int) ($kesMap[$u->id] ?? 0)),
                'matched' => false,
            ];
        }

        $groups = [];

        // Helper: safe get detail field
        $get = fn($id, $field) => $byId[$id]['user']->detail->{$field} ?? null;

        // Stage 1: strict pairing with priority weights
        // Priorities (weights): type_asesor prio1 (100), jml_kesanggupan equal prio2 (50), gender prio3 (20), work_city prio4 (10)
        $pairs = [];
        $ids = array_keys($byId);
        $n = count($ids);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $a = $byId[$ids[$i]]['user'];
                $b = $byId[$ids[$j]]['user'];

                // Enforce: Stage1 only pairs if kesanggupan equal and > 0
                $aKes = $byId[$a->id]['kes'] ?? 0;
                $bKes = $byId[$b->id]['kes'] ?? 0;
                if ($aKes <= 0 || $bKes <= 0 || $aKes !== $bKes) {
                    // skip: kesanggupan not equal -> leave to later stages (Stage3)
                    continue;
                }

                $score = 0;
                // type_asesor (prio1)
                if (($a->detail->type_asesor ?? null) && ($b->detail->type_asesor ?? null) && $a->detail->type_asesor === $b->detail->type_asesor) $score += 100;
                // kesanggupan equal (prio2) -- already guaranteed by check, still give bonus
                $score += 50;
                // gender (prio3)
                if (($a->detail->gender ?? null) && ($b->detail->gender ?? null) && $a->detail->gender === $b->detail->gender) $score += 20;
                // work_city same (prio4)
                if (($a->detail->work_city ?? null) && ($b->detail->work_city ?? null) && $a->detail->work_city === $b->detail->work_city) $score += 10;

                if ($score > 0) {
                    $pairs[] = ['a' => $a->id, 'b' => $b->id, 'score' => $score];
                }
            }
        }

        usort($pairs, fn($x,$y) => $y['score'] <=> $x['score']);

        foreach ($pairs as $p) {
            if (!isset($byId[$p['a']]) || !isset($byId[$p['b']])) continue;
            if ($byId[$p['a']]['matched'] || $byId[$p['b']]['matched']) continue;
            $groups[] = [$p['a'],$p['b']];
            $byId[$p['a']]['matched'] = $byId[$p['b']]['matched'] = true;
        }

        // Stage 2: relaxed pairing if still unmatched
        // Priorities: type_asesor (100), gender (50), proximity (<= X km) prio3 (30), same kesanggupan prio4 (10)
        // reindex remaining to numeric indexes to safely iterate by index
        $remaining = array_values(array_filter(array_keys($byId), fn($id) => !$byId[$id]['matched']));
        $pairs2 = [];
        $m = count($remaining);
        for ($i = 0; $i < $m; $i++) {
            for ($j = $i + 1; $j < $m; $j++) {
                $ai = $remaining[$i]; $bj = $remaining[$j];
                $a = $byId[$ai]['user']; $b = $byId[$bj]['user'];

                // Enforce: Stage2 also require same kesanggupan (>0) to form pair of 2
                $aKes = $byId[$a->id]['kes'] ?? 0;
                $bKes = $byId[$b->id]['kes'] ?? 0;
                if ($aKes <= 0 || $bKes <= 0 || $aKes !== $bKes) {
                    // skip pairing here; leave for Stage3 grouping
                    continue;
                }

                $score = 0;
                if (($a->detail->type_asesor ?? null) && ($b->detail->type_asesor ?? null) && $a->detail->type_asesor === $b->detail->type_asesor) $score += 100;
                if (($a->detail->gender ?? null) && ($b->detail->gender ?? null) && $a->detail->gender === $b->detail->gender) $score += 50;

                $d = $this->haversineDistance($a->detail->latitude ?? null, $a->detail->longitude ?? null, $b->detail->latitude ?? null, $b->detail->longitude ?? null);
                // interpret "not more than 2 kabkot different" as distance <= 80 km (configurable)
                if ($d !== null && $d <= 80) $score += 30;

                if (($byId[$a->id]['kes'] ?? 0) === ($byId[$b->id]['kes'] ?? 0) && $byId[$a->id]['kes'] > 0) $score += 10;

                if ($score > 0) $pairs2[] = ['a'=>$a->id,'b'=>$b->id,'score'=>$score];
            }
        }
        usort($pairs2, fn($x,$y) => $y['score'] <=> $x['score']);
        foreach ($pairs2 as $p) {
            if ($byId[$p['a']]['matched'] || $byId[$p['b']]['matched']) continue;
            $groups[] = [$p['a'],$p['b']];
            $byId[$p['a']]['matched'] = $byId[$p['b']]['matched'] = true;
        }

        // Stage 3: build teams of 2-3 to satisfy capacity (kesanggupan totals)
        // reindex remaining to numeric indexes for later indexing operations
        $remaining = array_values(array_filter(array_keys($byId), fn($id) => !$byId[$id]['matched']));
        // sort remaining by kes desc (higher capacity first)
        usort($remaining, fn($x,$y) => ($byId[$y]['kes'] <=> $byId[$x]['kes']));

        while (count($remaining) > 0) {
            if (count($remaining) == 1) {
                // single leftover, leave unmatched (admin will handle)
                break;
            }

            // take lead (highest kes)
            $lead = array_shift($remaining);
            $leadKes = $byId[$lead]['kes'];

            // try to find best combination of 2 partners (to form team of 3) where combined partner kes approximates leadKes
            $bestCombo = null; $bestComboScore = -1e9;
            $rCount = count($remaining);
            // try pairs of partners
            for ($i=0;$i<$rCount;$i++) {
                for ($j=$i+1;$j<$rCount;$j++) {
                    $aId = $remaining[$i]; $bId = $remaining[$j];
                    $sum = $byId[$aId]['kes'] + $byId[$bId]['kes'];
                    // prefer sum closer to leadKes (but not exceeding by too much). score = -(abs(leadKes - sum)) + small bonus by sum
                    $score = -abs($leadKes - $sum) + ($sum * 0.01);
                    if ($score > $bestComboScore) { $bestComboScore = $score; $bestCombo = [$i,$j]; }
                }
            }

            // try single best partner
            $bestSingle = null; $bestSingleScore = -1e9;
            foreach ($remaining as $idx => $cand) {
                $sum = $byId[$cand]['kes'];
                $score = -abs($leadKes - $sum) + ($sum * 0.01);
                if ($score > $bestSingleScore) { $bestSingleScore = $score; $bestSingle = $idx; }
            }

            // decide whether to pick pair or single: prefer pair if it gives better score
            // Prefer a 3-member combo when available to respect differing kesanggupan rules
            if ($bestCombo !== null) {
                $aIdx = $bestCombo[0]; $bIdx = $bestCombo[1];
                $aId = $remaining[$aIdx]; $bId = $remaining[$bIdx];
                // remove larger index first
                if ($aIdx > $bIdx) { unset($remaining[$aIdx]); unset($remaining[$bIdx]); } else { unset($remaining[$bIdx]); unset($remaining[$aIdx]); }
                $remaining = array_values($remaining);
                $groups[] = [$lead, $aId, $bId];
                $byId[$lead]['matched']=true; $byId[$aId]['matched']=true; $byId[$bId]['matched']=true;
            } elseif ($bestSingle !== null) {
                $memberId = $remaining[$bestSingle];
                unset($remaining[$bestSingle]);
                $remaining = array_values($remaining);
                $groups[] = [$lead, $memberId];
                $byId[$lead]['matched']=true; $byId[$memberId]['matched']=true;
            } else {
                // cannot find partner
                break;
            }
        }

        return $groups;
    }

    /**
     * Haversine distance in kilometers between two lat/lng points. Returns null if coords missing.
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) return null;
        $lat1 = floatval($lat1); $lon1 = floatval($lon1); $lat2 = floatval($lat2); $lon2 = floatval($lon2);
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Show the current draft teams and unmatched users
     */
    public function teamDraft(Request $request, $tahapId)
    {
        $tahap = Tahap::findOrFail($tahapId);

        // Build the same datasets as TahapController@show so view has all variables it expects
        $filled = Kesanggupan::query()
            ->where('tahap_id', $tahap->id)
            ->where(function ($q) {
                $q->whereNotNull('kesanggupan')
                    ->orWhereNotNull('alasan');
            })
            ->with([
                'user:id,name,email',
                'user.detail:user_id,work_city,gender,type_asesor,latitude,longitude',
            ])
            ->get();

        $can = $filled->where('kesediaan', true)->values();
        $cannot = $filled->where('kesediaan', false)->values();

        $filledUserIds = $filled->pluck('user_id')->unique()->values();

        $notFilledUsers = User::query()
            ->select(['id', 'name', 'email'])
            ->with(['detail:user_id,work_city,gender,type_asesor,latitude,longitude'])
            ->role('asesor')
            ->when($filledUserIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $filledUserIds))
            ->orderBy('name')
            ->get();

        $run = TeamGenerationRun::where('tahap_id', $tahap->id)->latest()->first();

        $teams = $run ? TeamDraft::with(['members.user.detail'])->where('run_id', $run->id)->get() : collect();

        // users eligible but not assigned in this run
        $eligibleUserIds = Kesanggupan::where('tahap_id', $tahap->id)->where('kesediaan', true)->pluck('user_id')->toArray();
        $assignedUserIds = $run ? TeamDraftMember::where('run_id', $run->id)->pluck('user_id')->toArray() : [];

        $unmatched = User::whereIn('id', array_diff($eligibleUserIds, $assignedUserIds))->with('detail')->get();

        return view('menu.admin.tahap.kesanggupan.detilTahapKesanggupan', compact('tahap', 'can', 'cannot', 'notFilledUsers', 'run', 'teams', 'unmatched'));
    }

    /**
     * Assign an unmatched user to a draft team
     */
    public function assignDraftMember(Request $request, $tahapId)
    {
        $data = $request->validate([
            'run_id' => ['required', 'integer', 'exists:team_generation_runs,id'],
            'team_id' => ['nullable', 'integer', 'exists:team_drafts,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $run = TeamGenerationRun::findOrFail($data['run_id']);
        if ($run->tahap_id != $tahapId || $run->status !== 'draft') {
            return back()->with('error', 'Invalid run');
        }

        // Check eligibility
        $kes = Kesanggupan::where('tahap_id', $tahapId)->where('user_id', $data['user_id'])->where('kesediaan', true)->first();
        if (! $kes) {
            return back()->with('error', 'User tidak eligible');
        }

        // check if already assigned in this run
        if (TeamDraftMember::where('run_id', $run->id)->where('user_id', $data['user_id'])->exists()) {
            return back()->with('error', 'User sudah ter-assign');
        }

        // if team_id not provided, create new team
        $teamId = $data['team_id'] ?? null;
        if (! $teamId) {
            $team = TeamDraft::create([
                'run_id' => $run->id,
                'team_code' => 'T' . (TeamDraft::where('run_id', $run->id)->count() + 1),
            ]);
            $teamId = $team->id;
        }

        // capacity check (max 3)
        $count = TeamDraftMember::where('team_draft_id', $teamId)->count();
        if ($count >= 3) {
            return back()->with('error', 'Tim sudah penuh');
        }

        TeamDraftMember::create([
            'run_id' => $run->id,
            'team_draft_id' => $teamId,
            'user_id' => $data['user_id'],
            'is_manual' => true,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'User berhasil diassign');
    }

    /**
     * Unassign member from draft
     */
    public function unassignDraftMember(Request $request, $tahapId)
    {
        $data = $request->validate([
            'run_id' => ['required', 'integer', 'exists:team_generation_runs,id'],
            'member_id' => ['required', 'integer', 'exists:team_draft_members,id'],
        ]);

        $run = TeamGenerationRun::findOrFail($data['run_id']);
        if ($run->tahap_id != $tahapId || $run->status !== 'draft') {
            return back()->with('error', 'Invalid run');
        }

        $member = TeamDraftMember::findOrFail($data['member_id']);
        if ($member->run_id != $run->id) {
            return back()->with('error', 'Member tidak ditemukan di run ini');
        }

        $member->delete();

        return back()->with('success', 'Member dihapus');
    }

    /**
     * Finalize teams
     */
    public function finalizeTeams(Request $request, $tahapId)
    {
        $data = $request->validate([
            'run_id' => ['required', 'integer', 'exists:team_generation_runs,id'],
        ]);

        $run = TeamGenerationRun::findOrFail($data['run_id']);
        if ($run->tahap_id != $tahapId || $run->status !== 'draft') {
            return back()->with('error', 'Invalid run');
        }

        // validate unmatched = 0
        $eligibleUserIds = Kesanggupan::where('tahap_id', $tahapId)->where('kesediaan', true)->pluck('user_id')->toArray();
        $assignedUserIds = TeamDraftMember::where('run_id', $run->id)->pluck('user_id')->toArray();
        $unmatched = array_diff($eligibleUserIds, $assignedUserIds);
        if (count($unmatched) > 0) {
            return back()->with('error', 'Masih ada user yang belum ter-assign');
        }

        // validate sizes
        $teams = TeamDraft::withCount('members')->where('run_id', $run->id)->get();
        foreach ($teams as $team) {
            if ($team->members_count < 2 || $team->members_count > 3) {
                return back()->with('error', 'Setiap tim harus beranggotakan 2 sampai 3 asesor');
            }
        }

        DB::beginTransaction();
        try {
            // copy draft teams -> final teams
            $finalTeamsMap = []; // draft_team_id => final_team_id
            foreach ($teams as $d) {
                $final = Team::create([
                    'tahap_id' => $run->tahap_id,
                    'code' => $d->team_code ?? ('T' . $d->id),
                    'created_by' => $run->created_by,
                    'finalized_by' => Auth::id(),
                    'finalized_at' => now(),
                ]);
                $finalTeamsMap[$d->id] = $final->id;

                // copy members
                $members = TeamDraftMember::where('team_draft_id', $d->id)->get();
                foreach ($members as $m) {
                    TeamMember::create([
                        'team_id' => $final->id,
                        'user_id' => $m->user_id,
                        'assigned_by' => $m->assigned_by ?? $run->created_by,
                        'assigned_at' => $m->assigned_at ?? now(),
                    ]);
                }
            }

            // mark run as final
            $run->status = 'final';
            $run->finalized_by = Auth::id();
            $run->finalized_at = now();
            $run->save();

            DB::commit();
            return back()->with('success', 'Teams berhasil difinalisasi dan disimpan ke tabel final');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }

    /**
     * Export current draft to CSV for offline editing.
     */
    public function downloadDraft(Request $request, $tahapId)
    {
        $run = TeamGenerationRun::where('tahap_id', $tahapId)->latest()->first();
        if (! $run) {
            return back()->with('error', 'Tidak ada draft run untuk tahap ini');
        }

        $teams = TeamDraft::with(['members.user.detail'])->where('run_id', $run->id)->get();

        $rows = [];
        foreach ($teams as $team) {
            foreach ($team->members as $member) {
                $user = $member->user;
                $detail = $user->detail ?? null;
                $kes = '';
                if ($user) {
                    $kes = Kesanggupan::where('tahap_id', $run->tahap_id)->where('user_id', $user->id)->value('kesanggupan');
                }

                // Preserve NIA exactly as in DB when opened in Excel by exporting as a formula that returns text: ="NIA"
                $niaRaw = $user->nia ?? '';
                $niaExport = $niaRaw !== '' ? '="' . str_replace('"', '""', $niaRaw) . '"' : '';

                $latitude = $detail->latitude ?? '';
                $longitude = $detail->longitude ?? '';

                // Export without user_id column; ensure latitude/longitude exported exactly as in DB.
                // Use formula string for NIA; export lat/lng as string to avoid locale decimal issues.
                $latExport = $latitude !== '' ? '="' . $latitude . '"' : '';
                $lonExport = $longitude !== '' ? '="' . $longitude . '"' : '';

                $rows[] = [
                    $team->team_code ?? ('T' . $team->id),
                    $niaExport,
                    $user->name ?? '',
                    $user->email ?? '',
                    $detail->work_city ?? '',
                    $kes ?? '',
                    $latExport,
                    $lonExport,
                ];
             }
         }

        $filename = 'draft_teams_tahap_' . $tahapId . '_run_' . $run->id . '.xlsx';

        return Excel::download(new DraftTeamsExport($rows), $filename);
    }

    /**
     * Upload CSV to overwrite current draft teams for a run.
     * Expected CSV columns: team_code, user_id (or nia or email)
     */
    public function uploadDraft(Request $request, $tahapId)
    {
        $request->validate([
            'run_id' => ['required','integer','exists:team_generation_runs,id'],
            'file' => ['required','file','mimes:csv,txt'],
        ]);

        $run = TeamGenerationRun::findOrFail($request->input('run_id'));
        if ($run->tahap_id != $tahapId || $run->status !== 'draft') {
            return back()->with('error', 'Invalid run');
        }

        $file = $request->file('file');

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return back()->with('error', 'Gagal membuka file');
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            return back()->with('error', 'CSV kosong atau tidak valid');
        }

        $map = array_flip(array_map('strtolower', $header));

        // expected at least team_code and one identifier (user_id or nia or email)
        if (! isset($map['team_code']) || (!isset($map['user_id']) && !isset($map['nia']) && !isset($map['email']))) {
            fclose($handle);
            return back()->with('error', 'CSV harus mengandung kolom team_code dan salah satu user_id / nia / email');
        }

        $rows = [];
        $lineNo = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $lineNo++;
            if (count($data) < 1) continue;
            $teamCode = $data[$map['team_code']] ?? null;
            $userIdentifier = null;
            if (isset($map['user_id'])) $userIdentifier = $data[$map['user_id']] ?? null;
            elseif (isset($map['nia'])) $userIdentifier = $data[$map['nia']] ?? null;
            elseif (isset($map['email'])) $userIdentifier = $data[$map['email']] ?? null;

            if (! $teamCode || ! $userIdentifier) {
                fclose($handle);
                return back()->with('error', "Baris $lineNo: team_code atau identifier user kosong");
            }

            $rows[] = ['team_code' => trim($teamCode), 'identifier' => trim($userIdentifier), 'line' => $lineNo];
        }

        fclose($handle);

        // resolve identifiers -> user ids
        $assignments = []; // team_code => [user_id,...]
        $used = [];
        foreach ($rows as $r) {
            $ident = $r['identifier'];
            $user = null;
            if (is_numeric($ident)) {
                $user = User::where('id', intval($ident))->first();
                if (! $user) {
                    // maybe numeric is NIA (string) so search nia too
                    $user = User::where('nia', $ident)->first();
                }
            } else {
                // not numeric, search by email first then nia
                $user = User::where('email', $ident)->first() ?? User::where('nia', $ident)->first();
            }

            if (! $user) {
                return back()->with('error', "Baris {$r['line']}: user dengan identifier '{$r['identifier']}' tidak ditemukan");
            }

            // check kesediaan for this tahap
            $kes = Kesanggupan::where('tahap_id', $run->tahap_id)->where('user_id', $user->id)->first();
            if (! $kes || !$kes->kesediaan) {
                return back()->with('error', "Baris {$r['line']}: user '{$user->name}' tidak eligible (belum mengisi kesediaan / memilih tidak)");
            }

            if (in_array($user->id, $used)) {
                return back()->with('error', "Baris {$r['line']}: user '{$user->name}' (id {$user->id}) muncul lebih dari sekali dalam CSV");
            }

            $used[] = $user->id;
            $assignments[$r['team_code']][] = $user->id;
        }

        // additional validation: ensure no team has more than 3 members
        foreach ($assignments as $teamCode => $members) {
            if (count($members) > 3) {
                return back()->with('error', "Tim '{$teamCode}' memiliki lebih dari 3 anggota ({count($members)}). Maksimum 3.");
            }
        }

        // persist: delete existing team_drafts & members for this run, then recreate
        DB::beginTransaction();
        try {
            TeamDraftMember::where('run_id', $run->id)->delete();
            TeamDraft::where('run_id', $run->id)->delete();

            foreach ($assignments as $teamCode => $userIds) {
                $team = TeamDraft::create(['run_id' => $run->id, 'team_code' => $teamCode]);
                foreach ($userIds as $uid) {
                    TeamDraftMember::create([
                        'run_id' => $run->id,
                        'team_draft_id' => $team->id,
                        'user_id' => $uid,
                        'is_manual' => true,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Draft berhasil di-upload dan diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan draft dari CSV: ' . $e->getMessage());
        }
    }

    /**
     * Cancel/delete current draft run so admin bisa generate/upload ulang.
     */
    public function cancelDraft(Request $request, $tahapId)
    {
        $data = $request->validate([
            'run_id' => ['required','integer','exists:team_generation_runs,id'],
        ]);

        $run = TeamGenerationRun::findOrFail($data['run_id']);
        if ($run->tahap_id != $tahapId) {
            return back()->with('error', 'Run tidak cocok');
        }

        if ($run->status !== 'draft') {
            return back()->with('error', 'Hanya run dengan status draft yang dapat dibatalkan');
        }

        DB::beginTransaction();
        try {
            // delete related drafts/members via cascade, then delete run
            TeamDraftMember::where('run_id', $run->id)->delete();
            TeamDraft::where('run_id', $run->id)->delete();
            $run->delete();
            DB::commit();
            return back()->with('success', 'Draft berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan draft: ' . $e->getMessage());
        }
    }
}
