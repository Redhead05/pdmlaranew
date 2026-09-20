<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ValidasiLembagaExport;
use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Validasi;
use App\Models\ValidasiKesanggupan;
use App\Models\ValidasiLembaga;
use App\Models\User;
use App\Notifications\ValidasiDibuatNotification;
use App\Notifications\ValidasiSuratTugasNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class ValidasiController extends Controller
{
    public function index()
    {
        $validasis = Validasi::query()
            ->withCount([
                'kesanggupans as bisa_count' => fn ($q) => $q->where('kesediaan', true),
                'kesanggupans as tidak_count' => fn ($q) => $q->where('kesediaan', false),
            ])
            ->latest('id')
            ->get();

        $totalAsesor = User::role('asesor')->count();

        return view('menu.admin.validasi.index', compact('validasis', 'totalAsesor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'surat_keputusan' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ]);

        $validasi = Validasi::create($data);

        // Notifikasi ke seluruh asesor.
        $recipients = User::role('asesor')->get();
        foreach ($recipients as $user) {
            $user->notify(new ValidasiDibuatNotification($validasi));
        }

        return redirect()->route('admin.validasi.index')->with('success', 'Validasi "'.$validasi->nama.'" dibuat dan notifikasi dikirim ke '.$recipients->count().' asesor.');
    }

    public function show(Validasi $validasi)
    {
        $totalAsesor = User::role('asesor')->count();
        $bisa = $validasi->kesanggupans()->where('kesediaan', true)->count();
        $tidak = $validasi->kesanggupans()->where('kesediaan', false)->count();
        $sudahIsi = $validasi->kesanggupans()->whereNotNull('kesediaan')->distinct()->count('user_id');
        $belum = max(0, $totalAsesor - $sudahIsi);

        return view('menu.admin.validasi.show', [
            'validasi' => $validasi,
            'stats' => [
                'bisa' => $bisa,
                'tidak' => $tidak,
                'belum' => $belum,
                'total_asesor' => $totalAsesor,
            ],
            'locked' => $validasi->pairing_locked_at !== null,
            'suratTugasSent' => $validasi->surat_tugas_notification_sent_at !== null,
        ]);
    }

    public function destroy(Validasi $validasi)
    {
        $id = (string) $validasi->id;

        DB::transaction(function () use ($validasi, $id) {
            // Hapus notifikasi terkait validasi ini (dibuat + surat tugas).
            DB::table('notifications')
                ->whereIn('type', [ValidasiDibuatNotification::class, ValidasiSuratTugasNotification::class])
                ->where('data', 'like', '%"validasi_id":"'.$id.'"%')
                ->delete();

            $validasi->delete(); // cascade validasi_kesanggupans via FK
        });

        return redirect()->route('admin.validasi.index')->with('success', 'Validasi dan notifikasinya dihapus.');
    }

    // ------------------------------------------------------------------
    // DataTables hasil validasi
    // ------------------------------------------------------------------

    public function bisaData(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $items = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with(['user.detail'])
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'kota' => (string) ($k->user->detail->work_city ?? '-'),
                'gender' => (string) ($k->user->detail->gender ?? '-'),
                'ttd' => $k->ttd,
                'user_id' => $k->user_id,
            ]);

        return DataTables::of($items)->make(true);
    }

    public function tidakBisaData(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $items = $validasi->kesanggupans()
            ->where('kesediaan', false)
            ->with(['user.detail'])
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'kota' => (string) ($k->user->detail->work_city ?? '-'),
                'gender' => (string) ($k->user->detail->gender ?? '-'),
                'alasan' => (string) ($k->alasan ?? '-'),
                'bukti' => $k->bukti,
                'user_id' => $k->user_id,
            ]);

        return DataTables::of($items)->make(true);
    }

    public function belumMengisiData(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $filledIds = $validasi->kesanggupans()->whereNotNull('kesediaan')->pluck('user_id');

        $items = User::query()
            ->role('asesor')
            ->with('detail')
            ->when($filledIds->isNotEmpty(), fn ($q) => $q->whereNotIn('users.id', $filledIds))
            ->orderBy('users.name')
            ->get()
            ->map(fn (User $u) => [
                'nia' => (string) ($u->nia ?? ''),
                'name' => (string) $u->name,
                'kota' => (string) ($u->detail->work_city ?? '-'),
                'gender' => (string) ($u->detail->gender ?? '-'),
                'user_id' => $u->id,
            ]);

        return DataTables::of($items)->make(true);
    }

    // ------------------------------------------------------------------
    // Edit: jadikan bisa
    // ------------------------------------------------------------------

    public function setBisa(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at !== null) {
            $msg = 'Validasi sudah dikunci.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $row = $validasi->kesanggupans()->firstOrNew(['user_id' => $data['user_id']]);
        $row->kesediaan = true;
        $row->alasan = null;
        $row->ttd = null;
        $row->bukti = null;
        $row->save();

        $msg = 'Asesor ditandai sebagai "Bisa".';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    /**
     * Pindahkan asesor ke "Tidak Bisa" beserta alasan + bukti (base64).
     */
    public function setTidak(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at !== null) {
            $msg = 'Validasi sudah dikunci.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'alasan' => ['required', 'string', 'min:5'],
            'bukti' => ['required', 'string', 'min:20'],
        ]);

        $row = $validasi->kesanggupans()->firstOrNew(['user_id' => $data['user_id']]);
        $row->kesediaan = false;
        $row->alasan = $data['alasan'];
        $row->bukti = $data['bukti'];
        $row->ttd = null;
        $row->save();

        $msg = 'Asesor dipindahkan ke "Tidak Bisa" beserta bukti.';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    /**
     * Pindahkan asesor kembali ke "Belum Mengisi" (hapus jawabannya).
     */
    public function setBelum(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at !== null) {
            $msg = 'Validasi sudah dikunci.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $validasi->kesanggupans()->where('user_id', $data['user_id'])->forceDelete();

        $msg = 'Asesor dipindahkan ke "Belum Mengisi".';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }
    // ------------------------------------------------------------------
    // Bulk edit (multi-baris)
    // ------------------------------------------------------------------

    public function bulkSetBisa(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at !== null) {
            $msg = 'Validasi sudah dikunci.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        foreach (array_unique($data['user_ids']) as $uid) {
            $row = $validasi->kesanggupans()->firstOrNew(['user_id' => $uid]);
            $row->kesediaan = true;
            $row->alasan = null;
            $row->ttd = null;
            $row->bukti = null;
            $row->save();
        }

        $msg = count($data['user_ids']).' asesor ditandai "Bisa".';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    public function bulkSetBelum(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at !== null) {
            $msg = 'Validasi sudah dikunci.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $validasi->kesanggupans()->whereIn('user_id', $data['user_ids'])->forceDelete();

        $msg = count($data['user_ids']).' asesor dipindahkan ke "Belum Mengisi".';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    // ------------------------------------------------------------------
    // Pairing lembaga (visitasi terkunci) ↔ asesor "bisa"
    // ------------------------------------------------------------------

    public function lembagaIndex(Validasi $validasi)
    {
        return view('menu.admin.validasi.lembaga', compact('validasi'));
    }

    public function lembagaData(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        // Opsi asesor "Bisa" untuk dropdown NIA di datatable.
        $asesors = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with('user.detail')
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'id' => $k->user_id,
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'home_city' => (string) ($k->user->detail->home_city ?? ''),
            ])
            ->sortBy('nia')
            ->values();

        $rows = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->join('tahaps', 'tahaps.id', '=', 'lembaga_tahap.tahap_id')
            ->leftJoin('validasi_lembaga', function ($j) use ($validasi) {
                $j->on('validasi_lembaga.lembaga_id', '=', 'lembagas.id')
                    ->where('validasi_lembaga.validasi_id', '=', $validasi->id);
            })
            ->leftJoin('users', 'users.id', '=', 'validasi_lembaga.user_id')
            ->leftJoin('user_details', function ($j) {
                $j->on('user_details.user_id', '=', 'validasi_lembaga.user_id')
                    ->whereNull('user_details.deleted_at');
            })
            ->whereNotNull('tahaps.pairing_locked_at')
            ->whereNull('tahaps.deleted_at')
            ->select(
                'lembagas.id',
                'lembagas.npsn',
                'lembagas.satuan_pen',
                'lembagas.kabupaten',
                'users.name as asesor_name',
                'users.nia as asesor_nia',
                'user_details.home_city as asesor_home_city',
                'validasi_lembaga.user_id as asesor_user_id'
            )
            ->get();

        $items = $rows->map(function ($r) use ($asesors) {
            $current = (int) ($r->asesor_user_id ?? 0);
            $select = '<select class="form-select form-select-sm nia-select" data-lembaga="'.$r->id.'" style="min-width:210px">'
                .'<option value=""'.($current === 0 ? ' selected' : '').'>— belum dipasangkan —</option>';
            foreach ($asesors as $a) {
                $sel = ($current === (int) $a['id']) ? ' selected' : '';
                $select .= '<option value="'.$a['id'].'"'.$sel.'>'.e($a['nia']).' — '.e($a['name']).'</option>';
            }
            $select .= '</select>';

            return [
                'lembaga_id' => $r->id,
                'npsn' => (string) ($r->npsn ?? '-'),
                'name' => (string) ($r->satuan_pen ?? '-'),
                'kabupaten' => (string) ($r->kabupaten ?? '-'),
                'nia_asesor' => $select,
                'nama_asesor' => (string) ($r->asesor_name ?? '—'),
                'home_city' => (string) ($r->asesor_home_city ?? '—'),
                'asesor_nia' => (string) ($r->asesor_nia ?? ''),
                'asesor_user_id' => $r->asesor_user_id,
            ];
        });

        return DataTables::of($items)->rawColumns(['nia_asesor'])->make(true);
    }

    public function lembagaAsesorOptions(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $lembaga = $request->filled('lembaga_id') ? Lembaga::find($request->integer('lembaga_id')) : null;
        $kab = $lembaga ? strtolower(trim((string) $lembaga->kabupaten)) : '';

        $data = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with(['user.detail'])
            ->get()
            ->filter(function (ValidasiKesanggupan $k) use ($kab) {
                $home = strtolower(trim((string) ($k->user->detail->home_city ?? '')));
                $work = strtolower(trim((string) ($k->user->detail->work_city ?? '')));
                if ($kab === '') {
                    return true;
                }

                return $home !== $kab && $work !== $kab;
            })
            ->map(fn (ValidasiKesanggupan $k) => [
                'id' => $k->user_id,
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'kota' => (string) ($k->user->detail->work_city ?? '-'),
            ])
            ->values();

        return response()->json(['data' => $data]);
    }

    public function lembagaAssign(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        $data = $request->validate([
            'lembaga_ids' => ['required', 'array', 'min:1'],
            'lembaga_ids.*' => ['integer', 'exists:lembagas,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $asesor = $validasi->kesanggupans()->where('user_id', $data['user_id'])->where('kesediaan', true)->first();
        if (! $asesor) {
            $msg = 'Asesor tidak termasuk yang menyatakan "Bisa".';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $detail = $asesor->user->detail;
        $home = strtolower(trim((string) ($detail->home_city ?? '')));
        $work = strtolower(trim((string) ($detail->work_city ?? '')));

        $lembagas = Lembaga::whereIn('id', $data['lembaga_ids'])->get()->keyBy('id');
        $violations = [];
        foreach ($data['lembaga_ids'] as $lid) {
            $l = $lembagas[$lid] ?? null;
            if (! $l) {
                continue;
            }
            $kab = strtolower(trim((string) $l->kabupaten));
            if ($kab !== '' && ($home === $kab || $work === $kab)) {
                $violations[] = $l->satuan_pen.' (kab/kota sama: '.$l->kabupaten.')';
            }
        }

        if ($violations) {
            $msg = 'Tidak bisa dipasangkan — kabupaten/kota sama: '.implode(', ', $violations);

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        foreach ($data['lembaga_ids'] as $lid) {
            ValidasiLembaga::updateOrCreate(
                ['validasi_id' => $validasi->id, 'lembaga_id' => $lid],
                ['user_id' => $data['user_id']]
            );
        }

        $msg = count($data['lembaga_ids']).' lembaga dipasangkan ke asesor.';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    public function lembagaUnassign(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        $data = $request->validate([
            'lembaga_ids' => ['required', 'array', 'min:1'],
            'lembaga_ids.*' => ['integer'],
        ]);

        ValidasiLembaga::where('validasi_id', $validasi->id)->whereIn('lembaga_id', $data['lembaga_ids'])->delete();

        $msg = count($data['lembaga_ids']).' pasangan lembaga dilepas.';

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }
    /**
     * Pairing otomatis: pasangkan lembaga (visitasi terkunci) yang belum
     * terpasang ke asesor "Bisa" dengan aturan kabupaten/kota tidak boleh sama.
     * Distribusi round-robin agar merata.
     */
    public function lembagaAutoPair(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at === null) {
            $msg = 'Kunci validasi terlebih dahulu sebelum pairing otomatis.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $asesors = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with('user.detail')
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'id' => $k->user_id,
                'home' => strtolower(trim((string) ($k->user->detail->home_city ?? ''))),
                'work' => strtolower(trim((string) ($k->user->detail->work_city ?? ''))),
            ])
            ->values();

        if ($asesors->isEmpty()) {
            $msg = 'Tidak ada asesor yang menyatakan "Bisa" pada validasi ini.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $assignedIds = ValidasiLembaga::where('validasi_id', $validasi->id)->pluck('lembaga_id');

        $lembagas = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->join('tahaps', 'tahaps.id', '=', 'lembaga_tahap.tahap_id')
            ->whereNotNull('tahaps.pairing_locked_at')
            ->whereNull('tahaps.deleted_at')
            ->when($assignedIds->isNotEmpty(), fn ($q) => $q->whereNotIn('lembagas.id', $assignedIds))
            ->select('lembagas.id', 'lembagas.kabupaten')
            ->orderBy('lembagas.kabupaten')
            ->get();

        if ($lembagas->isEmpty()) {
            $msg = 'Tidak ada lembaga yang belum terpasang.';

            return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('info', $msg);
        }

        $count = $asesors->count();
        $idx = 0;
        $paired = 0;
        $skipped = 0;

        foreach ($lembagas as $l) {
            $kab = strtolower(trim((string) $l->kabupaten));
            $found = null;
            for ($i = 0; $i < $count; $i++) {
                $a = $asesors[($idx + $i) % $count];
                if ($kab === '' || ($a['home'] !== $kab && $a['work'] !== $kab)) {
                    $found = $a;
                    $idx = ($idx + $i + 1) % $count;
                    break;
                }
            }

            if ($found) {
                ValidasiLembaga::updateOrCreate(
                    ['validasi_id' => $validasi->id, 'lembaga_id' => $l->id],
                    ['user_id' => $found['id']]
                );
                $paired++;
            } else {
                $skipped++;
            }
        }

        $msg = "Pairing otomatis selesai: {$paired} lembaga terpasang, {$skipped} dilewati (tidak ada asesor beda kota).";

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    /**
     * Pairing dengan aturan jumlah lembaga per asesor (2..10).
     * Setiap asesor "Bisa" dipasangkan maksimal N lembaga (kab/kota tidak sama),
     * sisanya dibiarkan kosong. Distribusi round-robin agar merata.
     */
    public function lembagaPairRule(Request $request, Validasi $validasi)
    {
        $isJson = $request->ajax() || $request->wantsJson();

        if ($validasi->pairing_locked_at === null) {
            $msg = 'Kunci validasi terlebih dahulu sebelum pairing.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $data = $request->validate([
            'per_asesor' => ['required', 'integer', 'min:2', 'max:10'],
        ]);
        $per = (int) $data['per_asesor'];

        $asesors = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with('user.detail')
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'id' => $k->user_id,
                'home' => strtolower(trim((string) ($k->user->detail->home_city ?? ''))),
                'work' => strtolower(trim((string) ($k->user->detail->work_city ?? ''))),
            ])
            ->values();

        if ($asesors->isEmpty()) {
            $msg = 'Tidak ada asesor yang menyatakan "Bisa" pada validasi ini.';

            return $isJson ? response()->json(['ok' => false, 'message' => $msg], 422) : back()->with('error', $msg);
        }

        $assignedIds = ValidasiLembaga::where('validasi_id', $validasi->id)->pluck('lembaga_id');

        $lembagas = DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->join('tahaps', 'tahaps.id', '=', 'lembaga_tahap.tahap_id')
            ->whereNotNull('tahaps.pairing_locked_at')
            ->whereNull('tahaps.deleted_at')
            ->when($assignedIds->isNotEmpty(), fn ($q) => $q->whereNotIn('lembagas.id', $assignedIds))
            ->select('lembagas.id', 'lembagas.kabupaten')
            ->orderBy('lembagas.kabupaten')
            ->get();

        $n = $asesors->count();
        $idx = 0;
        $counts = [];
        $paired = 0;
        $skipped = 0;

        foreach ($lembagas as $l) {
            $kab = strtolower(trim((string) $l->kabupaten));
            $candidate = null;

            for ($i = 0; $i < $n; $i++) {
                $a = $asesors[($idx + $i) % $n];
                if (($counts[$a['id']] ?? 0) >= $per) {
                    continue;
                }
                if ($kab !== '' && ($a['home'] === $kab || $a['work'] === $kab)) {
                    continue;
                }
                $candidate = $a;
                $idx = ($idx + $i + 1) % $n;
                break;
            }

            if ($candidate) {
                ValidasiLembaga::updateOrCreate(
                    ['validasi_id' => $validasi->id, 'lembaga_id' => $l->id],
                    ['user_id' => $candidate['id']]
                );
                $counts[$candidate['id']] = ($counts[$candidate['id']] ?? 0) + 1;
                $paired++;
            } else {
                $skipped++;
            }
        }

        $msg = "Pairing selesai: {$paired} lembaga terpasang ({$per} per asesor), {$skipped} dibiarkan kosong.";

        return $isJson ? response()->json(['ok' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    // ------------------------------------------------------------------
    // Copy / Download Excel / Import manual pairing lembaga
    // ------------------------------------------------------------------

    protected function lembagaRowsData(Validasi $validasi)
    {
        return DB::table('lembagas')
            ->join('lembaga_tahap', 'lembaga_tahap.lembaga_id', '=', 'lembagas.id')
            ->join('tahaps', 'tahaps.id', '=', 'lembaga_tahap.tahap_id')
            ->leftJoin('validasi_lembaga', function ($j) use ($validasi) {
                $j->on('validasi_lembaga.lembaga_id', '=', 'lembagas.id')
                    ->where('validasi_lembaga.validasi_id', '=', $validasi->id);
            })
            ->leftJoin('users', 'users.id', '=', 'validasi_lembaga.user_id')
            ->whereNotNull('tahaps.pairing_locked_at')
            ->whereNull('tahaps.deleted_at')
            ->select(
                'lembagas.id',
                'lembagas.npsn',
                'lembagas.satuan_pen',
                'lembagas.kabupaten',
                'users.name as asesor_name',
                'users.nia as asesor_nia',
                'validasi_lembaga.user_id as asesor_user_id'
            )
            ->get();
    }

    public function lembagaExport(Validasi $validasi)
    {
        $rows = [];
        foreach ($this->lembagaRowsData($validasi) as $r) {
            $rows[] = [
                (string) ($r->npsn ?? ''),
                (string) ($r->satuan_pen ?? ''),
                (string) ($r->kabupaten ?? ''),
                (string) ($r->asesor_nia ?? ''),
                (string) ($r->asesor_name ?? ''),
            ];
        }

        $filename = 'pairing_lembaga_validasi_'.$validasi->slug.'_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new ValidasiLembagaExport($rows), $filename);
    }

    public function lembagaCopy(Request $request, Validasi $validasi)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $headers = ['NPSN', 'Nama Lembaga', 'Kabupaten', 'NIA Asesor', 'Nama Asesor'];
        $rows = [];
        foreach ($this->lembagaRowsData($validasi) as $r) {
            $rows[] = [$r->npsn, $r->satuan_pen, $r->kabupaten, $r->asesor_nia ?? '', $r->asesor_name ?? ''];
        }

        return response()->json(['headers' => $headers, 'rows' => $rows]);
    }

    public function lembagaImport(Request $request, Validasi $validasi)
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt,xlsx']]);

        $errors = [];
        $raw = $this->readLembagaImport($request->file('file'), $errors);
        if ($raw === null) {
            return back()->with('error', implode('<br>', $errors));
        }
        if (! $raw) {
            return back()->with('error', 'File tidak berisi baris data.');
        }

        $byNpsn = [];
        foreach ($this->lembagaRowsData($validasi) as $r) {
            $byNpsn[(string) $r->npsn] = $r;
        }

        $asesors = $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with('user.detail')
            ->get()
            ->keyBy(fn ($k) => (string) $k->user->nia);

        $errors = [];
        $plan = [];
        foreach ($raw as $i => $row) {
            $line = $i + 2;
            $npsn = trim((string) $row['npsn']);
            $nia = trim((string) $row['nia']);
            // Baris tanpa NIA (lembaga belum diisi) = tidak ada perubahan, lewati.
            if ($npsn === '' || $nia === '') {
                continue;
            }
            $l = $byNpsn[$npsn] ?? null;
            if (! $l) {
                $errors[] = "Baris {$line}: NPSN '{$npsn}' tidak ditemukan pada lembaga visitasi terkunci.";
                continue;
            }
            $a = $asesors[$nia] ?? null;
            if (! $a) {
                $errors[] = "Baris {$line}: NIA '{$nia}' tidak ditemukan / bukan asesor 'Bisa'.";
                continue;
            }
            $home = strtolower(trim((string) ($a->user->detail->home_city ?? '')));
            $work = strtolower(trim((string) ($a->user->detail->work_city ?? '')));
            $kab = strtolower(trim((string) $l->kabupaten));
            if ($kab !== '' && ($home === $kab || $work === $kab)) {
                $errors[] = "Baris {$line}: asesor NIA {$nia} kabupaten/kota sama dengan lembaga.";
                continue;
            }
            $plan[] = ['lembaga_id' => $l->id, 'user_id' => $a->user_id];
        }

        if ($errors) {
            return back()->with('error', 'Import ditolak:<br>'.implode('<br>', array_slice($errors, 0, 30)));
        }

        foreach ($plan as $p) {
            ValidasiLembaga::updateOrCreate(
                ['validasi_id' => $validasi->id, 'lembaga_id' => $p['lembaga_id']],
                ['user_id' => $p['user_id']]
            );
        }

        return back()->with('success', count($plan).' pasangan lembaga ↔ asesor berhasil diimpor.');
    }

    protected function readLembagaImport($file, array &$errors): ?array
    {
        $ext = strtolower((string) $file->getClientOriginalExtension());
        $raw = [];

        if (in_array($ext, ['csv', 'txt'], true)) {
            $handle = fopen($file->getRealPath(), 'rb');
            if ($handle === false) {
                $errors[] = 'Gagal membuka file CSV.';

                return null;
            }
            while (($row = fgetcsv($handle)) !== false) {
                $raw[] = $row;
            }
            fclose($handle);
        } elseif ($ext === 'xlsx') {
            try {
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $names = $reader->listWorksheetNames($file->getRealPath());
                if (in_array('Pairing Lembaga', $names, true)) {
                    $reader->setLoadSheetsOnly(['Pairing Lembaga']);
                }
                $ss = $reader->load($file->getRealPath());
                $sheet = $ss->getSheetByName('Pairing Lembaga') ?? $ss->getActiveSheet();
                foreach ($sheet->toArray(null, true, true, false) as $row) {
                    $raw[] = $row;
                }
                $ss->disconnectWorksheets();
                unset($ss);
            } catch (\Throwable $e) {
                $errors[] = 'Gagal membaca file Excel: '.$e->getMessage();

                return null;
            }
        } else {
            $errors[] = 'Format file tidak didukung.';

            return null;
        }

        $raw = array_values(array_filter($raw, fn ($r) => count(array_filter((array) $r, fn ($c) => trim((string) $c) !== '')) > 0));
        if (! $raw) {
            return [];
        }

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $raw[0]);
        $find = function (array $names) use ($header) {
            foreach ($names as $n) {
                $i = array_search($n, $header, true);
                if ($i !== false) {
                    return $i;
                }
            }

            return null;
        };

        $colNpsn = $find(['npsn']);
        $colNia = $find(['nia asesor', 'nia']);
        if ($colNpsn === null || $colNia === null) {
            $errors[] = 'Kolom tidak dikenali. Butuh kolom "NPSN" dan "NIA Asesor".';

            return null;
        }

        $out = [];
        foreach (array_slice($raw, 1) as $row) {
            $out[] = [
                'npsn' => $this->normCell($row[$colNpsn] ?? ''),
                'nia' => $this->normCell($row[$colNia] ?? ''),
            ];
        }

        return $out;
    }

    protected function normCell($value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_float($value) && floor($value) === $value) {
            // sprintf %.0f agar NPSN/NIA panjang tidak jadi notasi ilmiah.
            return sprintf('%.0f', $value);
        }
        if (is_numeric($value)) {
            $s = trim((string) $value);
            // Hilangkan ".0" di akhir bila hasil konversi float sederhana.
            if (str_ends_with($s, '.0')) {
                return substr($s, 0, -2);
            }
        }

        return trim((string) $value);
    }


    // ------------------------------------------------------------------
    // Kunci / buka kunci
    // ------------------------------------------------------------------

    public function lock(Validasi $validasi)
    {
        $validasi->update(['pairing_locked_at' => now(), 'pairing_locked_by' => Auth::id()]);

        return back()->with('success', 'Validasi dikunci.');
    }

    public function unlock(Validasi $validasi)
    {
        $validasi->update(['pairing_locked_at' => null, 'pairing_locked_by' => null]);

        return back()->with('success', 'Validasi dibuka kembali.');
    }

    // ------------------------------------------------------------------
    // Surat tugas validasi
    // ------------------------------------------------------------------

    public function suratTugasRows(Validasi $validasi): array
    {
        if ($validasi->surat_tugas_payload) {
            return $validasi->surat_tugas_payload['rows'] ?? [];
        }

        return $validasi->kesanggupans()
            ->where('kesediaan', true)
            ->with(['user.detail'])
            ->get()
            ->map(fn (ValidasiKesanggupan $k) => [
                'user_id' => $k->user_id,
                'nia' => (string) ($k->user->nia ?? ''),
                'nama' => (string) ($k->user->name ?? '-'),
                'kota' => (string) ($k->user->detail->work_city ?? '-'),
                'gender' => (string) ($k->user->detail->gender ?? '-'),
                'ttd' => $k->ttd,
            ])
            ->all();
    }

    public function sendSuratTugas(Request $request, Validasi $validasi)
    {
        if ($validasi->pairing_locked_at === null) {
            return back()->with('error', 'Kunci validasi terlebih dahulu sebelum mengirim surat tugas.');
        }

        $data = $request->validate([
            'nomor_st' => ['required', 'string', 'max:255'],
        ]);

        $rows = $this->suratTugasRows($validasi);
        if (! $rows) {
            return back()->with('error', 'Belum ada asesor yang menyatakan "Bisa" untuk dibuatkan surat tugas.');
        }

        $nomor = trim($data['nomor_st']);
        $slug = Str::slug($nomor).'-'.now()->format('YmdHis');

        $payload = [
            'nomor_st' => $nomor,
            'generated_at' => now()->toIso8601String(),
            'rows' => $rows,
        ];

        $history = $validasi->surat_tugas_history ?? [];
        $history[] = [
            'slug' => $slug,
            'nomor_st' => $nomor,
            'payload' => $payload,
            'generated_at' => now()->toIso8601String(),
            'generated_by' => Auth::id(),
        ];

        $validasi->update([
            'surat_tugas_payload' => $payload,
            'surat_tugas_number' => $nomor,
            'surat_tugas_slug' => $slug,
            'surat_tugas_generated_by' => Auth::id(),
            'surat_tugas_generated_at' => now(),
            'surat_tugas_history' => $history,
        ]);

        $userIds = array_column($rows, 'user_id');
        $recipients = User::role('asesor')->whereIn('id', $userIds)->get();
        $sent = 0;
        foreach ($recipients as $user) {
            $user->notify(new ValidasiSuratTugasNotification($validasi, $nomor));
            $sent++;
        }

        $validasi->update(['surat_tugas_notification_sent_at' => now()]);

        return back()->with('success', "Surat tugas {$nomor} dikirim ke {$sent} asesor.");
    }

    public function suratTugas(Validasi $validasi)
    {
        $rows = $this->suratTugasRows($validasi);
        $nomorSt = $validasi->surat_tugas_number
            ?: 'ST/'.str_pad((string) $validasi->id, 3, '0', STR_PAD_LEFT).'/'.now()->format('Y');

        $signerId = $validasi->surat_tugas_generated_by ?? $validasi->pairing_locked_by;
        $adminName = $signerId ? (User::find($signerId)?->name ?? 'Administrator') : 'Administrator';

        return view('menu.admin.validasi.surat_tugas', [
            'validasi' => $validasi,
            'rows' => $rows,
            'nomorSt' => $nomorSt,
            'isPersonal' => false,
            'recipientName' => null,
            'adminName' => $adminName,
        ]);
    }
}
