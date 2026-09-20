<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Tahap;
use App\Models\TeamGenerationRun;
use App\Models\User;
use App\Services\TahapPairingService;
use Illuminate\Http\Request;

class SuratTugasController extends Controller
{
    public function __construct(protected TahapPairingService $pairing)
    {
    }

    /**
     * Surat tugas pribadi asesor (hanya baris tim yang memuat asesor login).
     * URL memakai slug surat tugas (nomor ST + timestamp), bukan id run.
     */
    public function show(Request $request, Tahap $tahap, string $st)
    {
        $run = TeamGenerationRun::where('tahap_id', $tahap->id)
            ->where('surat_tugas_slug', $st)
            ->firstOrFail();

        $snapshot = $run->final_pairs_payload['rows'] ?? null;
        $rows = $snapshot !== null
            ? array_values(array_filter($snapshot, fn ($row) => in_array((int) $request->user()->id, array_map('intval', $row['member_ids'] ?? []), true)))
            : $this->pairing->suratTugasFor($tahap, (int) $request->user()->id);
        $nomorSt = $run->surat_tugas_number
            ?: 'ST/'.str_pad((string) $run->id, 3, '0', STR_PAD_LEFT).'/'.$tahap->id.'/'.now()->format('Y');

        $signerId = $run->surat_tugas_generated_by ?? $run->finalized_by ?? $run->created_by;
        $adminName = $signerId ? (User::find($signerId)?->name ?? 'Administrator') : 'Administrator';

        return view('menu.admin.tahap.generation.surat_tugas', [
            'tahap' => $tahap,
            'run' => $run,
            'rows' => $rows,
            'nomorSt' => $nomorSt,
            'isPersonal' => true,
            'recipientName' => (string) $request->user()->name,
            'adminName' => $adminName,
        ]);
    }
}
