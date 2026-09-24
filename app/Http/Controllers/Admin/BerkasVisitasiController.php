<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasVisitasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BerkasVisitasiController extends Controller
{
    public function index()
    {
        return view('menu.admin.berkas.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $items = BerkasVisitasi::query()
            ->with(['lembaga', 'asesor', 'tahap'])
            ->when($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'], true), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('id')
            ->get()
            ->map(fn (BerkasVisitasi $b) => [
                'id' => $b->id,
                'npsn' => (string) ($b->lembaga->npsn ?? '-'),
                'lembaga' => (string) ($b->lembaga->satuan_pen ?? '-'),
                'kabupaten' => (string) ($b->lembaga->kabupaten ?? '-'),
                'nia' => (string) ($b->asesor->nia ?? '-'),
                'asesor' => (string) ($b->asesor->name ?? '-'),
                'sk' => (string) ($b->tahap->surat_keputusan ?? '-'),
                'tanggal_visitasi' => $b->tanggal_visitasi?->format('d M Y') ?? '-',
                'status' => $b->status,
                'status_badge' => $this->statusBadge($b->status),
                'action' => '<button type="button" class="btn btn-sm btn-outline-primary btn-check-berkas" data-id="'.$b->id.'" title="Check berkas">'
                    .'<span class="material-symbols-outlined align-middle" style="font-size:16px">fact_check</span></button>',
            ]);

        return DataTables::of($items)->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function show(BerkasVisitasi $berkas)
    {
        return response()->json([
            'id' => $berkas->id,
            'npsn' => (string) ($berkas->lembaga->npsn ?? '-'),
            'lembaga' => (string) ($berkas->lembaga->satuan_pen ?? '-'),
            'kabupaten' => (string) ($berkas->lembaga->kabupaten ?? '-'),
            'lembaga_lat' => $berkas->lembaga->latitude,
            'lembaga_lng' => $berkas->lembaga->longitude,
            'nia' => (string) ($berkas->asesor->nia ?? '-'),
            'asesor' => (string) ($berkas->asesor->name ?? '-'),
            'sk' => (string) ($berkas->tahap->surat_keputusan ?? '-'),
            'tahap' => (string) ($berkas->tahap->tahap ?? '-'),
            'tanggal_visitasi' => $berkas->tanggal_visitasi?->format('d M Y') ?? '-',
            'jenis_perjalanan' => $berkas->jenis_perjalanan === 'menginap' ? 'Menginap' : 'Pulang/Pergi',
            'latitude' => $berkas->latitude,
            'longitude' => $berkas->longitude,
            'jarak_km' => $berkas->jarak_km,
            'status' => $berkas->status,
            'admin_komentar' => $berkas->admin_komentar,
            'bukti_transport' => $berkas->bukti_transport ? Storage::url($berkas->bukti_transport) : null,
            'bukti_menginap' => $berkas->bukti_menginap ? Storage::url($berkas->bukti_menginap) : null,
            'foto_depan' => $berkas->foto_depan ? Storage::url($berkas->foto_depan) : null,
            'scan_sppd' => $berkas->scan_sppd ? Storage::url($berkas->scan_sppd) : null,
            'surat_perjalanan_dinas' => $berkas->surat_perjalanan_dinas ? Storage::url($berkas->surat_perjalanan_dinas) : null,
            'pakta_integritas' => $berkas->pakta_integritas ? Storage::url($berkas->pakta_integritas) : null,
            'berita_acara' => $berkas->berita_acara ? Storage::url($berkas->berita_acara) : null,
            'daftar_hadir' => $berkas->daftar_hadir ? Storage::url($berkas->daftar_hadir) : null,
            'nominal_transport' => $berkas->nominal_transport,
            'nominal_menginap' => $berkas->nominal_menginap,
        ]);
    }

    public function approve(BerkasVisitasi $berkas)
    {
        $berkas->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_komentar' => null,
        ]);

        // Simpan koordinat dari foto ke master lembaga agar admin tahu titik yang benar.
        if ($berkas->latitude !== null && $berkas->longitude !== null && $berkas->lembaga) {
            $berkas->lembaga->update([
                'latitude' => $berkas->latitude,
                'longitude' => $berkas->longitude,
            ]);
        }

        return back()->with('success', 'Berkas visitasi disetujui dan koordinat lembaga diperbarui.');
    }

    public function reject(Request $request, BerkasVisitasi $berkas)
    {
        $data = $request->validate([
            'admin_komentar' => ['required', 'string', 'min:5'],
        ]);

        $berkas->update([
            'status' => 'rejected',
            'admin_komentar' => $data['admin_komentar'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Berkas ditolak dan komentar dikirim ke asesor.');
    }

    protected function statusBadge(string $status): string
    {
        return match ($status) {
            'approved' => '<span class="badge bg-success">Diterima</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-warning text-dark">Pending</span>',
        };
    }
}
