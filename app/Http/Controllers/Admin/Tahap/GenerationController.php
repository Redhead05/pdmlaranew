<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Tahap;
use App\Models\Team;
use App\Models\TeamLembaga;
use App\Services\AutoMatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerationController extends Controller
{
    /**
     * Halaman hasil pairing tim asesor <-> lembaga beserta manajemen manual.
     */
    public function index(Tahap $tahap)
    {
        $teams = Team::where('tahap_id', $tahap->id)
            ->whereNotNull('finalized_at')
            ->with(['members.user.detail', 'lembagas'])
            ->get();

        $assignments = TeamLembaga::where('tahap_id', $tahap->id)->get();

        // Tim yang masih bisa menerima lembaga (belum mencapai kuota).
        $unmatchedTeams = $teams->filter(fn ($t) => $t->lembagas->count() < $t->kuota());

        // Lembaga yang belum terpasang ke tim mana pun.
        $takenLembagaIds = $assignments->pluck('lembaga_id')->unique();
        $availableLembagas = $tahap->lembagas()
            ->orderBy('kabupaten')
            ->get()
            ->whereNotIn('id', $takenLembagaIds);

        // Data untuk modal Detail: per tim -> anggota + daftar lembaga (jarak per asesor).
        $teamDetail = $teams->mapWithKeys(function ($team) {
            $members = $team->members->map(function ($m) {
                $d = $m->user->detail ?? null;

                return [
                    'name' => $m->user->name ?? '-',
                    'lat' => $d->latitude ?? null,
                    'lng' => $d->longitude ?? null,
                ];
            })->values();

            $lembagas = $team->lembagas->map(function ($l) use ($team) {
                return [
                    'assignment_id' => $l->pivot->id,
                    'npsn' => $l->npsn,
                    'name' => $l->satuan_pen,
                    'kabupaten' => $l->kabupaten,
                    'is_manual' => (bool) $l->pivot->is_manual,
                    'distances' => AutoMatchService::memberDistances($team->members, $l),
                ];
            })->values();

            return [$team->id => [
                'code' => $team->code,
                'members' => $members,
                'lembagas' => $lembagas,
            ]];
        });

        return view('menu.admin.tahap.generation.index', compact(
            'tahap',
            'teams',
            'assignments',
            'unmatchedTeams',
            'availableLembagas',
            'teamDetail',
        ));
    }

    /**
     * Jalankan auto-match: reset semua pairing lalu pasangkan tim ke lembaga terdekat (minimax).
     */
    public function generate(Request $request, Tahap $tahap)
    {
        $teams = Team::where('tahap_id', $tahap->id)
            ->whereNotNull('finalized_at')
            ->with(['members.user.detail'])
            ->get();

        $lembagas = $tahap->lembagas()->get();

        if ($teams->isEmpty() || $lembagas->isEmpty()) {
            return back()->with('error', 'Pastikan tim final dan lembaga tahap sudah tersedia.');
        }

        $total = DB::transaction(function () use ($tahap, $teams, $lembagas) {
            // Bersihkan hasil lama agar auto-match selalu dimulai dari kondisi bersih.
            TeamLembaga::where('tahap_id', $tahap->id)->delete();

            $count = 0;
            foreach (AutoMatchService::autoMatch($teams, $lembagas) as $a) {
                TeamLembaga::create([
                    'tahap_id' => $tahap->id,
                    'team_id' => $a['team']->id,
                    'lembaga_id' => $a['lembaga_id'],
                    'distance_km' => $a['distance_km'],
                    'is_manual' => false,
                    'assigned_by' => auth()->id(),
                ]);
                $count++;
            }

            return $count;
        });

        return back()->with('success', "Auto-match selesai. {$total} pasangan berhasil dibuat.");
    }

    /**
     * Override manual: pasangkan satu tim ke satu lembaga secara paksa.
     */
    public function assign(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'lembaga_id' => ['required', 'integer', 'exists:lembagas,id'],
        ]);

        $team = Team::with('members.user.detail')->findOrFail($data['team_id']);
        $lembaga = Lembaga::findOrFail($data['lembaga_id']);

        if (TeamLembaga::where('tahap_id', $tahap->id)->where('lembaga_id', $lembaga->id)->exists()) {
            return back()->with('error', 'Lembaga tersebut sudah terpasang ke tim lain.');
        }

        if (TeamLembaga::where('tahap_id', $tahap->id)->where('team_id', $team->id)->count() >= $team->kuota()) {
            return back()->with('error', "Kuota tim {$team->code} sudah penuh.");
        }

        $distance = AutoMatchService::teamDistanceToLembaga($team->members, $lembaga);

        TeamLembaga::create([
            'tahap_id' => $tahap->id,
            'team_id' => $team->id,
            'lembaga_id' => $lembaga->id,
            'distance_km' => $distance !== null ? round($distance, 3) : null,
            'is_manual' => true,
            'assigned_by' => auth()->id(),
        ]);

        return back()->with('success', "Tim {$team->code} dipasangkan manual ke {$lembaga->satuan_pen}.");
    }

    /**
     * Lepas pasangan (biasanya untuk mengulang override).
     */
    public function unassign(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'assignment_id' => ['required', 'integer', 'exists:team_lembaga,id'],
        ]);

        TeamLembaga::where('tahap_id', $tahap->id)->where('id', $data['assignment_id'])->delete();

        return back()->with('success', 'Pasangan berhasil dilepas.');
    }
}
