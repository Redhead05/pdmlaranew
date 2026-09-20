<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Exports\PairingExport;
use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Tahap;
use App\Models\Team;
use App\Models\TeamGenerationRun;
use App\Models\TeamLembaga;
use App\Models\User;
use App\Notifications\SuratTugasGeneratedNotification;
use App\Services\AutoMatchService;
use App\Services\TahapPairingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class GenerationController extends Controller
{
    protected TahapPairingService $pairing;

    public function __construct(TahapPairingService $pairing)
    {
        $this->pairing = $pairing;
    }

    /**
     * Pengaman eksekusi untuk dataset besar (ribuan lembaga / ratusan tim).
     * TODO: bisa dilepas setelah seluruh jalur pairing nyaman di memori default.
     */
    protected function bumpLimits(): void
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');
    }

    protected function isLocked(Tahap $tahap): bool
    {
        return $tahap->pairing_locked_at !== null;
    }

    protected function ensureUnlocked(Tahap $tahap): bool
    {
        if ($this->isLocked($tahap)) {
            session()->flash('error', 'Data pairing sedang dikunci. Buka kunci dahulu sebelum mengubah data.');

            return false;
        }

        return true;
    }

    /**
     * Tim final milik tahap beserta anggota + user.detail.
     */
    protected function finalizedTeams(Tahap $tahap)
    {
        return Team::where('tahap_id', $tahap->id)
            ->whereNotNull('finalized_at')
            ->with(['members.user.detail'])
            ->orderBy('id')
            ->get();
    }

    /**
     * Semua baris pasangan tahap ini (ringan: tanpa relasi).
     */
    protected function assignmentRows(Tahap $tahap)
    {
        return DB::table('team_lembaga')
            ->where('tahap_id', $tahap->id)
            ->select(['id', 'team_id', 'lembaga_id', 'distance_km', 'is_manual', 'asesor_a_user_id', 'asesor_b_user_id'])
            ->get();
    }

    /**
     * Load user beserta detail untuk sekumpulan id (asesor override per baris).
     *
     * @return array<int, User>
     */
    protected function usersWithDetail(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (! $ids) {
            return [];
        }
        $users = [];
        foreach (User::with('detail')->whereIn('id', $ids)->get() as $u) {
            $users[$u->id] = $u;
        }

        return $users;
    }

    /**
     * Jarak (km) satu asesor ke lembaga bila keduanya punya koordinat.
     */
    protected function asesorKmToLembaga(?User $u, $l): ?float
    {
        $d = $u?->detail;
        if (! $d || ! $l || $l->latitude === null || $l->longitude === null || $d->latitude === null || $d->longitude === null) {
            return null;
        }

        return round(AutoMatchService::haversine((float) $d->latitude, (float) $d->longitude, (float) $l->latitude, (float) $l->longitude), 2);
    }

    /**
     * Baris hasil pairing per NPSN beserta pasangan asesor EFEKTIF:
     *  - override per baris (asesor_a/b_user_id) bila ada;
     *  - selain itu mengikuti anggota tim (urutan ke-1 = A, ke-2 = B).
     * Baris yang asesornya tidak punya tim ber-team_id null.
     *
     * @return array<int, array>
     */
    protected function pairingRows(Tahap $tahap): array
    {
        $teams = $this->finalizedTeams($tahap);
        $rows = $this->assignmentRows($tahap);
        $teamsById = $teams->keyBy('id');

        $overrideIds = [];
        foreach ($rows as $r) {
            if ($r->asesor_a_user_id) {
                $overrideIds[] = $r->asesor_a_user_id;
            }
            if ($r->asesor_b_user_id) {
                $overrideIds[] = $r->asesor_b_user_id;
            }
        }
        $extra = $this->usersWithDetail($overrideIds);
        // gabung user anggota tim (sudah di-load beserta detail) + user override
        $all = $extra;
        foreach ($teams as $team) {
            foreach ($team->members as $m) {
                if ($m->user) {
                    $all[$m->user->id] = $m->user;
                }
            }
        }

        $lembagaIds = $rows->pluck('lembaga_id')->unique()->all();
        $lembagaMap = $lembagaIds
            ? Lembaga::whereIn('id', $lembagaIds)->get(['id', 'npsn', 'satuan_pen', 'kabupaten', 'latitude', 'longitude'])->keyBy('id')
            : collect();

        $out = [];
        foreach ($rows as $r) {
            $team = $r->team_id ? ($teamsById[$r->team_id] ?? null) : null;
            $l = $lembagaMap[$r->lembaga_id] ?? null;
            if (! $l) {
                continue;
            }

            $members = $team ? $team->members->sortBy('id')->values() : collect();
            $fallback = fn ($i) => $members->get($i)?->user;

            $uA = $r->asesor_a_user_id ? ($all[$r->asesor_a_user_id] ?? null) : $fallback(0);
            $uB = $r->asesor_b_user_id ? ($all[$r->asesor_b_user_id] ?? null) : $fallback(1);

            $kmA = $this->asesorKmToLembaga($uA, $l);
            $kmB = $this->asesorKmToLembaga($uB, $l);
            $kms = array_values(array_filter([$kmA, $kmB], fn ($v) => $v !== null));
            $distance = $kms ? max($kms) : $r->distance_km;

            $out[] = [
                'assignment_id' => $r->id,
                'team_id' => $r->team_id,
                'team' => $team,
                'code' => $team ? ($team->code ?? 'T'.$team->id) : '',
                'npsn' => $l->npsn,
                'name' => $l->satuan_pen,
                'kabupaten' => $l->kabupaten,
                'pair' => [$uA, $uB],
                'member_km' => [$kmA, $kmB],
                'distance_km' => $distance,
                'is_manual' => (bool) $r->is_manual,
            ];
        }

        return $out;
    }

    /**
     * Statistik ringkas halaman pairing (butuh tim final untuk kuota).
     */
    protected function pairingStats(Tahap $tahap): array
    {
        $teams = $this->finalizedTeams($tahap);
        $rows = $this->assignmentRows($tahap);
        $kuotaMap = AutoMatchService::kuotaMap($tahap->id, $teams);

        $countByTeam = [];
        $taken = [];
        foreach ($rows as $r) {
            $countByTeam[$r->team_id] = ($countByTeam[$r->team_id] ?? 0) + 1;
            $taken[$r->lembaga_id] = true;
        }

        $stats = [
            'teams' => $teams->count(),
            'lembagas' => (int) DB::table('lembaga_tahap')->where('tahap_id', $tahap->id)->count(),
            'terpasang' => count($rows),
            'tersisa' => 0,
            'tim_belum_penuh' => 0,
        ];
        foreach ($teams as $team) {
            if (($countByTeam[$team->id] ?? 0) < ($kuotaMap[$team->id] ?? 0)) {
                $stats['tim_belum_penuh']++;
            }
        }
        $stats['tersisa'] = max(0, $stats['lembagas'] - count($taken));

        return $stats;
    }

    /**
     * Halaman hasil pairing tim asesor <-> lembaga beserta manajemen manual.
     */
    public function index(Tahap $tahap)
    {
        $stats = $this->pairingStats($tahap);
        $run = TeamGenerationRun::where('tahap_id', $tahap->id)->latest('id')->first();

        return view('menu.admin.tahap.generation.index', [
            'tahap' => $tahap,
            'stats' => $stats,
            'locked' => $this->isLocked($tahap),
            'run' => $run,
            'suratTugasSent' => (bool) ($run?->surat_tugas_notification_sent_at),
            'suratTugasNumber' => $run?->surat_tugas_number,
        ]);
    }

    /**
     * Kirim surat tugas: simpan nomor ST + snapshot pairing, lalu notifikasi
     * seluruh asesor yang tertugaskan. Hanya bisa saat tahap sudah dikunci.
     */
    public function sendSuratTugas(Request $request, Tahap $tahap)
    {
        if (! $this->isLocked($tahap)) {
            return back()->with('error', 'Surat tugas hanya bisa dikirim setelah data pairing dikunci.');
        }

        $data = $request->validate([
            'nomor_st' => ['required', 'string', 'max:255'],
        ]);

        $rows = $this->pairing->suratTugasRows($tahap);
        if (! $rows) {
            return back()->with('error', 'Belum ada pasangan tim ↔ lembaga untuk dibuatkan surat tugas. Jalankan Auto-Match terlebih dahulu.');
        }

        $nomor = trim($data['nomor_st']);
        $slug = Str::slug($nomor).'-'.now()->format('YmdHis');

        // Buat run baru setiap pengiriman agar revisi tidak menimpa surat tugas lama.
        $run = TeamGenerationRun::create([
            'tahap_id' => $tahap->id,
            'status' => 'final',
            'created_by' => Auth::id(),
            'finalized_by' => Auth::id(),
            'finalized_at' => now(),
            'final_pairs_payload' => [
                'nomor_st' => $nomor,
                'generated_at' => now()->toIso8601String(),
                'rows' => $rows,
            ],
            'surat_tugas_number' => $nomor,
            'surat_tugas_slug' => $slug,
            'surat_tugas_generated_by' => Auth::id(),
            'surat_tugas_generated_at' => now(),
        ]);

        // Kumpulkan kode tim per asesor, lalu kirim notifikasi database.
        $teamCodesByUser = [];
        foreach ($rows as $row) {
            foreach ($row['member_ids'] as $userId) {
                $teamCodesByUser[(int) $userId][] = $row['team_code'];
            }
        }

        $recipients = User::role('asesor')->whereIn('id', array_keys($teamCodesByUser))->get();
        $sent = 0;
        foreach ($recipients as $user) {
            $codes = array_values(array_unique($teamCodesByUser[$user->id] ?? []));
            $user->notify(new SuratTugasGeneratedNotification(
                $tahap,
                $run,
                implode(', ', $codes),
                $nomor,
            ));
            $sent++;
        }

        $run->update(['surat_tugas_notification_sent_at' => now()]);

        return back()->with('success', "Surat tugas {$nomor} berhasil dikirim ke {$sent} asesor.");
    }

    /**
     * Pratinjau surat tugas (admin): seluruh baris pairing.
     */
    public function suratTugas(Request $request, Tahap $tahap, TeamGenerationRun $run)
    {
        if ((int) $run->tahap_id !== $tahap->id) {
            abort(404);
        }

        $rows = $run->final_pairs_payload['rows'] ?? $this->pairing->suratTugasRows($tahap);
        $nomorSt = $run->surat_tugas_number
            ?: 'ST/'.str_pad((string) $run->id, 3, '0', STR_PAD_LEFT).'/'.$tahap->id.'/'.now()->format('Y');

        $signerId = $run->surat_tugas_generated_by ?? $run->finalized_by ?? $run->created_by;
        $adminName = $signerId ? (User::find($signerId)?->name ?? 'Administrator') : 'Administrator';

        return view('menu.admin.tahap.generation.surat_tugas', [
            'tahap' => $tahap,
            'run' => $run,
            'rows' => $rows,
            'nomorSt' => $nomorSt,
            'isPersonal' => false,
            'recipientName' => null,
            'adminName' => $adminName,
        ]);
    }

    /**
     * Baris hasil pairing per NPSN (dipakai tabel DataTables & tombol Copy).
     */
    protected function buildResultItems(Tahap $tahap): array
    {
        $items = [];
        foreach ($this->pairingRows($tahap) as $p) {
            $detailMembers = [];
            foreach ([0, 1] as $i) {
                $u = $p['pair'][$i];
                $detailMembers[] = [
                    'name' => $u?->name ?? '-',
                    'nia' => $u?->nia ?? '',
                    'km' => $p['member_km'][$i],
                ];
            }
            $detail = [
                'assignment_id' => $p['assignment_id'],
                'team_id' => $p['team_id'],
                'team_code' => $p['code'],
                'npsn' => $p['npsn'],
                'name' => $p['name'],
                'kabupaten' => $p['kabupaten'],
                'is_manual' => $p['is_manual'],
                'distance_km' => $p['distance_km'],
                'members' => $detailMembers,
            ];
            $uA = $p['pair'][0];
            $uB = $p['pair'][1];
            $items[] = [
                'assignment_id' => $p['assignment_id'],
                'team_id' => $p['team_id'],
                'code' => $p['code'],
                'npsn' => $p['npsn'],
                'name' => $p['name'],
                'kabupaten' => $p['kabupaten'],
                'nia_a' => $uA?->nia ?? '',
                'nama_a' => $uA?->name ?? '',
                'nia_b' => $uB?->nia ?? '',
                'nama_b' => $uB?->name ?? '',
                'detail' => json_encode($detail, JSON_UNESCAPED_UNICODE),
                'action' => $this->isLocked($tahap)
                    ? '<span class="text-muted fs-13">Terkunci</span>'
                    : '<button type="button" class="btn btn-sm btn-outline-danger cancel-assignment" data-assignment="'.$p['assignment_id'].'" title="Batalkan pasangan ini — lembaga kembali ke daftar tersisa">Cancel</button>',
            ];
        }

        return $items;
    }

    public function pairingResults(Request $request, Tahap $tahap)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        return DataTables::of(collect($this->buildResultItems($tahap)))->make(true);
    }

    /**
     * Data hasil pairing (tanpa envelope DataTables) untuk tombol Copy di tabel.
     */
    public function resultsCopy(Request $request, Tahap $tahap)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $headers = ['Kode Tim', 'NPSN', 'Nama Lembaga', 'Kab/Kota', 'NIA Asesor A', 'Nama Asesor A', 'NIA Asesor B', 'Nama Asesor B'];
        $rows = [];
        foreach ($this->buildResultItems($tahap) as $it) {
            $rows[] = [$it['code'], $it['npsn'], $it['name'], $it['kabupaten'], $it['nia_a'], $it['nama_a'], $it['nia_b'], $it['nama_b']];
        }

        return response()->json(['headers' => $headers, 'rows' => $rows]);
    }

    /**
     * Jalankan auto-match: reset semua pairing lalu pasangkan tim ke lembaga
     * terdekat (minimax) — versi memori-terbatas + batch insert.
     */
    public function generate(Request $request, Tahap $tahap)
    {
        $this->bumpLimits();
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        $teams = $this->finalizedTeams($tahap);
        $lembagas = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->where('lembaga_tahap.tahap_id', $tahap->id)
            ->select('lembagas.id', 'lembagas.latitude', 'lembagas.longitude')
            ->get();

        if ($teams->isEmpty() || $lembagas->isEmpty()) {
            return back()->with('error', 'Pastikan tim final dan lembaga tahap sudah tersedia.');
        }

        $total = DB::transaction(function () use ($tahap, $teams, $lembagas) {
            DB::table('team_lembaga')->where('tahap_id', $tahap->id)->delete();

            $assignments = AutoMatchService::autoMatch($teams, $lembagas);
            $userId = Auth::id();
            $now = now();
            $rows = [];
            $count = 0;
            foreach ($assignments as $a) {
                $rows[] = [
                    'tahap_id' => $tahap->id,
                    'team_id' => $a['team_id'],
                    'lembaga_id' => $a['lembaga_id'],
                    'distance_km' => $a['distance_km'],
                    'is_manual' => false,
                    'assigned_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if (count($rows) >= 500) {
                    DB::table('team_lembaga')->insert($rows);
                    $count += count($rows);
                    $rows = [];
                }
            }
            if ($rows) {
                DB::table('team_lembaga')->insert($rows);
                $count += count($rows);
            }

            return $count;
        });

        $eligible = $lembagas->where('latitude', '!=', null)->where('longitude', '!=', null)->count();
        $leftover = max(0, $eligible - $total);
        $message = "Auto-match selesai. {$total} pasangan berhasil dibuat.";
        if ($leftover > 0) {
            $message .= " {$leftover} lembaga belum terpasang (kapasitas tim penuh) — lihat halaman Lembaga Tersisa / rapikan via Manual Override.";
        }

        return back()->with('success', $message);
    }

    /**
     * Override manual: pasangkan 1+ lembaga sekaligus sesuai sisa kuota tim.
     */
    public function assign(Request $request, Tahap $tahap)
    {
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        $data = $request->validate([
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'lembaga_ids' => ['required', 'array', 'min:1'],
            'lembaga_ids.*' => ['integer', 'distinct', 'exists:lembagas,id'],
        ]);

        $team = Team::with('members.user.detail')->findOrFail($data['team_id']);
        $lembagaIds = array_values(array_unique(array_map('intval', $data['lembaga_ids'])));

        $assigned = (int) TeamLembaga::where('tahap_id', $tahap->id)->where('team_id', $team->id)->count();
        $kuotaMap = AutoMatchService::kuotaMap($tahap->id, collect([$team]));
        $kuota = $kuotaMap[$team->id] ?? 0;
        if ($assigned + count($lembagaIds) > $kuota) {
            return back()->with('error', "Kuota tim {$team->code} hanya tersisa ".max(0, $kuota - $assigned).' lembaga.');
        }

        $taken = TeamLembaga::where('tahap_id', $tahap->id)->whereIn('lembaga_id', $lembagaIds)->pluck('lembaga_id')->all();
        if ($taken) {
            return back()->with('error', 'Beberapa lembaga yang dipilih sudah terpasang ke tim lain.');
        }

        $lembagas = Lembaga::whereIn('id', $lembagaIds)->get()->keyBy('id');
        $userId = Auth::id();
        $now = now();
        $count = 0;
        DB::transaction(function () use ($tahap, $team, $lembagas, $lembagaIds, $userId, $now, &$count) {
            foreach ($lembagaIds as $id) {
                $l = $lembagas[$id] ?? null;
                if (! $l) {
                    continue;
                }
                $distance = AutoMatchService::teamDistanceToLembaga($team->members, $l);
                DB::table('team_lembaga')->insert([
                    'tahap_id' => $tahap->id,
                    'team_id' => $team->id,
                    'lembaga_id' => $l->id,
                    'distance_km' => $distance !== null ? round($distance, 3) : null,
                    'is_manual' => true,
                    'assigned_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $count++;
            }
        });

        return back()->with('success', "Tim {$team->code} dipasangkan manual ke {$count} lembaga.");
    }

    public function unassign(Request $request, Tahap $tahap, $assignment = null)
    {
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        $assignmentId = $request->input('assignment_id');
        if ($assignmentId === null) {
            $assignmentId = $assignment;
        }
        if ($assignmentId === null || (int) $assignmentId < 1) {
            return back()->with('error', 'Parameter pasangan tidak ditemukan.');
        }

        $deleted = TeamLembaga::where('tahap_id', $tahap->id)->where('id', (int) $assignmentId)->delete();

        return back()->with($deleted ? 'success' : 'error', $deleted
            ? 'Pasangan berhasil dilepas.'
            : 'Pasangan tidak ditemukan pada tahap ini.');
    }

    /**
     * Batal/hapus banyak pasangan sekaligus dari Hasil Pairing —
     * lembaga yang dibatalkan kembali ke daftar Lembaga Tersisa.
     */
    public function batchCancel(Request $request, Tahap $tahap)
    {
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        $data = $request->validate([
            'assignment_ids' => ['required', 'array', 'min:1'],
            'assignment_ids.*' => ['integer', 'distinct'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['assignment_ids'])));
        $deleted = DB::table('team_lembaga')
            ->where('tahap_id', $tahap->id)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', $deleted.' pasangan dibatalkan — lembaga kembali ke daftar Lembaga Tersisa.');
    }

    /**
     * DataTables server-side "Manual Override — Tim Belum Penuh".
     * Kolom: no, team, NIA A, Nama A, NIA B, Nama B, aksi assign.
     */
    public function unmatchedData(Request $request, Tahap $tahap)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $teams = $this->finalizedTeams($tahap);
        $rows = $this->assignmentRows($tahap);
        $kuotaMap = AutoMatchService::kuotaMap($tahap->id, $teams);
        $countByTeam = [];
        foreach ($rows as $r) {
            $countByTeam[$r->team_id] = ($countByTeam[$r->team_id] ?? 0) + 1;
        }

        $locked = $this->isLocked($tahap);

        // Bila tidak ada lembaga tersisa, tidak ada yang bisa di-assign manual.
        $attachedCount = (int) DB::table('lembaga_tahap')->where('tahap_id', $tahap->id)->count();
        if (count($countByTeam) === 0 || count($rows) >= $attachedCount) {
            return DataTables::of(collect([]))->make(true);
        }

        $items = [];
        foreach ($teams as $team) {
            $assigned = $countByTeam[$team->id] ?? 0;
            $kuota = $kuotaMap[$team->id] ?? 0;
            if ($assigned >= $kuota) {
                continue;
            }

            $members = $team->members->sortBy('id')->values();
            $memberOf = function ($i) use ($members) {
                $m = $members->get($i);

                return $m && $m->user
                    ? ['nia' => (string) ($m->user->nia ?? ''), 'name' => (string) ($m->user->name ?? '')]
                    : ['nia' => '', 'name' => '-'];
            };
            $a = $memberOf(0);
            $b = $memberOf(1);

            $sisa = max(0, $kuota - $assigned);
            $action = $locked
                ? '<span class="text-muted fs-13">Terkunci</span>'
                : '<div style="min-width:340px">'
                    .'<div class="text-muted fs-12 mb-1">Sisa kuota: <strong>'.$sisa.'</strong> lembaga (maks '.$kuota.')</div>'
                    .'<input type="text" class="form-control form-control-sm lembaga-input mb-1" list="lembaga-datalist" data-team="'.$team->id.'" placeholder="Ketik NPSN / nama / kabupaten lalu pilih dari daftar…" autocomplete="off">'
                    .'<div class="selected-chips d-flex flex-wrap gap-1 mb-1" data-team="'.$team->id.'" data-max="'.$sisa.'"></div>'
                    .'<button type="button" class="btn btn-sm btn-primary assign-btn text-nowrap" data-team="'.$team->id.'" disabled>Assign Lembaga Terpilih</button>'
                    .'</div>';

            $items[] = [
                'code' => $team->code ?? 'T'.$team->id,
                'nia_a' => $a['nia'],
                'nama_a' => $a['name'],
                'nia_b' => $b['nia'],
                'nama_b' => $b['name'],
                'sisa' => $sisa,
                'action' => $action,
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    public function lembagaOptions(Request $request, Tahap $tahap)
    {
        $q = trim((string) $request->input('q'));
        $taken = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->pluck('lembaga_id');

        $query = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->where('lembaga_tahap.tahap_id', $tahap->id)
            ->whereNotIn('lembagas.id', $taken)
            ->select('lembagas.id', 'lembagas.npsn', 'lembagas.satuan_pen', 'lembagas.kabupaten');

        if ($q !== '') {
            $like = '%'.$q.'%';
            $query->where(function ($b) use ($like) {
                $b->where('lembagas.npsn', 'like', $like)
                    ->orWhere('lembagas.satuan_pen', 'like', $like)
                    ->orWhere('lembagas.kabupaten', 'like', $like);
            });
        }

        $results = $query->limit(30)->get()->map(fn ($l) => [
            'id' => $l->id,
            'npsn' => $l->npsn,
            'name' => $l->satuan_pen,
            'kabupaten' => $l->kabupaten,
        ]);

        return response()->json(['results' => $results]);
    }

    /**
     * Halaman "Lembaga Belum Terpetakan (Tersisa)" — DataTables server-side.
     */
    public function remainingIndex(Tahap $tahap)
    {
        $stats = $this->pairingStats($tahap);

        return view('menu.admin.tahap.generation.tersisa', [
            'tahap' => $tahap,
            'count' => $stats['tersisa'],
            'stats' => $stats,
        ]);
    }

    /**
     * DataTables server-side: lembaga tahap yang BELUM dipetakan (tersisa).
     */
    public function remainingLembagas(Request $request, Tahap $tahap)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $taken = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->pluck('lembaga_id');

        return DataTables::of(
            DB::table('lembagas')
                ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
                ->where('lembaga_tahap.tahap_id', $tahap->id)
                ->whereNotIn('lembagas.id', $taken)
                ->select('lembagas.id', 'lembagas.npsn', 'lembagas.satuan_pen', 'lembagas.kabupaten', 'lembagas.kecamatan', 'lembagas.jenjang')
        )
            ->make(true);
    }

    /**
     * Hapus lembaga dari tahap (lembaga tersisa tidak diikutsertakan lagi).
     */
    public function remainingDetach(Request $request, Tahap $tahap, Lembaga $lembaga)
    {
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        DB::table('lembaga_tahap')->where('tahap_id', $tahap->id)->where('lembaga_id', $lembaga->id)->delete();

        return back()->with('success', "Lembaga {$lembaga->satuan_pen} (NPSN {$lembaga->npsn}) dihapus dari tahap — tidak diikutsertakan lagi.");
    }

    /**
     * Data seluruh asesor (role asesor, termasuk yang belum mengisi kesanggupan)
     * untuk modal Ubah pada tabel Hasil Pairing per NPSN.
     */
    public function asesorData(Request $request, Tahap $tahap)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $users = User::role('asesor')->select(['id', 'name', 'nia'])->orderBy('name')->get();

        // Tim final tahap ini per user (asumsi satu tim per user per tahap).
        $teamMap = [];
        foreach (
            DB::table('team_members')
                ->join('teams', 'teams.id', '=', 'team_members.team_id')
                ->where('teams.tahap_id', $tahap->id)
                ->whereNotNull('teams.finalized_at')
                ->select('team_members.user_id', 'teams.id as team_id', 'teams.code')
                ->get() as $x
        ) {
            if (! isset($teamMap[$x->user_id])) {
                $teamMap[$x->user_id] = ['id' => $x->team_id, 'code' => $x->code];
            }
        }

        $kesMap = DB::table('kesanggupans')
            ->where('tahap_id', $tahap->id)
            ->select('user_id', 'kesediaan', 'kesanggupan')
            ->get()
            ->keyBy('user_id');

        $data = [];
        foreach ($users as $u) {
            $k = $kesMap->get($u->id);
            $kesLabel = $k
                ? ($k->kesediaan ? (string) $k->kesanggupan : 'Tidak bersedia')
                : 'Belum isi';
            $data[] = [
                'id' => $u->id,
                'nia' => $u->nia ?? '',
                'name' => $u->name,
                'team_id' => $teamMap[$u->id]['id'] ?? null,
                'team_code' => $teamMap[$u->id]['code'] ?? '',
                'kesanggupan' => $kesLabel,
            ];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Ubah asesor A ATAU B untuk SATU baris pairing (per NPSN) — hanya slot
     * yang diklik yang berubah; slot satunya dibekukan dengan asesor aslinya.
     * Override manual mengabaikan batas maksimal penugasan/kuota.
     * Baris dipindah ke tim milik asesor pilihan (bila ada); bila asesor tidak
     * punya tim, baris menjadi tanpa tim (team null).
     */
    public function setAsesor(Request $request, Tahap $tahap)
    {
        if (! $this->ensureUnlocked($tahap)) {
            return response()->json(['ok' => false, 'message' => 'Data pairing sedang dikunci.'], 403);
        }

        $data = $request->validate([
            'assignment_id' => ['required', 'integer', 'exists:team_lembaga,id'],
            'slot' => ['required', 'in:a,b'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $row = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->where('id', $data['assignment_id'])->first();
        if (! $row) {
            return response()->json(['ok' => false, 'message' => 'Baris pairing tidak ditemukan.'], 404);
        }
        $user = User::with('detail')->find($data['user_id']);
        if (! $user) {
            return response()->json(['ok' => false, 'message' => 'Asesor tidak ditemukan.'], 404);
        }

        $slot = $data['slot'];
        $col = $slot === 'a' ? 'asesor_a_user_id' : 'asesor_b_user_id';
        $otherCol = $slot === 'a' ? 'asesor_b_user_id' : 'asesor_a_user_id';
        $otherIdx = $slot === 'a' ? 1 : 0;

        // Tim lama (hanya untuk membaca fallback slot lain supaya TIDAK ikut berubah).
        $oldTeam = $row->team_id ? Team::with('members.user.detail')->find($row->team_id) : null;
        $oldMembers = $oldTeam ? $oldTeam->members->sortBy('id')->values() : collect();

        $otherId = $row->{$otherCol};
        if (! $otherId && $oldMembers->count() > $otherIdx) {
            $otherId = $oldMembers->get($otherIdx)->user_id;
        }
        if ($otherId != null && (int) $otherId === (int) $user->id) {
            return response()->json([
                'ok' => false,
                'message' => 'Asesor '.strtoupper($slot).' tidak boleh sama dengan asesor pada slot satunya.',
            ], 422);
        }

        // Tim final tempat asesor pilihan tergabung (tanpa validasi kapasitas — override manual).
        $memberTeam = DB::table('team_members')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.tahap_id', $tahap->id)
            ->whereNotNull('teams.finalized_at')
            ->where('team_members.user_id', $user->id)
            ->select('teams.id')
            ->first();
        $team = $memberTeam ? Team::with('members.user.detail')->find($memberTeam->id) : null;

        // Objek user slot lain (untuk hitung ulang jarak).
        $otherUser = null;
        if ($otherId) {
            $otherUser = $oldMembers->firstWhere('user_id', (int) $otherId)?->user;
            if (! $otherUser) {
                $otherUser = $this->usersWithDetail([$otherId])[$otherId] ?? null;
            }
        }

        $kmA = $this->asesorKmToLembaga($slot === 'a' ? $user : $otherUser, Lembaga::find($row->lembaga_id));
        $kmB = $this->asesorKmToLembaga($slot === 'b' ? $user : $otherUser, Lembaga::find($row->lembaga_id));
        $kms = array_values(array_filter([$kmA, $kmB], fn ($v) => $v !== null));
        $distance = $kms ? max($kms) : null;

        // Slot satunya ikut ditulis (dibekukan) agar tidak berubah saat tim bergeser.
        DB::table('team_lembaga')->where('id', $row->id)->update([
            'team_id' => $team ? $team->id : null,
            $col => $user->id,
            $otherCol => $otherId ?: null,
            'distance_km' => $distance,
            'updated_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'nia' => $user->nia ?? '',
            'name' => $user->name,
            'team_code' => $team ? ($team->code ?? 'T'.$team->id) : '',
        ]);
    }

    public function download(Request $request, Tahap $tahap)
    {
        $this->bumpLimits();

        $teams = $this->finalizedTeams($tahap);
        $rows = $this->assignmentRows($tahap);
        $kuotaMap = AutoMatchService::kuotaMap($tahap->id, $teams);

        $countByTeam = [];
        $taken = [];
        foreach ($rows as $r) {
            if ($r->team_id) {
                $countByTeam[$r->team_id] = ($countByTeam[$r->team_id] ?? 0) + 1;
            }
            $taken[$r->lembaga_id] = true;
        }

        // Sheet 1: Hasil Pairing — per NPSN, asesor efektif per baris (override bila ada).
        $results = [];
        foreach ($this->pairingRows($tahap) as $p) {
            $uA = $p['pair'][0];
            $uB = $p['pair'][1];
            $results[] = [
                $p['code'],
                $p['npsn'],
                $p['name'],
                $p['kabupaten'],
                $uA?->nia ?? '',
                $uA?->name ?? '',
                $uA?->detail?->work_city ?? '',
                $uB?->nia ?? '',
                $uB?->name ?? '',
                $uB?->detail?->work_city ?? '',
            ];
        }

        // Sheet 2: Manual Override — tim belum penuh.
        $manualOverride = [];
        foreach ($teams as $team) {
            $assigned = $countByTeam[$team->id] ?? 0;
            $kuota = $kuotaMap[$team->id] ?? 0;
            $sisa = max(0, $kuota - $assigned);
            if ($sisa > 0) {
                $members = $team->members->sortBy('id')->values();
                $mA = $members->get(0);
                $mB = $members->get(1);
                $manualOverride[] = [
                    'team_code' => $team->code ?? 'T'.$team->id,
                    'nia_a' => $mA?->user?->nia ?? '',
                    'nama_a' => $mA?->user?->name ?? '',
                    'nia_b' => $mB?->user?->nia ?? '',
                    'nama_b' => $mB?->user?->name ?? '',
                    'sisa_kuota' => $sisa,
                    'npsn' => '',
                    'nama_lembaga' => '',
                ];
            }
        }

        // Sheet 3: Lembaga Tersisa.
        $remaining = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->where('lembaga_tahap.tahap_id', $tahap->id)
            ->whereNotIn('lembagas.id', array_keys($taken))
            ->select('lembagas.npsn', 'lembagas.satuan_pen', 'lembagas.kabupaten', 'lembagas.kecamatan', 'lembagas.jenjang')
            ->orderBy('lembagas.kabupaten')
            ->orderBy('lembagas.satuan_pen')
            ->get()
            ->map(fn ($l) => [
                $l->npsn ?? '',
                $l->satuan_pen ?? '',
                $l->kabupaten ?? '',
                $l->kecamatan ?? '',
                $l->jenjang ?? '',
                'Tersisa',
            ])
            ->all();

        $filename = 'pairing_tahap_'.$tahap->slug.'_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new PairingExport($results, $manualOverride, $remaining), $filename);
    }

    /**
     * Upload hasil rapikan manual: isi kolom npsn/nama pada sheet "Manual
     * Override" dari file yang diunduh (atau CSV terpisah), lalu pasangkan ke
     * tim yang belum penuh.
     */
    public function upload(Request $request, Tahap $tahap)
    {
        // File unduhan bisa sangat besar (ribuan baris di sheet Hasil Pairing),
        // PhpSpreadsheet butuh memori ekstra saat membaca workbook.
        $this->bumpLimits();
        if (! $this->ensureUnlocked($tahap)) {
            return back();
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ]);

        $errors = [];
        $data = $this->readUploadSheets($request->file('file'), $errors);
        if ($data === null) {
            return back()->with('error', implode('<br>', $errors));
        }
        $manualRows = $data['manual'];
        $hasilRows = $data['hasil'];
        if (! $manualRows && ! $hasilRows) {
            return back()->with('error', 'File tidak berisi baris isian: isi sheet "Manual Override" (Kode Tim + NPSN/Nama Lembaga) atau tambahkan baris lembaga+asesor di sheet "Hasil Pairing".');
        }

        try {
            $res = $this->buildUploadPlan($tahap, $manualRows, $hasilRows, $data['has_hasil'] ?? false);
        } catch (\Throwable $e) {
            return back()->with('error', 'Upload gagal diproses: '.$e->getMessage());
        }

        if (! $res['ok']) {
            return back()->with('error', 'Upload ditolak:<br>'.implode('<br>', array_slice($res['errors'], 0, 30)));
        }

        $plan = $res['plan'];
        if (! empty($plan['delete_ids'])) {
            session()->put('pairing_upload_plan', [
                'tahap_id' => $tahap->id,
                'user_id' => Auth::id(),
                'created_at' => now()->timestamp,
                'plan' => $plan,
                'new' => $plan['counts']['new'],
                'update' => $plan['counts']['update'],
                'delete' => count($plan['delete_ids']),
            ]);

            return back()->with('info', 'Upload perlu ditinjau: '.count($plan['delete_ids']).' pasangan akan dihapus karena tidak ada di file.');
        }

        return back()->with('success', $this->resultMessage($this->commitUploadPlan($tahap, $plan)));
    }

    /**
     * Terapkan isian file upload:
     *  1) sheet Manual Override -> tim + lembaga (batas kuota tim dihormati);
     *  2) sheet Hasil Pairing   -> pasangan per NPSN (asesor A/B manual, batas
     *     penugasan diabaikan, lembaga yang belum punya baris akan ditambahkan).
     *
     * @return RedirectResponse
     */
    /**
     * Validasi & susun rencana upload (TANPA menulis DB), agar upload bisa
     * dipratinjau dulu bila akan menghapus pasangan (sinkron penuh).
     *
     * @return array{ok: bool, errors: array, plan: ?array}
     */
    protected function buildUploadPlan(Tahap $tahap, array $manualRows, array $hasilRows, bool $hasHasil): array
    {
        $teams = $this->finalizedTeams($tahap);
        $teamsByCode = $teams->keyBy(fn ($t) => (string) ($t->code ?? 'T'.$t->id));
        $teamsById = $teams->keyBy('id');
        $kuotaMap = AutoMatchService::kuotaMap($tahap->id, $teams);

        $existing = $this->assignmentRows($tahap);
        $rowByLembaga = [];
        $assignedLembaga = [];
        $countByTeam = [];
        foreach ($existing as $e) {
            $rowByLembaga[$e->lembaga_id] = $e;
            $assignedLembaga[$e->lembaga_id] = true;
            if ($e->team_id) {
                $countByTeam[$e->team_id] = ($countByTeam[$e->team_id] ?? 0) + 1;
            }
        }

        $attached = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->where('lembaga_tahap.tahap_id', $tahap->id)
            ->select('lembagas.id', 'lembagas.npsn', 'lembagas.satuan_pen', 'lembagas.latitude', 'lembagas.longitude')
            ->get();
        $byNpsn = [];
        $byName = [];
        foreach ($attached as $l) {
            if ($l->npsn !== null && $l->npsn !== '') {
                $byNpsn[(string) $l->npsn] = $l;
            }
            $byName[(string) $l->satuan_pen] = $l;
        }

        $asesors = User::role('asesor')->with('detail')->get();
        $byNia = [];
        $byNameUser = [];
        foreach ($asesors as $u) {
            if ($u->nia !== null && $u->nia !== '') {
                $byNia[(string) $u->nia] = $u;
            }
            $byNameUser[strtolower(trim($u->name))] = $u;
        }
        $teamOfUser = [];
        foreach (
            DB::table('team_members')
                ->join('teams', 'teams.id', '=', 'team_members.team_id')
                ->where('teams.tahap_id', $tahap->id)
                ->whereNotNull('teams.finalized_at')
                ->select('team_members.user_id', 'teams.id as team_id')
                ->get() as $x
        ) {
            if (! isset($teamOfUser[$x->user_id])) {
                $teamOfUser[$x->user_id] = $x->team_id;
            }
        }

        $errors = [];
        $seen = [];
        $manualCommits = [];
        $hasilCommits = [];

        // ---------- Isian tim (sheet Manual Override) ----------
        foreach ($manualRows as $i => $row) {
            $teamCode = trim((string) ($row['team_code'] ?? ''));
            $npsn = trim((string) ($row['npsn'] ?? ''));
            $nama = trim((string) ($row['nama_lembaga'] ?? ''));
            if ($teamCode === '' && $npsn === '' && $nama === '') {
                continue;
            }
            if ($teamCode === '') {
                $errors[] = 'Sheet Manual Override baris '.($i + 2).': kolom Kode Tim kosong.';

                continue;
            }
            if (! isset($teamsByCode[$teamCode])) {
                $errors[] = 'Sheet Manual Override baris '.($i + 2).": tim '{$teamCode}' tidak ditemukan (final) pada tahap ini.";

                continue;
            }
            $lembaga = $npsn !== '' && isset($byNpsn[$npsn]) ? $byNpsn[$npsn] : (isset($byName[$nama]) ? $byName[$nama] : null);
            if (! $lembaga) {
                $errors[] = 'Sheet Manual Override baris '.($i + 2).": lembaga (NPSN '{$npsn}' / '{$nama}') tidak ditemukan pada tahap ini.";

                continue;
            }
            if (isset($assignedLembaga[$lembaga->id]) || isset($seen[$lembaga->id])) {
                $errors[] = 'Sheet Manual Override baris '.($i + 2).': lembaga tersebut sudah terpasang (di file atau di data saat ini).';

                continue;
            }
            $seen[$lembaga->id] = true;
            $manualCommits[] = [
                'team_id' => $teamsByCode[$teamCode]->id,
                'lembaga_id' => $lembaga->id,
                'distance_km' => null, // dihitung saat commit
            ];
        }

        $pendingByTeam = [];
        foreach ($manualCommits as $p) {
            $pendingByTeam[$p['team_id']] = ($pendingByTeam[$p['team_id']] ?? 0) + 1;
        }
        foreach ($pendingByTeam as $teamId => $n) {
            if (($countByTeam[$teamId] ?? 0) + $n > ($kuotaMap[$teamId] ?? 0)) {
                $team = $teamsById[$teamId];
                $errors[] = "Tim '".($team->code ?? 'T'.$teamId)."' melebihi kuota (maks ".($kuotaMap[$teamId] ?? 0).').';
            }
        }

        // ---------- Pasangan per NPSN (sheet Hasil Pairing) ----------
        $newCount = 0;
        $updateCount = 0;
        foreach ($hasilRows as $i => $row) {
            $npsn = trim((string) ($row['npsn'] ?? ''));
            $niaA = trim((string) ($row['nia_a'] ?? ''));
            $namaA = trim((string) ($row['nama_a'] ?? ''));
            $niaB = trim((string) ($row['nia_b'] ?? ''));
            $namaB = trim((string) ($row['nama_b'] ?? ''));
            if ($npsn === '' && $niaA === '' && $namaA === '' && $niaB === '' && $namaB === '') {
                continue;
            }
            if ($npsn === '') {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).': NPSN kosong.';

                continue;
            }
            if (! isset($byNpsn[$npsn])) {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).": NPSN '{$npsn}' tidak terdaftar pada tahap ini.";

                continue;
            }
            $lembaga = $byNpsn[$npsn];
            if (isset($seen[$lembaga->id])) {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).': lembaga tersebut sudah diatur di baris lain pada file ini.';

                continue;
            }
            $seen[$lembaga->id] = true;

            $userA = $this->resolveAsesorUpload($niaA, $namaA, $byNia, $byNameUser);
            $userB = $this->resolveAsesorUpload($niaB, $namaB, $byNia, $byNameUser);
            if ($userA === false) {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).": asesor A (NIA '{$niaA}' / '{$namaA}') tidak ditemukan.";

                continue;
            }
            if ($userB === false) {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).": asesor B (NIA '{$niaB}' / '{$namaB}') tidak ditemukan.";

                continue;
            }
            if ($userA !== null && $userB !== null && $userA->id === $userB->id) {
                $errors[] = 'Sheet Hasil Pairing baris '.($i + 2).': asesor A dan B tidak boleh sama.';

                continue;
            }

            $teamId = null;
            if ($userA && isset($teamOfUser[$userA->id])) {
                $teamId = $teamOfUser[$userA->id];
            } elseif ($userB && isset($teamOfUser[$userB->id])) {
                $teamId = $teamOfUser[$userB->id];
            }
            $kmA = $this->asesorKmToLembaga($userA, $lembaga);
            $kmB = $this->asesorKmToLembaga($userB, $lembaga);
            $kms = array_values(array_filter([$kmA, $kmB], fn ($v) => $v !== null));
            $distance = $kms ? max($kms) : null;

            $cur = $rowByLembaga[$lembaga->id] ?? null;
            $update = false;
            if ($cur) {
                $same = ((int) ($cur->team_id ?? 0) === (int) ($teamId ?? 0))
                    && ((int) ($cur->asesor_a_user_id ?? 0) === (int) ($userA?->id ?? 0))
                    && ((int) ($cur->asesor_b_user_id ?? 0) === (int) ($userB?->id ?? 0));
                $update = ! $same;
            }

            $hasilCommits[] = [
                'lembaga_id' => $lembaga->id,
                'team_id' => $teamId,
                'asesor_a' => $userA?->id,
                'asesor_b' => $userB?->id,
                'distance_km' => $distance,
                'is_new' => ! $cur,
                'update' => $update,
            ];
            if (! $cur) {
                $newCount++;
            } elseif ($update) {
                $updateCount++;
            }
        }

        if ($errors) {
            return ['ok' => false, 'errors' => $errors, 'plan' => null];
        }

        $desired = [];
        foreach ($manualCommits as $m) {
            $desired[$m['lembaga_id']] = true;
        }
        foreach ($hasilCommits as $h) {
            $desired[$h['lembaga_id']] = true;
        }

        $deleteIds = [];
        if ($hasHasil) {
            foreach ($existing as $e) {
                if (! isset($desired[$e->lembaga_id])) {
                    $deleteIds[] = $e->id;
                }
            }
        }

        $plan = [
            'manual' => $manualCommits,
            'hasil' => $hasilCommits,
            'delete_ids' => $deleteIds,
            'counts' => ['new' => $newCount, 'update' => $updateCount],
        ];

        return ['ok' => true, 'errors' => [], 'plan' => $plan];
    }

    /**
     * Terapkan rencana upload dalam satu transaksi.
     *
     * @return array{added:int, updated:int, removed:int}
     */
    protected function commitUploadPlan(Tahap $tahap, array $plan): array
    {
        $userId = Auth::id();
        $now = now();
        $added = 0;
        $updated = 0;
        $removed = 0;

        DB::transaction(function () use ($tahap, $plan, $userId, $now, &$added, &$updated, &$removed) {
            $teamsById = null;
            foreach ($plan['manual'] as $m) {
                if ($teamsById === null) {
                    $teams = $this->finalizedTeams($tahap);
                    $teamsById = $teams->keyBy('id');
                }
                $team = $teamsById[$m['team_id']] ?? null;
                $lembaga = DB::table('lembagas')->where('id', $m['lembaga_id'])->first();
                $distance = $m['distance_km'];
                if ($team && $lembaga) {
                    $d = AutoMatchService::teamDistanceToLembaga($team->members, $lembaga);
                    $distance = $d !== null ? round($d, 3) : null;
                }
                DB::table('team_lembaga')->insert([
                    'tahap_id' => $tahap->id,
                    'team_id' => $m['team_id'],
                    'lembaga_id' => $m['lembaga_id'],
                    'distance_km' => $distance,
                    'is_manual' => true,
                    'assigned_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $added++;
            }

            foreach ($plan['hasil'] as $h) {
                $cur = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->where('lembaga_id', $h['lembaga_id'])->first();
                $values = [
                    'team_id' => $h['team_id'],
                    'distance_km' => $h['distance_km'],
                    'is_manual' => true,
                    'asesor_a_user_id' => $h['asesor_a'],
                    'asesor_b_user_id' => $h['asesor_b'],
                    'assigned_by' => $userId,
                    'updated_at' => $now,
                ];
                if ($cur) {
                    $same = ((int) ($cur->team_id ?? 0) === (int) ($h['team_id'] ?? 0))
                        && ((int) ($cur->asesor_a_user_id ?? 0) === (int) ($h['asesor_a'] ?? 0))
                        && ((int) ($cur->asesor_b_user_id ?? 0) === (int) ($h['asesor_b'] ?? 0));
                    if ($same) {
                        continue;
                    }
                    DB::table('team_lembaga')->where('id', $cur->id)->update($values);
                    $updated++;
                } else {
                    $values['tahap_id'] = $tahap->id;
                    $values['lembaga_id'] = $h['lembaga_id'];
                    $values['created_at'] = $now;
                    DB::table('team_lembaga')->insert($values);
                    $added++;
                }
            }

            if ($plan['delete_ids']) {
                $removed = DB::table('team_lembaga')
                    ->where('tahap_id', $tahap->id)
                    ->whereIn('id', $plan['delete_ids'])
                    ->delete();
            }
        });

        return ['added' => $added, 'updated' => $updated, 'removed' => $removed];
    }

    /**
     * Konfirmasi menerapkan rencana upload yang disimpan di session.
     */
    public function uploadConfirm(Request $request, Tahap $tahap)
    {
        $stored = session('pairing_upload_plan');
        if (! $stored || ($stored['tahap_id'] ?? null) !== $tahap->id || ($stored['user_id'] ?? null) !== Auth::id()) {
            return back()->with('error', 'Sesi upload tidak valid atau kedaluwarsa. Silakan upload ulang file.');
        }
        if ((now()->timestamp - (int) ($stored['created_at'] ?? 0)) > 1800) {
            session()->forget('pairing_upload_plan');

            return back()->with('error', 'Sesi upload kedaluwarsa (lebih dari 30 menit). Silakan upload ulang file.');
        }

        $result = $this->commitUploadPlan($tahap, $stored['plan']);
        session()->forget('pairing_upload_plan');

        return back()->with('success', $this->resultMessage($result));
    }

    /**
     * Batalkan rencana upload (tidak ada perubahan data).
     */
    public function uploadCancel(Request $request, Tahap $tahap)
    {
        session()->forget('pairing_upload_plan');

        return back()->with('success', 'Upload dibatalkan — tidak ada perubahan data.');
    }

    /**
     * Pesan ringkas hasil commit upload.
     */
    protected function resultMessage(array $result): string
    {
        $msg = "Upload berhasil: {$result['added']} pasangan baru, {$result['updated']} pasangan diperbarui.";
        if (($result['removed'] ?? 0) > 0) {
            $msg .= ' '.$result['removed'].' pasangan dihapus (disinkron dari file — kembali ke Lembaga Tersisa).';
        }

        return $msg;
    }

    /**
     * Cari asesor dari isian NIA / nama (case-insensitive).
     *
     * @return User|null|false null=kolom kosong, false=identifier ada tapi tidak ditemukan
     */
    protected function resolveAsesorUpload($nia, $nama, array $byNia, array $byNameUser)
    {
        $nia = trim((string) $nia);
        $nama = trim((string) $nama);
        if ($nia !== '') {
            return isset($byNia[$nia]) ? $byNia[$nia] : false;
        }
        if ($nama !== '') {
            $key = strtolower($nama);

            return isset($byNameUser[$key]) ? $byNameUser[$key] : false;
        }

        return null;
    }

    /**
     * Kunci data pairing agar tidak bisa diubah lagi.
     */
    public function lock(Request $request, Tahap $tahap)
    {
        $tahap->update([
            'pairing_locked_at' => now(),
            'pairing_locked_by' => Auth::id(),
        ]);

        return back()->with('success', 'Data pairing dikunci. Untuk mengubahnya, gunakan tombol Buka Kunci.');
    }

    /**
     * Buka kunci data pairing (cancel lock).
     */
    public function unlock(Request $request, Tahap $tahap)
    {
        $tahap->update([
            'pairing_locked_at' => null,
            'pairing_locked_by' => null,
        ]);

        return back()->with('success', 'Kunci data pairing dibuka. Data dapat diubah kembali.');
    }

    /**
     * Baca file upload (CSV/XLSX) menjadi baris isian untuk dua sumber:
     *  - 'manual': sheet "Manual Override" (Kode Tim + NPSN/Nama Lembaga);
     *  - 'hasil' : sheet "Hasil Pairing" (NPSN + NIA/Nama Asesor A & B).
     *  - 'has_hasil' : apakah file memuat sheet Hasil Pairing (sinkron penuh).
     * XLSX dibaca hemat memori: data-only, hanya sheet yang dibutuhkan.
     *
     * @return array{manual: array, hasil: array, has_hasil: bool}|null
     */
    protected function readUploadSheets($file, array &$errors): ?array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['csv', 'txt'], true)) {
            $handle = fopen($file->getRealPath(), 'rb');
            if ($handle === false) {
                $errors[] = 'Gagal membuka file CSV.';

                return null;
            }
            $raw = [];
            while (($row = fgetcsv($handle)) !== false) {
                $raw[] = $row;
            }
            fclose($handle);

            $classified = $this->classifyRawSheet($raw, $errors);
            $classified['has_hasil'] = ! empty($classified['hasil']) && empty($classified['manual']);

            return $classified;
        }

        if ($ext === 'xlsx') {
            try {
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);

                $sheetNames = $reader->listWorksheetNames($file->getRealPath());
                $wanted = array_values(array_intersect($sheetNames, ['Hasil Pairing', 'Manual Override']));
                if ($wanted) {
                    $reader->setLoadSheetsOnly($wanted);
                }

                $spreadsheet = $reader->load($file->getRealPath());
                $out = ['manual' => [], 'hasil' => [], 'has_hasil' => false];
                $hasNamedHasil = $spreadsheet->getSheetByName('Hasil Pairing') !== null;
                $out['has_hasil'] = $hasNamedHasil;

                $manualSheet = $spreadsheet->getSheetByName('Manual Override');
                if ($manualSheet) {
                    $rows = $this->classifyRawSheet($manualSheet->toArray(null, true, true, false), $errors, true);
                    $out['manual'] = $rows['manual'];
                    $out['hasil'] = array_merge($out['hasil'], $rows['hasil']);
                }
                $hasilSheet = $spreadsheet->getSheetByName('Hasil Pairing');
                if ($hasilSheet) {
                    $rows = $this->classifyRawSheet($hasilSheet->toArray(null, true, true, false), $errors, false);
                    $out['hasil'] = array_merge($out['hasil'], $rows['hasil']);
                }

                // Bila sheet tidak bernama seperti di atas (file diedit), coba sheet aktif.
                if (! $manualSheet && ! $hasilSheet) {
                    $active = $spreadsheet->getActiveSheet();
                    $rows = $this->classifyRawSheet($active->toArray(null, true, true, false), $errors);
                    $out['manual'] = $rows['manual'];
                    $out['hasil'] = array_merge($out['hasil'], $rows['hasil']);
                    $out['has_hasil'] = $out['has_hasil'] || ! empty($rows['hasil']);
                }

                return $out;
            } catch (\Throwable $e) {
                $errors[] = 'Gagal membaca file Excel: '.$e->getMessage();

                return null;
            }
        }

        $errors[] = 'Format file tidak didukung.';

        return null;
    }

    /**
     * Deteksi jenis isian dari baris mentah (header) lalu ekstrak barisnya.
     * $forceManual dipakai untuk sheet bernama "Manual Override".
     *
     * @return array{manual: array, hasil: array}
     */
    protected function classifyRawSheet(array $raw, array &$errors, ?bool $forceManual = null): array
    {
        $raw = array_values(array_filter($raw, fn ($r) => is_array($r) && count(array_filter((array) $r, fn ($c) => trim((string) $c) !== '')) > 0));
        if (! $raw) {
            return ['manual' => [], 'hasil' => []];
        }

        $header = array_values(array_map(fn ($h) => strtolower(trim((string) $h)), $raw[0]));
        $norm = [];
        foreach ($header as $i => $h) {
            $norm[$h] = $i;
        }
        $find = function (array $names) use ($norm) {
            foreach ($names as $n) {
                if (isset($norm[$n])) {
                    return $norm[$n];
                }
            }

            return null;
        };

        $hasTeam = $find(['team_code', 'kode tim', 'kode_tim', 'team']) !== null;
        $hasNpsn = $find(['npsn']) !== null;
        $hasAsesor = $find(['nia a', 'nia_a', 'nia asesor a', 'nama a', 'nama_a', 'nama asesor a', 'nia b', 'nia_b', 'nia asesor b', 'nama b', 'nama_b', 'nama asesor b']) !== null;

        $isManual = $forceManual ?? ($hasTeam && $hasNpsn);
        $isHasil = $forceManual === false || ($hasNpsn && $hasAsesor && ! $isManual);

        if (! $isManual && ! $isHasil) {
            $errors[] = 'Kolom file tidak dikenali. Gunakan file hasil unduhan (sheet Hasil Pairing / Manual Override) tanpa mengubah baris header.';

            return ['manual' => [], 'hasil' => []];
        }

        $defs = $isManual
            ? [
                'team_code' => ['team_code', 'kode tim', 'kode_tim', 'team'],
                'npsn' => ['npsn'],
                'nama_lembaga' => ['nama_lembaga', 'nama lembaga', 'nama_lembaga (isi)', 'nama lembaga (isi)', 'nama'],
            ]
            : [
                'npsn' => ['npsn'],
                'nia_a' => ['nia a', 'nia_a', 'nia asesor a'],
                'nama_a' => ['nama a', 'nama_a', 'nama asesor a'],
                'nia_b' => ['nia b', 'nia_b', 'nia asesor b'],
                'nama_b' => ['nama b', 'nama_b', 'nama asesor b'],
            ];

        $cols = [];
        foreach ($defs as $key => $aliases) {
            $cols[$key] = $find($aliases);
        }

        $out = [];
        foreach (array_slice($raw, 1) as $data) {
            $row = [];
            foreach ($cols as $key => $idx) {
                $row[$key] = ($idx !== null && isset($data[$idx])) ? (string) $data[$idx] : '';
            }
            $out[] = $row;
        }

        return $isManual
            ? ['manual' => $out, 'hasil' => []]
            : ['manual' => [], 'hasil' => $out];
    }
}
