<?php

namespace App\Services;

use App\Models\Kesanggupan;
use App\Models\Lembaga;
use App\Models\Tahap;
use App\Models\Team;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;
use App\Models\TeamGenerationRun;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Logika halaman Detail Tahap: data kesanggupan asesor + pasangan (tim) asesor.
 *
 * Sumber pasangan mengikuti "mode" tahap:
 *  - draft : run terakhir masih berstatus draft -> team_drafts / team_draft_members
 *  - final : tim final sudah ada                -> teams / team_members
 *  - none  : belum ada pasangan sama sekali
 */
class TahapPairingService
{
    public const MODE_DRAFT = 'draft';

    public const MODE_FINAL = 'final';

    public const MODE_NONE = 'none';

    /** Cache pasangan per tahap agar tidak dihitung berulang dalam satu request. */
    private array $pairsCache = [];

    private array $kesCache = [];

    // ------------------------------------------------------------------
    // Mode & kunci data
    // ------------------------------------------------------------------

    public function isLocked(Tahap $tahap): bool
    {
        return $tahap->pairing_locked_at !== null;
    }

    public function run(Tahap $tahap): ?TeamGenerationRun
    {
        return TeamGenerationRun::where('tahap_id', $tahap->id)->latest('id')->first();
    }

    public function mode(Tahap $tahap): string
    {
        $run = $this->run($tahap);
        if ($run && $run->status === 'draft') {
            return self::MODE_DRAFT;
        }
        if (Team::where('tahap_id', $tahap->id)->exists()) {
            return self::MODE_FINAL;
        }

        return self::MODE_NONE;
    }

    public function modeLabel(string $mode): string
    {
        return match ($mode) {
            self::MODE_DRAFT => 'Draft',
            self::MODE_FINAL => 'Final',
            default => 'Belum ada',
        };
    }

    // ------------------------------------------------------------------
    // Data kesanggupan (untuk DataTables)
    // ------------------------------------------------------------------

    /** Asesor yang sudah mengisi dan BERSEDIA (kesediaan = true). */
    public function bisaQuery(Tahap $tahap)
    {
        return Kesanggupan::query()
            ->where('kesanggupans.tahap_id', $tahap->id)
            ->where('kesediaan', true)
            ->with([
                'user:id,name,email,nia',
                'user.detail:user_id,work_city,gender,type_asesor',
            ]);
    }

    /** Asesor yang mengisi TIDAK BERSEDIA beserta alasannya. */
    public function tidakBisaQuery(Tahap $tahap)
    {
        return Kesanggupan::query()
            ->where('kesanggupans.tahap_id', $tahap->id)
            ->where('kesediaan', false)
            ->with([
                'user:id,name,email,nia',
                'user.detail:user_id,work_city,gender,type_asesor',
            ]);
    }

    /** Asesor (role asesor) yang belum mengisi apa pun pada tahap ini. */
    public function belumMengisiQuery(Tahap $tahap)
    {
        return User::query()
            ->select(['users.id', 'users.name', 'users.email', 'users.nia'])
            ->role('asesor')
            ->with(['detail:user_id,work_city,gender,type_asesor'])
            ->whereNotIn('users.id', function ($q) use ($tahap) {
                $q->select('user_id')
                    ->from('kesanggupans')
                    ->where('tahap_id', $tahap->id)
                    ->whereNull('deleted_at')
                    ->where(function ($b) {
                        $b->whereNotNull('kesanggupan')->orWhereNotNull('alasan');
                    });
            });
    }

    /**
     * Peta user_id => nilai kesanggupan untuk asesor yang bersedia.
     *
     * @return array<int, int>
     */
    public function kesanggupanMap(Tahap $tahap): array
    {
        if (! isset($this->kesCache[$tahap->id])) {
            $this->kesCache[$tahap->id] = Kesanggupan::where('tahap_id', $tahap->id)
                ->where('kesediaan', true)
                ->whereNotNull('kesanggupan')
                ->pluck('kesanggupan', 'user_id')
                ->map(fn ($v) => (int) $v)
                ->all();
        }

        return $this->kesCache[$tahap->id];
    }

    /**
     * Peta user_id => kode tim pada mode yang sedang aktif.
     *
     * @return array<int, string>
     */
    public function teamCodeMap(Tahap $tahap): array
    {
        $map = [];
        foreach ($this->pairs($tahap) as $pair) {
            foreach ($pair['members'] as $member) {
                $map[$member['id']] = $pair['code'];
            }
        }

        return $map;
    }

    // ------------------------------------------------------------------
    // Pasangan asesor
    // ------------------------------------------------------------------

    /**
     * Daftar tim/pasangan asesor pada mode aktif (hasil di-cache per request).
     *
     * @return array<int, array{team_id:int, code:string, slots:array, members_count:int, lembaga_count:int}>
     */
    public function pairs(Tahap $tahap): array
    {
        if (isset($this->pairsCache[$tahap->id])) {
            return $this->pairsCache[$tahap->id];
        }

        $mode = $this->mode($tahap);
        $out = [];

        if ($mode === self::MODE_DRAFT) {
            $run = $this->run($tahap);
            $teams = $run
                ? TeamDraft::with(['members.user.detail'])->where('run_id', $run->id)->orderBy('id')->get()
                : collect();
            foreach ($teams as $team) {
                $out[] = $this->pairItem($team, $this->membersOf($team), $tahap, 0);
            }
        } elseif ($mode === self::MODE_FINAL) {
            $counts = DB::table('team_lembaga')
                ->where('tahap_id', $tahap->id)
                ->selectRaw('team_id, COUNT(*) as total')
                ->groupBy('team_id')
                ->pluck('total', 'team_id')
                ->all();

            $teams = Team::with(['members.user.detail'])->where('tahap_id', $tahap->id)->orderBy('id')->get();
            foreach ($teams as $team) {
                $out[] = $this->pairItem($team, $this->membersOf($team), $tahap, (int) ($counts[$team->id] ?? 0));
            }
        }

        return $this->pairsCache[$tahap->id] = $out;
    }

    /**
     * Asesor bersedia (kesediaan = true) yang belum masuk tim mana pun.
     *
     * @return array<int, array>
     */
    public function unmatched(Tahap $tahap): array
    {
        $eligible = Kesanggupan::where('tahap_id', $tahap->id)
            ->where('kesediaan', true)
            ->pluck('user_id')
            ->all();

        $assigned = [];
        foreach ($this->pairs($tahap) as $pair) {
            foreach ($pair['members'] as $member) {
                $assigned[$member['id']] = true;
            }
        }

        $ids = array_values(array_diff($eligible, array_keys($assigned)));
        if (! $ids) {
            return [];
        }

        $kesMap = $this->kesanggupanMap($tahap);

        return User::with('detail')
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'nia' => (string) ($u->nia ?? ''),
                'name' => (string) $u->name,
                'email' => (string) ($u->email ?? ''),
                'kota' => (string) ($u->detail->work_city ?? ''),
                'gender' => (string) ($u->detail->gender ?? ''),
                'kesanggupan' => $kesMap[$u->id] ?? '',
            ])
            ->all();
    }

    /**
     * Seluruh asesor yang bersedia pada tahap ini beserta tim yang diikuti —
     * dipakai modal "Pilih Asesor" (termasuk untuk saling tukar antar tim).
     *
     * @return array<int, array>
     */
    public function eligibleAsesor(Tahap $tahap): array
    {
        $kesMap = $this->kesanggupanMap($tahap);
        if (! $kesMap) {
            return [];
        }

        $teamMap = $this->teamCodeMap($tahap);

        return User::with('detail')
            ->whereIn('id', array_keys($kesMap))
            ->orderBy('name')
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'nia' => (string) ($u->nia ?? ''),
                'name' => (string) $u->name,
                'kota' => (string) ($u->detail->work_city ?? ''),
                'gender' => (string) ($u->detail->gender ?? ''),
                'kesanggupan' => $kesMap[$u->id] ?? '',
                'tim' => $teamMap[$u->id] ?? '',
            ])
            ->all();
    }

    /**
     * Baris surat tugas per penugasan (tim ↔ lembaga) pada tahap ini.
     *
     * Bila run sudah menyimpan snapshot (final_pairs_payload), pakai snapshot
     * tersebut agar surat tugas tidak berubah walau pairing diedit kemudian.
     *
     * @return array<int, array>
     */
    public function suratTugasRows(Tahap $tahap): array
    {
        $run = $this->run($tahap);
        if ($run && ! empty($run->final_pairs_payload['rows'])) {
            return $run->final_pairs_payload['rows'];
        }

        return $this->buildLiveSuratTugasRows($tahap);
    }

    /**
     * Baris surat tugas untuk satu asesor (hanya tim yang memuat asesor tsb).
     *
     * @return array<int, array>
     */
    public function suratTugasFor(Tahap $tahap, int $userId): array
    {
        return array_values(array_filter(
            $this->suratTugasRows($tahap),
            fn ($row) => in_array($userId, array_map('intval', $row['member_ids'] ?? []), true)
        ));
    }

    /**
     * Susun baris surat tugas langsung dari tabel pairing (fallback).
     *
     * @return array<int, array>
     */
    protected function buildLiveSuratTugasRows(Tahap $tahap): array
    {
        $teams = Team::with(['members.user.detail'])
            ->where('tahap_id', $tahap->id)
            ->whereNotNull('finalized_at')
            ->orderBy('id')
            ->get()
            ->keyBy('id');

        $rows = DB::table('team_lembaga')
            ->where('tahap_id', $tahap->id)
            ->orderBy('id')
            ->get();

        $lembagaIds = $rows->pluck('lembaga_id')->unique()->all();
        $lembagaMap = $lembagaIds
            ? Lembaga::whereIn('id', $lembagaIds)->get(['id', 'npsn', 'satuan_pen', 'kabupaten'])->keyBy('id')
            : collect();

        $out = [];
        foreach ($rows as $r) {
            $l = $lembagaMap[$r->lembaga_id] ?? null;
            if (! $l) {
                continue;
            }

            $team = $r->team_id ? ($teams[$r->team_id] ?? null) : null;
            $memberUsers = $team
                ? $team->members->sortBy('id')->values()->map(fn ($m) => $m->user)->filter()->values()
                : collect();
            $byId = $memberUsers->keyBy('id');

            $uA = ($r->asesor_a_user_id && $byId->has($r->asesor_a_user_id))
                ? $byId->get($r->asesor_a_user_id)
                : $memberUsers->get(0);
            $uB = ($r->asesor_b_user_id && $byId->has($r->asesor_b_user_id))
                ? $byId->get($r->asesor_b_user_id)
                : $memberUsers->get(1);

            $memberIds = $memberUsers->pluck('id')->all();
            foreach ([$uA, $uB] as $u) {
                if ($u && ! in_array($u->id, $memberIds, true)) {
                    $memberIds[] = $u->id;
                }
            }

            $mainIds = [];
            foreach ([$uA, $uB] as $u) {
                if ($u) {
                    $mainIds[] = $u->id;
                }
            }
            $extra = [];
            foreach ($memberUsers as $u) {
                if (! in_array($u->id, $mainIds, true)) {
                    $extra[] = ['nia' => (string) ($u->nia ?? ''), 'nama' => (string) ($u->name ?? '')];
                }
            }

            $out[] = [
                'team_code' => $team ? (string) ($team->code ?? 'T'.$team->id) : '',
                'team_id' => $r->team_id,
                'member_ids' => array_values(array_unique(array_map('intval', $memberIds))),
                'nia_a' => $uA ? (string) ($uA->nia ?? '') : '',
                'nama_a' => $uA ? (string) $uA->name : '',
                'user_id_a' => $uA ? (int) $uA->id : null,
                'nia_b' => $uB ? (string) ($uB->nia ?? '') : '',
                'nama_b' => $uB ? (string) $uB->name : '',
                'user_id_b' => $uB ? (int) $uB->id : null,
                'extra' => $extra,
                'npsn' => (string) ($l->npsn ?? ''),
                'nama_lembaga' => (string) ($l->satuan_pen ?? ''),
                'kabupaten' => (string) ($l->kabupaten ?? ''),
            ];
        }

        return $out;
    }

    /**
     * Pilihan tim untuk modal "tambah asesor".
     *
     * @return array<int, array{id:int, code:string, info:string}>
     */
    public function teamOptions(Tahap $tahap): array
    {
        $out = [];
        foreach ($this->pairs($tahap) as $pair) {
            $names = [];
            foreach ($pair['members'] as $member) {
                $names[] = $member['name'];
            }
            $out[] = [
                'id' => $pair['team_id'],
                'code' => $pair['code'],
                'info' => $names ? implode(' + ', $names) : 'belum ada anggota',
            ];
        }

        return $out;
    }

    /**
     * Ringkasan angka untuk kartu statistik.
     *
     * @return array<string, int|string>
     */
    public function stats(Tahap $tahap): array
    {
        $bersedia = Kesanggupan::where('tahap_id', $tahap->id)->where('kesediaan', true)->count();
        $tidakBisa = Kesanggupan::where('tahap_id', $tahap->id)->where('kesediaan', false)->count();
        $totalAsesor = User::role('asesor')->count();
        $sudahMengisi = Kesanggupan::where('tahap_id', $tahap->id)
            ->where(function ($q) {
                $q->whereNotNull('kesanggupan')->orWhereNotNull('alasan');
            })
            ->distinct()
            ->count('user_id');

        $pairs = $this->pairs($tahap);
        $kesMap = $this->kesanggupanMap($tahap);
        $terpasang = 0;
        foreach ($pairs as $pair) {
            foreach ($pair['member_ids'] as $id) {
                if (isset($kesMap[$id])) {
                    $terpasang++;
                }
            }
        }

        return [
            'bersedia' => $bersedia,
            'tidak_bisa' => $tidakBisa,
            'belum_mengisi' => max(0, $totalAsesor - $sudahMengisi),
            'total_asesor' => $totalAsesor,
            'tim' => count($pairs),
            'terpasang' => $terpasang,
            'belum_terpasang' => max(0, $bersedia - $terpasang),
            'lembaga_terpasang' => (int) DB::table('team_lembaga')->where('tahap_id', $tahap->id)->count(),
            'lembaga_tahap' => (int) DB::table('lembaga_tahap')->where('tahap_id', $tahap->id)->count(),
        ];
    }

    /**
     * Data siap kirim ke view halaman detail tahap.
     *
     * @return array<string, mixed>
     */
    public function pageData(Tahap $tahap): array
    {
        $mode = $this->mode($tahap);
        $canGenerate = $tahap->end_date !== null && $tahap->end_date->lte(now());

        return [
            'tahap' => $tahap,
            'stats' => $this->stats($tahap),
            'locked' => $this->isLocked($tahap),
            'mode' => $mode,
            'modeLabel' => $this->modeLabel($mode),
            'run' => $this->run($tahap),
            'teamOptions' => $this->teamOptions($tahap),
            'hasPairs' => count($this->pairs($tahap)) > 0,
            'canGenerate' => $canGenerate,
            'endDate' => $tahap->end_date,
        ];
    }

    // ------------------------------------------------------------------
    // Generate pasangan asesor sesuai kriteria
    // ------------------------------------------------------------------

    /**
     * Bentuk tim asesor dari data yang bersedia.
     * Prioritas berjenjang: (1) gender sama + kab/kota sama, (2) gender sama,
     * (3) kab/kota sama, (4) bebas — hanya di dalam kelompok kesanggupan sama.
     *
     * @param  Collection  $users  koleksi User (dengan detail)
     * @param  array<int, int>  $kesMap  user_id => kesanggupan
     * @return array<int, array<int, int>> daftar grup berisi user_id
     */
    public function buildGroups(Collection $users, array $kesMap): array
    {
        $roster = [];
        foreach ($users as $u) {
            $roster[$u->id] = [
                'id' => $u->id,
                'kes' => max(0, (int) ($kesMap[$u->id] ?? 0)),
                'gender' => strtoupper(trim((string) ($u->detail->gender ?? ''))),
                'city' => strtolower(trim((string) ($u->detail->work_city ?? $u->detail->home_city ?? ''))),
            ];
        }

        $groups = [];
        foreach (collect($roster)->where('kes', '>', 0)->groupBy('kes') as $pool) {
            $groups = array_merge($groups, $this->pairPool($pool->values()->all()));
        }

        return $groups;
    }

    /**
     * Pasangkan asesor dalam satu kelompok kesanggupan secara greedy per tier.
     *
     * @param  array<int, array>  $pool
     * @return array<int, array<int, int>>
     */
    protected function pairPool(array $pool): array
    {
        $groups = [];
        $used = [];

        $tiers = [
            [true, true],
            [true, false],
            [false, true],
            [false, false],
        ];

        foreach ($tiers as [$sameGender, $sameCity]) {
            for ($i = 0; $i < count($pool); $i++) {
                if (isset($used[$i])) {
                    continue;
                }
                for ($j = $i + 1; $j < count($pool); $j++) {
                    if (isset($used[$j])) {
                        continue;
                    }
                    if ($sameGender && $pool[$i]['gender'] !== $pool[$j]['gender']) {
                        continue;
                    }
                    if ($sameCity && ($pool[$i]['city'] === '' || $pool[$i]['city'] !== $pool[$j]['city'])) {
                        continue;
                    }

                    $groups[] = [$pool[$i]['id'], $pool[$j]['id']];
                    $used[$i] = $used[$j] = true;
                    break;
                }
            }
        }

        return $groups;
    }

    /**
     * Jalankan generate: susun ulang pasangan asesor menjadi TIM FINAL.
     *
     * @return array{ok:bool, errors:array<int,string>, message:string, tim:int, asesor:int}
     */
    public function generate(Tahap $tahap, bool $resetPairing = false): array
    {
        $fail = fn (array $errors) => ['ok' => false, 'errors' => $errors, 'message' => '', 'tim' => 0, 'asesor' => 0];

        if ($this->isLocked($tahap)) {
            return $fail(['Data pasangan sedang dikunci. Buka kunci terlebih dahulu.']);
        }

        if (! $tahap->end_date || $tahap->end_date->gt(now())) {
            return $fail(['Generate hanya bisa dijalankan setelah masa tahap berakhir.']);
        }

        $kesRows = Kesanggupan::where('tahap_id', $tahap->id)->where('kesediaan', true)->get();
        if ($kesRows->isEmpty()) {
            return $fail(['Belum ada asesor yang menyatakan bersedia pada tahap ini.']);
        }

        $existsLembagaPairing = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->exists();
        if ($existsLembagaPairing && ! $resetPairing) {
            return $fail(['Tahap ini sudah punya pasangan tim ↔ lembaga. Generate ulang akan menghapus pemetaan lembaga tersebut — ulangi dengan konfirmasi reset.']);
        }

        $kesMap = [];
        foreach ($kesRows as $r) {
            $kesMap[$r->user_id] = (int) ($r->kesanggupan ?? 0);
        }

        $users = User::whereIn('id', array_keys($kesMap))->with('detail')->get();
        $groups = $this->buildGroups($users, $kesMap);

        if (! $groups) {
            return $fail(['Tidak ada pasangan yang bisa dibentuk dari data kesanggupan saat ini.']);
        }

        $userId = Auth::id();
        $now = now();

        DB::transaction(function () use ($tahap, $groups, $userId, $now) {
            // Bersihkan pasangan lama (cascade menghapus team_lembaga).
            $teamIds = Team::where('tahap_id', $tahap->id)->pluck('id')->all();
            if ($teamIds) {
                TeamMember::whereIn('team_id', $teamIds)->delete();
                Team::whereIn('id', $teamIds)->delete();
            }
            $runIds = TeamGenerationRun::where('tahap_id', $tahap->id)->pluck('id')->all();
            if ($runIds) {
                TeamDraftMember::whereIn('run_id', $runIds)->delete();
                TeamDraft::whereIn('run_id', $runIds)->delete();
                TeamGenerationRun::whereIn('id', $runIds)->delete();
            }

            $run = TeamGenerationRun::create([
                'tahap_id' => $tahap->id,
                'status' => 'final',
                'created_by' => $userId,
                'finalized_by' => $userId,
                'finalized_at' => $now,
            ]);

            foreach ($groups as $idx => $group) {
                $team = Team::create([
                    'tahap_id' => $tahap->id,
                    'code' => 'T'.($idx + 1),
                    'created_by' => $userId,
                    'finalized_by' => $userId,
                    'finalized_at' => $now,
                ]);

                foreach ($group as $uid) {
                    TeamMember::create([
                        'team_id' => $team->id,
                        'user_id' => $uid,
                        'assigned_by' => $userId,
                        'assigned_at' => $now,
                    ]);
                }
            }

            return $run;
        });

        $this->forget($tahap);

        $asesor = array_sum(array_map('count', $groups));

        return [
            'ok' => true,
            'errors' => [],
            'message' => count($groups).' pasangan asesor berhasil dibentuk ('.$asesor.' asesor).',
            'tim' => count($groups),
            'asesor' => $asesor,
        ];
    }

    // ------------------------------------------------------------------
    // Perubahan pasangan secara manual
    // ------------------------------------------------------------------

    /**
     * Ganti asesor pada slot A / B sebuah tim.
     *
     * @return array{ok:bool, message:string}
     */
    public function setSlot(Tahap $tahap, int $teamId, string $slot, int $userId): array
    {
        if ($this->isLocked($tahap)) {
            return ['ok' => false, 'message' => 'Data pasangan sedang dikunci.'];
        }

        $slot = strtolower($slot) === 'b' ? 'b' : 'a';
        $team = $this->findTeam($tahap, $teamId);
        if (! $team) {
            return ['ok' => false, 'message' => 'Tim tidak ditemukan pada tahap ini.'];
        }

        $user = User::find($userId);
        if (! $user) {
            return ['ok' => false, 'message' => 'Asesor tidak ditemukan.'];
        }
        if (! isset($this->kesanggupanMap($tahap)[$user->id])) {
            return ['ok' => false, 'message' => $user->name.' belum mengisi kesanggupan atau menyatakan tidak bersedia pada tahap ini.'];
        }

        $current = array_values($this->membersOf($team)->pluck('id')->all());
        $index = $slot === 'a' ? 0 : 1;

        // Asesor sudah berada di tim ini -> tukar posisi A/B.
        $existingIndex = array_search($user->id, $current, true);
        if ($existingIndex !== false) {
            if ((int) $existingIndex === $index) {
                return ['ok' => false, 'message' => 'Asesor tersebut sudah menempati slot '.strtoupper($slot).'.'];
            }
            if ($index < count($current)) {
                $tmp = $current[$index];
                $current[$index] = $current[$existingIndex];
                $current[$existingIndex] = $tmp;
                $this->persistMany($tahap, [$teamId => $current]);
                $this->forget($tahap);

                return ['ok' => true, 'message' => 'Posisi asesor A dan B ditukar.'];
            }
        }

        $displaced = $current[$index] ?? null;

        // Asesor sudah tergabung pada tim lain -> TUKAR pasangan antar tim.
        $other = $this->locateMember($tahap, $user->id, $teamId);
        $persist = [];
        if ($other) {
            $otherIds = $other['ids'];
            array_splice($otherIds, $other['index'], 1);
            if ($displaced !== null) {
                $pos = min($other['index'], count($otherIds));
                array_splice($otherIds, $pos, 0, [$displaced]);
            }
            $persist[$other['team']->id] = $otherIds;
        }

        while (count($current) <= $index) {
            $current[] = null;
        }
        $current[$index] = $user->id;
        $current = array_values(array_filter($current, fn ($v) => $v !== null));
        $persist[$teamId] = $current;

        $this->persistMany($tahap, $persist);
        $this->forget($tahap);

        if ($other) {
            return ['ok' => true, 'message' => $user->name.' dipindahkan ke tim ini'.($displaced ? ' — asesor yang tergeser dipindahkan ke tim '.$other['code'].'.' : '.')];
        }

        return ['ok' => true, 'message' => 'Slot '.strtoupper($slot).' diisi '.$user->name.'.'];
    }

    /**
     * Tambahkan asesor (yang belum punya tim) ke sebuah tim.
     *
     * @return array{ok:bool, message:string}
     */
    public function addMember(Tahap $tahap, int $teamId, int $userId): array
    {
        if ($this->isLocked($tahap)) {
            return ['ok' => false, 'message' => 'Data pasangan sedang dikunci.'];
        }

        $team = $this->findTeam($tahap, $teamId);
        if (! $team) {
            return ['ok' => false, 'message' => 'Tim tidak ditemukan pada tahap ini.'];
        }

        $user = User::find($userId);
        if (! $user) {
            return ['ok' => false, 'message' => 'Asesor tidak ditemukan.'];
        }
        if (! isset($this->kesanggupanMap($tahap)[$user->id])) {
            return ['ok' => false, 'message' => $user->name.' belum mengisi kesanggupan atau menyatakan tidak bersedia pada tahap ini.'];
        }

        $current = $this->membersOf($team)->pluck('id')->all();
        if (count($current) >= 3) {
            return ['ok' => false, 'message' => 'Tim sudah berisi 3 asesor.'];
        }
        if (in_array($user->id, $current, true)) {
            return ['ok' => false, 'message' => 'Asesor sudah tergabung pada tim ini.'];
        }

        $other = $this->locateMember($tahap, $user->id, $teamId);
        if ($other) {
            return ['ok' => false, 'message' => 'Asesor '.$user->name.' sudah tergabung pada tim '.$other['code'].'.'];
        }

        $current[] = $user->id;
        $this->persistMany($tahap, [$teamId => $current]);
        $this->forget($tahap);

        return ['ok' => true, 'message' => $user->name.' ditambahkan ke tim.'];
    }

    /**
     * Keluarkan asesor dari sebuah tim.
     *
     * @return array{ok:bool, message:string}
     */
    public function removeMember(Tahap $tahap, int $teamId, int $userId): array
    {
        if ($this->isLocked($tahap)) {
            return ['ok' => false, 'message' => 'Data pasangan sedang dikunci.'];
        }

        $team = $this->findTeam($tahap, $teamId);
        if (! $team) {
            return ['ok' => false, 'message' => 'Tim tidak ditemukan pada tahap ini.'];
        }

        $current = $this->membersOf($team)->pluck('id')->all();
        if (! in_array($userId, $current, true)) {
            return ['ok' => false, 'message' => 'Asesor tidak tergabung pada tim ini.'];
        }

        $current = array_values(array_filter($current, fn ($v) => (int) $v !== $userId));
        $this->persistMany($tahap, [$teamId => $current]);
        $this->forget($tahap);

        return ['ok' => true, 'message' => 'Asesor dikeluarkan dari tim.'];
    }

    // ------------------------------------------------------------------
    // Excel: unduh & unggah pasangan asesor
    // ------------------------------------------------------------------

    /**
     * Baris unduhan: satu baris = satu tim (pasangan asesor).
     *
     * @return array<int, array>
     */
    public function exportRows(Tahap $tahap): array
    {
        $rows = [];
        foreach ($this->pairs($tahap) as $pair) {
            $cells = [];
            foreach ([0, 1, 2] as $i) {
                $m = $pair['members'][$i] ?? null;
                $cells[] = $m['nia'] ?? '';
                $cells[] = $m['name'] ?? '';
                $cells[] = $m['city'] ?? '';
                $cells[] = $m['kesanggupan'] ?? '';
            }
            $rows[] = array_merge([$pair['code']], $cells, [$pair['lembaga_count']]);
        }

        return $rows;
    }

    /**
     * Terapkan isian file upload ke pasangan asesor.
     * Baris dikenali dari "Kode Tim"; NIA A/B kosong berarti slot dikosongkan.
     * Kode tim yang belum ada akan dibuat sebagai tim baru.
     *
     * @param  array<int, array{code:string, nia_a:string, nia_b:string, nia_c?:string|null}>  $rows
     * @return array{ok:bool, errors:array<int,string>, added:int, updated:int, removed:int, message:string}
     */
    public function applyUploadRows(Tahap $tahap, array $rows): array
    {
        if ($this->isLocked($tahap)) {
            return ['ok' => false, 'errors' => ['Data pasangan sedang dikunci. Buka kunci terlebih dahulu.'], 'added' => 0, 'updated' => 0, 'removed' => 0, 'message' => ''];
        }

        $errors = [];

        // Peta NIA -> user (semua role, agar pesan error bisa dibedakan).
        $users = User::with('detail')->get();
        $byNia = [];
        $byNiaStrict = [];
        foreach ($users as $u) {
            $nia = trim((string) ($u->nia ?? ''));
            if ($nia === '') {
                continue;
            }
            $byNia[$nia] = $u;
            $byNiaStrict[strtolower($nia)] = $u;
        }
        $kesMap = $this->kesanggupanMap($tahap);

        $existing = [];
        foreach ($this->pairs($tahap) as $pair) {
            $existing[strtolower(trim($pair['code']))] = $pair;
        }

        // Tim yang TIDAK ikut diubah -> anggotanya tetap terpakai.
        $usedBy = [];
        $touched = [];
        foreach ($rows as $r) {
            $touched[strtolower(trim($r['code']))] = true;
        }
        foreach ($this->pairs($tahap) as $pair) {
            if (isset($touched[strtolower(trim($pair['code']))])) {
                continue;
            }
            foreach ($pair['members'] as $member) {
                $usedBy[$member['id']] = $pair['code'];
            }
        }

        $planned = [];
        $line = 1;
        foreach ($rows as $r) {
            $line++;
            $code = trim((string) $r['code']);
            if ($code === '') {
                $errors[] = "Baris {$line}: Kode Tim kosong.";
                continue;
            }

            $key = strtolower($code);
            $memberIds = [];

            // Kolom NIA Asesor C tidak ada pada file lama -> anggota ke-3 dst
            // dibiarkan apa adanya agar tidak terhapus tanpa sengaja.
            $keepExtra = ! array_key_exists('nia_c', $r) || $r['nia_c'] === null;
            if ($keepExtra && isset($existing[$key])) {
                $memberIds = array_slice($existing[$key]['member_ids'], 2);
                foreach ($memberIds as $id) {
                    $usedBy[$id] = $usedBy[$id] ?? $code;
                }
            }

            $columns = $keepExtra ? ['a', 'b'] : ['a', 'b', 'c'];
            foreach ($columns as $slot) {
                $nia = trim((string) ($r['nia_'.$slot] ?? ''));
                if ($nia === '') {
                    continue;
                }

                $user = $byNia[$nia] ?? $byNiaStrict[strtolower($nia)] ?? null;
                if (! $user) {
                    $errors[] = "Baris {$line} ({$code}): NIA '{$nia}' tidak ditemukan.";
                    continue;
                }

                if (! isset($kesMap[$user->id])) {
                    $errors[] = "Baris {$line} ({$code}): {$user->name} (NIA {$nia}) belum mengisi kesanggupan atau menyatakan tidak bersedia pada tahap ini.";
                    continue;
                }

                if (isset($usedBy[$user->id])) {
                    $errors[] = "Baris {$line} ({$code}): {$user->name} sudah tergabung pada tim {$usedBy[$user->id]}.";
                    continue;
                }

                $usedBy[$user->id] = $code;
                $memberIds[] = $user->id;
            }

            $memberIds = array_values(array_unique($memberIds));
            if (count($memberIds) > 3) {
                $errors[] = "Baris {$line} ({$code}): lebih dari 3 asesor.";
                continue;
            }

            $planned[] = ['code' => $code, 'members' => $memberIds];
        }

        if ($errors) {
            return ['ok' => false, 'errors' => array_slice($errors, 0, 30), 'added' => 0, 'updated' => 0, 'removed' => 0, 'message' => ''];
        }

        if (! $planned) {
            return ['ok' => false, 'errors' => ['Tidak ada baris pasangan yang bisa diproses.'], 'added' => 0, 'updated' => 0, 'removed' => 0, 'message' => ''];
        }

        $mode = $this->mode($tahap);
        $userId = Auth::id();
        $now = now();
        $added = 0;
        $updated = 0;
        $removed = 0;

        $map = [];

        DB::transaction(function () use ($tahap, $planned, $existing, $mode, $userId, $now, &$added, &$updated, &$removed, &$map) {
            $run = null;
            if ($mode === self::MODE_DRAFT) {
                $run = $this->run($tahap);
            } elseif ($mode === self::MODE_NONE) {
                $run = TeamGenerationRun::where('tahap_id', $tahap->id)->latest('id')->first()
                    ?? TeamGenerationRun::create([
                        'tahap_id' => $tahap->id,
                        'status' => 'final',
                        'created_by' => $userId,
                        'finalized_by' => $userId,
                        'finalized_at' => $now,
                    ]);
            }

            foreach ($planned as $row) {
                $key = strtolower(trim($row['code']));
                $pair = $existing[$key] ?? null;

                if ($pair) {
                    $teamId = (int) $pair['team_id'];
                    $before = [];
                    foreach ($pair['slots'] as $slot) {
                        if ($slot) {
                            $before[] = $slot['id'];
                        }
                    }
                    $removed += count(array_diff($before, $row['members']));
                    $added += count(array_diff($row['members'], $before));
                } else {
                    if ($mode === self::MODE_DRAFT) {
                        $team = TeamDraft::create(['run_id' => $run->id, 'team_code' => $row['code']]);
                    } else {
                        $team = Team::create([
                            'tahap_id' => $tahap->id,
                            'code' => $row['code'],
                            'created_by' => $userId,
                            'finalized_by' => $userId,
                            'finalized_at' => $now,
                        ]);
                    }
                    $teamId = (int) $team->id;
                    $added += count($row['members']);
                }

                $updated++;
                $map[$teamId] = $row['members'];
            }
        });

        // Tulis serentak agar pertukaran asesor antar tim tidak menabrak batas unik.
        $this->persistMany($tahap, $map);

        $this->forget($tahap);

        return [
            'ok' => true,
            'errors' => [],
            'added' => $added,
            'updated' => $updated,
            'removed' => $removed,
            'message' => "Upload berhasil: {$updated} tim diproses ({$added} penempatan asesor baru, {$removed} asesor dikeluarkan).",
        ];
    }

    // ------------------------------------------------------------------
    // Helper internal
    // ------------------------------------------------------------------

    /** @return Collection<int, User> */
    protected function membersOf($team): Collection
    {
        return $team->members
            ->sortBy('id')
            ->values()
            ->map(fn ($m) => $m->user)
            ->filter()
            ->values();
    }

    /**
     * @param  Collection<int, User>  $users
     */
    protected function pairItem($team, Collection $users, Tahap $tahap, int $lembagaCount): array
    {
        $kesMap = $this->kesanggupanMap($tahap);
        $slots = [];
        $all = [];
        foreach ($users as $i => $u) {
            $item = [
                'id' => $u->id,
                'nia' => (string) ($u->nia ?? ''),
                'name' => (string) ($u->name ?? ''),
                'city' => (string) ($u->detail->work_city ?? ''),
                'kesanggupan' => $kesMap[$u->id] ?? '',
            ];
            $all[] = $item;
            if ($i < 2) {
                $slots[] = $item;
            }
        }
        while (count($slots) < 2) {
            $slots[] = null;
        }

        return [
            'team_id' => $team->id,
            'code' => (string) ($team->code ?? 'T'.$team->id),
            'slots' => $slots,
            'members' => $all,
            'member_ids' => array_map(fn ($m) => $m['id'], $all),
            'members_count' => $users->count(),
            'lembaga_count' => $lembagaCount,
        ];
    }

    /** Cari model tim pada mode aktif (atau tabel mana pun bila belum ada). */
    protected function findTeam(Tahap $tahap, int $teamId)
    {
        $mode = $this->mode($tahap);
        if ($mode === self::MODE_DRAFT) {
            $run = $this->run($tahap);

            return $run ? TeamDraft::with(['members.user.detail'])->where('run_id', $run->id)->find($teamId) : null;
        }

        return Team::with(['members.user.detail'])->where('tahap_id', $tahap->id)->find($teamId);
    }

    /**
     * Cari tim + posisi seorang asesor pada tahap ini.
     *
     * @return array{team:mixed, code:string, index:int, ids:array<int,int>}|null
     */
    protected function locateMember(Tahap $tahap, int $userId, ?int $exceptTeamId = null): ?array
    {
        foreach ($this->pairs($tahap) as $pair) {
            if ($exceptTeamId !== null && (int) $pair['team_id'] === $exceptTeamId) {
                continue;
            }
            foreach ($pair['members'] as $i => $member) {
                if ((int) $member['id'] === $userId) {
                    $team = $this->findTeam($tahap, (int) $pair['team_id']);
                    if (! $team) {
                        return null;
                    }

                    return [
                        'team' => $team,
                        'code' => $pair['code'],
                        'index' => (int) $i,
                        'ids' => array_values($this->membersOf($team)->pluck('id')->all()),
                    ];
                }
            }
        }

        return null;
    }

    /** Simpan ulang anggota sebuah tim (urutan menentukan slot A/B). */
    protected function persistMembers(Tahap $tahap, $team, array $userIds): void
    {
        $this->persistMany($tahap, [$team->id => $userIds]);
    }

    /**
     * Tulis ulang anggota beberapa tim sekaligus dalam satu transaksi.
     * Semua anggota tim yang terlibat dihapus lebih dulu agar batas unik
     * (run_id, user_id) tetap terpenuhi saat asesor bertukar tim.
     *
     * @param  array<int, array<int, int>>  $map  team_id => daftar user_id (urut)
     */
    protected function persistMany(Tahap $tahap, array $map): void
    {
        $map = array_filter($map, fn ($ids, $teamId) => $teamId !== null, ARRAY_FILTER_USE_BOTH);
        if (! $map) {
            return;
        }

        $mode = $this->mode($tahap);
        $run = $mode === self::MODE_DRAFT ? $this->run($tahap) : null;
        $userId = Auth::id();
        $now = now();

        DB::transaction(function () use ($tahap, $mode, $run, $map, $userId, $now) {
            $teamIds = array_map('intval', array_keys($map));

            if ($mode === self::MODE_DRAFT) {
                TeamDraftMember::whereIn('team_draft_id', $teamIds)->delete();
            } else {
                TeamMember::whereIn('team_id', $teamIds)->delete();
            }

            foreach ($map as $teamId => $userIds) {
                $userIds = array_values(array_unique(array_map('intval', (array) $userIds)));

                if ($mode === self::MODE_DRAFT) {
                    foreach ($userIds as $uid) {
                        TeamDraftMember::create([
                            'run_id' => $run?->id,
                            'team_draft_id' => (int) $teamId,
                            'user_id' => $uid,
                            'is_manual' => true,
                            'assigned_by' => $userId,
                            'assigned_at' => $now,
                        ]);
                    }
                } else {
                    foreach ($userIds as $uid) {
                        TeamMember::create([
                            'team_id' => (int) $teamId,
                            'user_id' => $uid,
                            'assigned_by' => $userId,
                            'assigned_at' => $now,
                        ]);
                    }
                }

                $this->clearStaleOverrides($tahap, (int) $teamId, $userIds, $now);
            }
        });
    }

    /**
     * Bersihkan override asesor per-baris (hasil pairing lembaga) yang asesornya
     * sudah tidak lagi menjadi anggota tim ini.
     *
     * @param  array<int, int>  $userIds
     */
    protected function clearStaleOverrides(Tahap $tahap, int $teamId, array $userIds, $now): void
    {
        $rows = DB::table('team_lembaga')
            ->where('tahap_id', $tahap->id)
            ->where('team_id', $teamId)
            ->get(['id', 'asesor_a_user_id', 'asesor_b_user_id']);

        foreach ($rows as $row) {
            $patch = [];
            if ($row->asesor_a_user_id && ! in_array((int) $row->asesor_a_user_id, $userIds, true)) {
                $patch['asesor_a_user_id'] = null;
            }
            if ($row->asesor_b_user_id && ! in_array((int) $row->asesor_b_user_id, $userIds, true)) {
                $patch['asesor_b_user_id'] = null;
            }
            if ($patch) {
                $patch['updated_at'] = $now;
                DB::table('team_lembaga')->where('id', $row->id)->update($patch);
            }
        }
    }

    private function forget(Tahap $tahap): void
    {
        unset($this->pairsCache[$tahap->id], $this->kesCache[$tahap->id]);
    }
}
