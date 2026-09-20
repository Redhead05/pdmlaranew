<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Validasi;
use App\Models\ValidasiKesanggupan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ValidasiController extends Controller
{
    /**
     * Daftar validasi + form Ya/Tidak + TTD, dan tab surat tugas.
     */
    public function index()
    {
        $user = auth()->user();

        $validasis = Validasi::query()
            ->with(['kesanggupans' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest('id')
            ->get();

        // Jawaban per validasi untuk user ini.
        $jawaban = [];
        foreach ($validasis as $v) {
            $jawaban[$v->id] = $v->kesanggupans->first();
        }

        return view('menu.asesor.validasi.index', compact('validasis', 'jawaban'));
    }

    /**
     * Simpan / perbarui jawaban validasi (Ya/Tidak + TTD).
     */
    public function simpan(Request $request, Validasi $validasi)
    {
        if ($validasi->pairing_locked_at !== null) {
            return back()->with('error', 'Validasi sudah dikunci, jawaban tidak dapat diubah.');
        }

        $data = $request->validate([
            'kesediaan' => ['required', 'boolean'],
            'alasan' => ['nullable', 'string'],
            'ttd' => ['required', 'string', 'min:10'],
            'bukti' => ['nullable', 'string'],
        ]);

        if (! (bool) $data['kesediaan']) {
            $data['alasan'] = $request->validate([
                'alasan' => ['required', 'string', 'min:5'],
            ])['alasan'];
            // Bukti (gambar base64) wajib diunggah bila memilih "Tidak".
            $data['bukti'] = $request->validate([
                'bukti' => ['required', 'string', 'min:20'],
            ])['bukti'];
        } else {
            $data['alasan'] = null;
            $data['bukti'] = null;
        }

        $row = $validasi->kesanggupans()->firstOrNew(['user_id' => auth()->id()]);
        $row->kesediaan = (bool) $data['kesediaan'];
        $row->alasan = $data['alasan'] ?? null;
        $row->ttd = $data['ttd'];
        $row->bukti = $data['bukti'] ?? null;
        $row->save();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Jawaban validasi tersimpan.']);
        }

        return back()->with('success', 'Jawaban validasi tersimpan.');
    }

    /**
     * Surat tugas validasi pribadi (baris asesor login saja).
     */
    public function suratTugas(Request $request, Validasi $validasi, string $st)
    {
        $payload = null;
        $nomorSt = null;

        if ($validasi->surat_tugas_slug === $st) {
            $payload = $validasi->surat_tugas_payload;
            $nomorSt = $validasi->surat_tugas_number;
        } else {
            $revision = collect($validasi->surat_tugas_history ?? [])->firstWhere('slug', $st);
            abort_if(! $revision, 404);
            $payload = $revision['payload'] ?? null;
            $nomorSt = $revision['nomor_st'] ?? null;
        }

        $rows = $payload['rows'] ?? [];
        $rows = array_values(array_filter($rows, fn ($r) => (int) ($r['user_id'] ?? 0) === (int) auth()->id()));

        $nomorSt = $nomorSt
            ?: 'ST/'.str_pad((string) $validasi->id, 3, '0', STR_PAD_LEFT).'/'.now()->format('Y');

        return view('menu.admin.validasi.surat_tugas', [
            'validasi' => $validasi,
            'rows' => $rows,
            'nomorSt' => $nomorSt,
            'isPersonal' => true,
            'recipientName' => (string) auth()->user()->name,
            'adminName' => null, // admin preview only
        ]);
    }

    /**
     * DataTables surat tugas validasi milik asesor login (per validasi).
     */
    public function penugasanData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $userId = (int) auth()->id();

        $rows = ValidasiKesanggupan::query()
            ->where('user_id', $userId)
            ->where('kesediaan', true)
            ->with('validasi')
            ->get()
            ->filter(fn ($k) => $k->validasi && $k->validasi->surat_tugas_slug)
            ->map(function (ValidasiKesanggupan $k) {
                $v = $k->validasi;
                $showUrl = route('asesor.validasi.surat-tugas', ['validasi' => $v->slug, 'st' => $v->surat_tugas_slug]);

                return [
                    'validasi' => (string) $v->nama,
                    'surat_keputusan' => (string) ($v->surat_keputusan ?? '-'),
                    'nomor_st' => (string) ($v->surat_tugas_number ?? '-'),
                    'action' => '<div class="d-flex gap-1 flex-wrap">'
                        .'<a href="'.e($showUrl).'" class="btn btn-sm btn-outline-primary">'
                        .'<span class="material-symbols-outlined align-middle" style="font-size:16px">visibility</span> Lihat</a>'
                        .'<a href="'.e($showUrl.'?print=1').'" target="_blank" class="btn btn-sm btn-outline-success">'
                        .'<span class="material-symbols-outlined align-middle" style="font-size:16px">download</span> Download</a>'
                        .'</div>',
                ];
            })
            ->values();

        return DataTables::of($rows)->make(true);
    }
}
