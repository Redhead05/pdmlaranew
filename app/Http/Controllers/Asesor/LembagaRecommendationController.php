<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Kesanggupan;
use App\Services\AutoMatchService;
use Illuminate\Http\Request;

class LembagaRecommendationController extends Controller
{
    /**
     * Tampilkan rekomendasi lembaga terdekat untuk asesor yang sedang login,
     * dibatasi oleh jumlah kesanggupan pada tahap yang dipilih.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Tahap tempat asesor menyatakan sanggup (kesediaan = ya).
        $kesanggupans = Kesanggupan::with('tahap.lembagas')
            ->where('user_id', $user->id)
            ->where('kesediaan', true)
            ->get();

        $selected = $request->filled('tahap_id')
            ? $kesanggupans->firstWhere('tahap_id', $request->integer('tahap_id'))
            : $kesanggupans->first();

        $recommendations = collect();
        $kuota = $selected ? (int) $selected->kesanggupan : 0;

        $detail = $user->detail;
        if ($selected && $detail && $detail->latitude && $detail->longitude) {
            $recommendations = $selected->tahap->lembagas
                ->filter(fn ($l) => $l->latitude && $l->longitude)
                ->map(fn ($l) => [
                    'lembaga' => $l,
                    'distance_km' => round(
                        AutoMatchService::haversine(
                            (float) $detail->latitude,
                            (float) $detail->longitude,
                            (float) $l->latitude,
                            (float) $l->longitude
                        ),
                        2
                    ),
                ])
                ->sortBy('distance_km')
                ->take($kuota)
                ->values();
        }

        return view('menu.asesor.rekomendasi.index', compact('kesanggupans', 'selected', 'recommendations', 'kuota'));
    }
}
