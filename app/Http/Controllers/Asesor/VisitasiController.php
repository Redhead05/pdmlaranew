<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\TeamGenerationRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VisitasiController extends Controller
{
    /**
     * Daftar penugasan (surat tugas) asesor yang sedang login.
     */
    public function index()
    {
        return view('menu.asesor.visitasi.index');
    }

    /**
     * DataTables server-side: satu baris PER TAHAP (bukan per NPSN).
     * Detail per NPSN ditampilkan di halaman surat tugas (Lihat).
     */
    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $user = $request->user();

        $teamIds = DB::table('team_members')->where('user_id', $user->id)->pluck('team_id');
        if ($teamIds->isEmpty()) {
            return DataTables::of(collect([]))->make(true);
        }

        // Tahap tempat asesor tertugaskan (punya baris team_lembaga).
        $tahapIds = DB::table('team_lembaga')
            ->whereIn('team_id', $teamIds)
            ->pluck('tahap_id')
            ->unique()
            ->all();

        // Hanya tahap yang surat tugasnya sudah dikirim (punya slug + nomor).
        $runs = TeamGenerationRun::whereIn('tahap_id', $tahapIds)
            ->whereNotNull('surat_tugas_number')
            ->whereNotNull('surat_tugas_slug')
            ->get()
            ->keyBy('tahap_id');

        if ($runs->isEmpty()) {
            return DataTables::of(collect([]))->make(true);
        }

        $tahaps = DB::table('tahaps')
            ->whereIn('id', $runs->keys()->all())
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('id');

        // Kode tim asesor pada tiap tahap (biasanya 1 tim per tahap).
        $teamCodes = DB::table('team_members')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('team_members.user_id', $user->id)
            ->whereIn('teams.tahap_id', $runs->keys()->all())
            ->orderBy('teams.id')
            ->pluck('teams.code', 'teams.tahap_id');

        $items = [];
        foreach ($runs as $tahapId => $run) {
            $tahap = $tahaps[$tahapId] ?? null;
            if (! $tahap) {
                continue;
            }

            $showUrl = route('asesor.surat-tugas.show', ['tahap' => $tahap->slug, 'st' => $run->surat_tugas_slug]);
            $berkasUrl = route('asesor.visitasi.berkas.index', $tahap->slug);
            $items[] = [
                'tahap' => (string) $tahap->tahap,
                'surat_keputusan' => (string) ($tahap->surat_keputusan ?? '-'),
                'nomor_st' => (string) ($run->surat_tugas_number ?? '-'),
                'team_code' => (string) ($teamCodes[$tahapId] ?? '-'),
                'action' => '<div class="d-flex gap-1 flex-wrap">'
                    .'<a href="'.e($showUrl).'" class="btn btn-sm btn-outline-primary">'
                    .'<span class="material-symbols-outlined align-middle" style="font-size:16px">visibility</span> Lihat</a>'
                    .'<a href="'.e($berkasUrl).'" class="btn btn-sm btn-outline-warning">'
                    .'<span class="material-symbols-outlined align-middle" style="font-size:16px">upload_file</span> Upload Berkas</a>'
                    .'<a href="'.e($showUrl.'?print=1').'" target="_blank" class="btn btn-sm btn-outline-success">'
                    .'<span class="material-symbols-outlined align-middle" style="font-size:16px">download</span> Download</a>'
                    .'</div>',
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }
}
