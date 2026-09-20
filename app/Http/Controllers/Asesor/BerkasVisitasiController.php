<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\BerkasVisitasi;
use App\Models\Lembaga;
use App\Models\Tahap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BerkasVisitasiController extends Controller
{
    public function index(Tahap $tahap)
    {
        $user = auth()->user();

        $teamIds = DB::table('team_members')->where('user_id', $user->id)->pluck('team_id');
        $lembagaIds = DB::table('team_lembaga')->where('tahap_id', $tahap->id)->whereIn('team_id', $teamIds)->pluck('lembaga_id');

        $lembagas = Lembaga::whereIn('id', $lembagaIds)->orderBy('npsn')->get();

        $existing = BerkasVisitasi::where('tahap_id', $tahap->id)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('lembaga_id');

        return view('menu.asesor.visitasi.berkas', compact('tahap', 'lembagas', 'existing'));
    }

    public function checkFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:2048'],
            'lembaga_id' => ['required', 'integer', 'exists:lembagas,id'],
        ]);

        $lembaga = Lembaga::find($request->integer('lembaga_id'));
        $gps = $this->extractGps($request->file('foto'));

        return response()->json([
            'ok' => (bool) $gps,
            'latitude' => $gps[0] ?? null,
            'longitude' => $gps[1] ?? null,
            'lembaga_lat' => $lembaga->latitude,
            'lembaga_lng' => $lembaga->longitude,
            'lembaga_has_coords' => $lembaga->latitude !== null && $lembaga->longitude !== null,
            'message' => $gps
                ? 'Metadata GPS ditemukan.'
                : 'Metadata GPS (latitude/longitude) tidak ditemukan pada foto.',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tahap_id' => ['required', 'integer', 'exists:tahaps,id'],
            'lembaga_id' => ['required', 'integer', 'exists:lembagas,id'],
            'tanggal_visitasi' => ['required', 'date'],
            'jenis_perjalanan' => ['required', 'in:pulang_pergi,menginap'],
            'bukti_transport' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bukti_menginap' => ['nullable', 'required_if:jenis_perjalanan,menginap', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_depan' => ['required', 'image', 'max:2048'],
            'scan_sppd' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'surat_perjalanan_dinas' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'pakta_integritas' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'berita_acara' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'daftar_hadir' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'nominal_transport' => ['nullable', 'string'],
            'nominal_menginap' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $user = auth()->user();

        // Lembaga harus milik asesor pada tahap ini.
        $teamIds = DB::table('team_members')->where('user_id', $user->id)->pluck('team_id');
        $allowed = DB::table('team_lembaga')->where('tahap_id', $data['tahap_id'])->whereIn('team_id', $teamIds)->pluck('lembaga_id');
        if (! $allowed->contains($data['lembaga_id'])) {
            return response()->json(['ok' => false, 'message' => 'Lembaga ini bukan penugasan Anda.'], 422);
        }

        $lembaga = Lembaga::find($data['lembaga_id']);
        if ($lembaga->latitude === null || $lembaga->longitude === null) {
            return response()->json(['ok' => false, 'message' => 'Lembaga ini belum memiliki koordinat (latitude/longitude). Hubungi admin.'], 422);
        }

        $lat = $data['latitude'] ?? null;
        $lng = $data['longitude'] ?? null;

        if ($lat === null || $lng === null) {
            $gps = $this->extractGps($request->file('foto_depan'));
            if ($gps) {
                $lat = $gps[0];
                $lng = $gps[1];
            }
        }

        if ($lat === null || $lng === null) {
            return response()->json(['ok' => false, 'message' => 'Metadata GPS tidak ditemukan pada foto depan. Gunakan foto dengan lokasi aktif.'], 422);
        }

        $nominalTransport = $data['nominal_transport'] ? (int) preg_replace('/\D/', '', $data['nominal_transport']) : null;
        $nominalMenginap = $data['nominal_menginap'] ? (int) preg_replace('/\D/', '', $data['nominal_menginap']) : null;

        $existing = BerkasVisitasi::where('tahap_id', $data['tahap_id'])
            ->where('lembaga_id', $data['lembaga_id'])
            ->where('user_id', $user->id)
            ->first();

        $fileFields = [
            'bukti_transport' => $request->file('bukti_transport'),
            'bukti_menginap' => $request->file('bukti_menginap'),
            'foto_depan' => $request->file('foto_depan'),
            'scan_sppd' => $request->file('scan_sppd'),
            'surat_perjalanan_dinas' => $request->file('surat_perjalanan_dinas'),
            'pakta_integritas' => $request->file('pakta_integritas'),
            'berita_acara' => $request->file('berita_acara'),
            'daftar_hadir' => $request->file('daftar_hadir'),
        ];

        $paths = [];
        foreach ($fileFields as $field => $file) {
            $paths[$field] = $file ? $this->storeFile($file) : ($existing->{$field} ?? null);
        }

        $row = BerkasVisitasi::updateOrCreate(
            ['tahap_id' => $data['tahap_id'], 'lembaga_id' => $data['lembaga_id'], 'user_id' => $user->id],
            array_merge($paths, [
                'tanggal_visitasi' => $data['tanggal_visitasi'],
                'jenis_perjalanan' => $data['jenis_perjalanan'],
                'nominal_transport' => $nominalTransport,
                'nominal_menginap' => $nominalMenginap,
                'latitude' => $lat,
                'longitude' => $lng,
                'status' => 'pending',
                'admin_komentar' => null,
            ])
        );

        return response()->json(['ok' => true, 'message' => 'Berkas terkirim. Menunggu verifikasi admin.']);
    }

    public function show(BerkasVisitasi $berkas)
    {
        abort_unless($berkas->user_id === auth()->id(), 403);

        return response()->json([
            'status' => $berkas->status,
            'tanggal_visitasi' => $berkas->tanggal_visitasi?->format('d M Y') ?? '-',
            'jenis_perjalanan' => $berkas->jenis_perjalanan === 'menginap' ? 'Menginap' : 'Pulang/Pergi',
            'nominal_transport' => $berkas->nominal_transport,
            'nominal_menginap' => $berkas->nominal_menginap,
            'admin_komentar' => $berkas->admin_komentar,
            'show_photo' => $berkas->status !== 'rejected',
            'files' => [
                ['label' => 'Scan SPPD', 'url' => $berkas->scan_sppd ? Storage::url($berkas->scan_sppd) : null],
                ['label' => 'Bukti Menginap Hotel', 'url' => $berkas->bukti_menginap ? Storage::url($berkas->bukti_menginap) : null],
                ['label' => 'Bukti Transport', 'url' => $berkas->bukti_transport ? Storage::url($berkas->bukti_transport) : null],
                ['label' => 'Surat Perjalanan Dinas', 'url' => $berkas->surat_perjalanan_dinas ? Storage::url($berkas->surat_perjalanan_dinas) : null],
                ['label' => 'Pakta Integritas Visitasi', 'url' => $berkas->pakta_integritas ? Storage::url($berkas->pakta_integritas) : null],
                ['label' => 'Berita Acara Visitasi', 'url' => $berkas->berita_acara ? Storage::url($berkas->berita_acara) : null],
                ['label' => 'Daftar Hadir Pembukaan & Penutupan', 'url' => $berkas->daftar_hadir ? Storage::url($berkas->daftar_hadir) : null],
                ['label' => 'Foto Depan Lembaga', 'url' => ($berkas->status !== 'rejected' && $berkas->foto_depan) ? Storage::url($berkas->foto_depan) : null],
            ],
            'gps' => $berkas->latitude !== null && $berkas->longitude !== null ? [$berkas->latitude, $berkas->longitude] : null,
        ]);
    }

    protected function storeFile($file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf'], true)) {
            $ext = 'bin';
        }
        $name = date('Ymd_His').'_'.uniqid().'.'.$ext;

        return $file->storeAs('berkas_visitasi', $name, 'public');
    }

    protected function extractGps($file)
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        $exif = @exif_read_data($file->getRealPath());
        if (! $exif || empty($exif['GPSLatitude']) || empty($exif['GPSLongitude'])) {
            return null;
        }

        $lat = $this->gpsToDecimal($exif['GPSLatitude'], $exif['GPSLatitudeRef'] ?? 'N');
        $lng = $this->gpsToDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef'] ?? 'E');

        return $lat === null || $lng === null ? null : [$lat, $lng];
    }

    protected function gpsToDecimal($coord, $hemi)
    {
        $d = (float) $coord[0];
        $m = (float) $coord[1];
        $s = (float) $coord[2];
        $dec = $d + ($m / 60) + ($s / 3600);

        if (strtoupper($hemi) === 'S' || strtoupper($hemi) === 'W') {
            $dec = -$dec;
        }

        return $dec;
    }
}
