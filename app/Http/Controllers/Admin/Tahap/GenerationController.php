<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Exports\GenerationExport;
use App\Http\Controllers\Controller;
use App\Models\Tahap;
use App\Models\Team;
use App\Models\TeamGenerationRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerationController extends Controller
{
    /**
     * List all generation runs for a tahap.
     */
    public function index(Tahap $tahap)
    {
        $runs = TeamGenerationRun::where('tahap_id', $tahap->id)
            ->with(['generatedBy'])
            ->latest()
            ->get();

        return view('menu.admin.tahap.generation.index', compact('tahap', 'runs'));
    }

    /**
     * Run pairing generation for a given tahap.
     */
    public function generate(Request $request, Tahap $tahap)
    {
        $lembagas = $tahap->lembagas()->get();
        $teams = Team::where('tahap_id', $tahap->id)
            ->whereNotNull('finalized_at')
            ->with(['members.user.detail'])
            ->get();

        if ($lembagas->isEmpty() || $teams->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak cukup data: pastikan lembaga dan team asesor sudah tersedia dan final.');
        }

        $run = TeamGenerationRun::create([
            'tahap_id'   => $tahap->id,
            'status'     => 'running',
            'created_by' => auth()->id(),
        ]);

        $centroid = function ($team) {
            $coords = collect($team->members)->map(function ($m) {
                $d = $m->user->detail ?? null;
                return $d && $d->latitude && $d->longitude
                    ? [floatval($d->latitude), floatval($d->longitude)]
                    : null;
            })->filter();
            if ($coords->isEmpty()) return null;
            return [$coords->avg(0), $coords->avg(1)];
        };

        $haversine = function ($lat1, $lon1, $lat2, $lon2) {
            $R = 6371;
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
            return $R * 2 * atan2(sqrt($a), sqrt(1-$a));
        };

        $availableLembaga = $lembagas->keyBy('id')->all();
        $assignedLembaga  = [];
        $pairs = [];

        foreach ($teams as $team) {
            $center = $centroid($team);
            if (!$center) continue;
            [$tlat, $tlng] = $center;
            $best = null;
            $bestDist = PHP_FLOAT_MAX;

            foreach ($availableLembaga as $lid => $l) {
                if (in_array($lid, $assignedLembaga)) continue;
                if (empty($l->latitude) || empty($l->longitude)) continue;
                $d = $haversine($tlat, $tlng, floatval($l->latitude), floatval($l->longitude));
                if ($d < $bestDist) { $bestDist = $d; $best = $l; }
            }

            if ($best) {
                $assignedLembaga[] = $best->id;
                // build member detail rows so export can produce one row per member
                $members = $team->members->map(function($m) {
                    $user = $m->user;
                    $d = $user?->detail;
                    return [
                        'user_id' => $user?->id,
                        'nia' => $user?->nia,
                        'name' => $user?->name,
                        'work_city' => $d?->work_city ?? null,
                        'home_city' => $d?->home_city ?? null,
                    ];
                })->toArray();

                $pairs[] = [
                    'team_id'      => $team->id,
                    'team_code'    => $team->code,
                    'members'      => $members,
                    'team_lat'     => $tlat,
                    'team_lng'     => $tlng,
                    'lembaga_id'   => $best->id,
                    'npsn'         => $best->npsn,
                    'lembaga_name' => $best->satuan_pen,
                    'lembaga_lat'  => $best->latitude,
                    'lembaga_lng'  => $best->longitude,
                    'distance_km'  => round($bestDist, 3),
                ];
            }
        }

        $pairedTeamIds    = collect($pairs)->pluck('team_id')->all();
        $pairedLembagaIds = collect($pairs)->pluck('lembaga_id')->all();

        $unpairedTeams = $teams->filter(fn($t) => !in_array($t->id, $pairedTeamIds))->map(function ($t) use ($centroid) {
            $c = $centroid($t);
            return ['team_id' => $t->id, 'team_code' => $t->code, 'members' => $t->members->map(fn($m) => $m->user->name ?? '')->implode(', '), 'lat' => $c ? $c[0] : null, 'lng' => $c ? $c[1] : null];
        })->values()->all();

        $unpairedLembagas = $lembagas->filter(fn($l) => !in_array($l->id, $pairedLembagaIds))->map(fn($l) => [
            'id' => $l->id, 'npsn' => $l->npsn, 'name' => $l->satuan_pen, 'lat' => $l->latitude, 'lng' => $l->longitude,
        ])->values()->all();

        // Save single Excel (2 sheets)
        $dir  = "team_generations/{$run->id}";
        Excel::store(new GenerationExport($pairs, $unpairedTeams, $unpairedLembagas), "$dir/result.xlsx", 'local');

        $run->update(['status' => 'done', 'finalized_by' => auth()->id(), 'finalized_at' => now()]);

        return redirect()
            ->route('admin.tahap.generation.index', ['tahap' => $tahap->slug])
            ->with('success', 'Generate selesai. ' . count($pairs) . ' pasangan berhasil dibuat.');
    }

    /**
     * Download combined Excel for a run.
     */
    public function downloadExcel(Tahap $tahap, TeamGenerationRun $run)
    {
        // prefer edited version if exists
        $editedPath = "team_generations/{$run->id}/result_edited.xlsx";
        $origPath   = "team_generations/{$run->id}/result.xlsx";
        $path = Storage::exists($editedPath) ? $editedPath : $origPath;

        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', 'File Excel tidak ditemukan.');
        }

        return Storage::download($path, "generate_{$tahap->slug}_run{$run->id}.xlsx");
    }

    /**
     * Upload edited Excel back.
     */
    public function uploadExcel(Request $request, Tahap $tahap, TeamGenerationRun $run)
    {
        $request->validate(['excel' => 'required|file|mimes:xlsx,xls']);

        $dir  = "team_generations/{$run->id}";
        Storage::put("$dir/result_edited.xlsx", file_get_contents($request->file('excel')->getRealPath()));

        return redirect()
            ->route('admin.tahap.generation.index', ['tahap' => $tahap->slug])
            ->with('success', 'File Excel berhasil di-upload. Hasil edit tersimpan.');
    }

    /**
     * Delete a run and its files.
     */
    public function destroy(Tahap $tahap, TeamGenerationRun $run)
    {
        $dir = "team_generations/{$run->id}";
        if (Storage::exists($dir)) Storage::deleteDirectory($dir);
        $run->delete();

        return redirect()
            ->route('admin.tahap.generation.index', ['tahap' => $tahap->slug])
            ->with('success', 'Riwayat generate berhasil dihapus.');
    }
}

