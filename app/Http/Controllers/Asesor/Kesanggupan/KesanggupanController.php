<?php

namespace App\Http\Controllers\Asesor\Kesanggupan;

use App\Http\Controllers\Controller;
use App\Models\Kesanggupan;
use App\Models\Tahap;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KesanggupanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()?->loadMissing('detail');

        $authUser = [
            'nia' => $user?->nia,
            'name' => $user?->name,
            'work_city' => $user?->detail?->work_city,
        ];

        $userId = (int) auth()->id();

        // Ambil semua tahap yang ada sekarang.
        // Jika ada tahap baru, buat 1 row kesanggupan untuk user asesor ini.
        $tahaps = Tahap::query()->select(['id'])->get();

        foreach ($tahaps as $tahap) {
            Kesanggupan::query()->firstOrCreate(
                ['tahap_id' => $tahap->id, 'user_id' => $userId],
                // jangan set kesediaan null karena DB kolomnya NOT NULL
                // default=false sudah cukup menandakan belum memilih/atau bisa dianggap "Tidak" sampai user mengubah.
                ['kesanggupan' => null, 'alasan' => null]
            );
        }

        // Tampilkan semua kesanggupan user ini (1 per tahap). Urut pakai tanggal tahap.
        $kesanggupans = Kesanggupan::query()
            ->where('user_id', $userId)
            ->with(['tahap'])
            ->join('tahaps', 'tahaps.id', '=', 'kesanggupans.tahap_id')
            ->orderBy('tahaps.start_date', 'desc')
            ->orderBy('kesanggupans.id', 'desc')
            ->select('kesanggupans.*')
            ->get();

        return view('menu.asesor.kesanggupan.index', compact('kesanggupans', 'authUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kesanggupan $kesanggupan)
    {
        abort_unless($kesanggupan->user_id === auth()->id(), 403);

        $endDate = $kesanggupan->tahap?->end_date;
        if ($endDate && now()->greaterThan($endDate)) {
            abort(403, 'Form kesanggupan sudah ditutup.');
        }

        $data = $request->validate([
            'kesediaan' => ['required', 'boolean'],
            'kesanggupan' => ['nullable'],
            'alasan' => ['nullable', 'string'],
        ]);

        $allowed = collect($kesanggupan->tahap?->allowed_kesanggupan ?? [])
            ->map(fn ($v) => (int) $v)
            ->filter(fn ($v) => $v > 0)
            ->unique()
            ->values()
            ->all();

        if ((bool) $data['kesediaan'] === false) {
            $data['alasan'] = $request->validate([
                'alasan' => ['required', 'string', 'min:5'],
            ])['alasan'];

            $data['kesanggupan'] = null;
        } else {
            $data['kesanggupan'] = $request->validate([
                'kesanggupan' => ['required', 'integer', 'in:' . implode(',', array_map('intval', $allowed))],
            ])['kesanggupan'];

            $data['alasan'] = null;
        }

        $kesanggupan->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $kesanggupan->fresh(['tahap', 'user']),
            ]);
        }

        return back()->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
