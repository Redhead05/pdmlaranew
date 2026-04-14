<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Http\Controllers\Controller;
use App\Models\Kesanggupan;
use App\Models\Tahap;
use App\Models\User;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahaps = Tahap::query()
            ->select(['id', 'slug','tahap', 'surat_keputusan', 'allowed_kesanggupan', 'created_at', 'start_date', 'end_date'])
            ->with([
                'kesanggupans' => function ($q) {
                    $q->whereNotNull('kesediaan')
                        ->with('user:id,name,email'); // adjust columns if needed
                },
            ])
            ->latest('id')
            ->get();

        return view('menu.admin.tahap.index', compact('tahaps'));
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
        $data = $request->validate([
            'tahap' => ['required', 'string', 'max:255'],
            'surat_keputusan' => ['required', 'string', 'max:255'],
            // one field, comma-separated: "2,3,4,10"
            'allowed_kesanggupan_csv' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $allowed = collect(preg_split('/\s*,\s*/', trim($data['allowed_kesanggupan_csv']), -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($v) => (int) $v)
            ->filter(fn ($v) => $v > 0) // keep positive ints only; change if you want to allow 0/negatives
            ->unique()
            ->values()
            ->all();

        if (count($allowed) === 0) {
            return back()
                ->withErrors(['allowed_kesanggupan_csv' => 'Kesanggupan Harus di isi minimal 1.'])
                ->withInput();
        }

        return DB::transaction(function () use ($data, $allowed) {
            do {
                $slug = (string) random_int(1000000, 9999999);
            }
            while(
                Tahap::where('slug', $slug)->exists()
            );

            $tahap = Tahap::create([
                'tahap' => $data['tahap'],
                'surat_keputusan' => $data['surat_keputusan'],
                'allowed_kesanggupan' => $allowed,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'slug' => $slug,
            ]);

            return redirect()->route('admin.tahap.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Tahap $tahap)
    {
        // Rows that are considered "filled" (any meaningful input exists)
        $filled = Kesanggupan::query()
            ->where('tahap_id', $tahap->id)
            ->where(function ($q) {
                $q->whereNotNull('kesanggupan')
                    ->orWhereNotNull('alasan');
            })
            ->with([
                'user:id,name,email',
                'user.detail:user_id,work_city,gender,type_asesor,latitude,longitude',
            ])
            ->get();

        $can = $filled->where('kesediaan', true)->values();
        $cannot = $filled->where('kesediaan', false)->values();

        // Users who have NOT filled anything:
        // - no row at all, OR
        // - only rows where kesediaan, kesanggupan, alasan are all null
        $filledUserIds = $filled->pluck('user_id')->unique()->values();

        $notFilledUsers = User::query()
            ->select(['id', 'name', 'email'])
            ->with(['detail:user_id,work_city,gender,type_asesor,latitude,longitude'])
            ->role('asesor')
            ->when($filledUserIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $filledUserIds))
            ->orderBy('name')
            ->get();

        // Load latest draft run (if any) and related teams/unmatched
        $run = TeamGenerationRun::where('tahap_id', $tahap->id)->latest()->first();
        $teams = $run ? TeamDraft::with(['members.user.detail'])->where('run_id', $run->id)->get() : collect();
        $eligibleUserIds = Kesanggupan::where('tahap_id', $tahap->id)->where('kesediaan', true)->pluck('user_id')->toArray();
        $assignedUserIds = $run ? TeamDraftMember::where('run_id', $run->id)->pluck('user_id')->toArray() : [];
        $unmatched = User::whereIn('id', array_diff($eligibleUserIds, $assignedUserIds))->with('detail')->get();

        return view('menu.admin.tahap.kesanggupan.detilTahapKesanggupan', [
            'tahap' => $tahap,
            'can' => $can,
            'cannot' => $cannot,
            'notFilledUsers' => $notFilledUsers,
            'run' => $run,
            'teams' => $teams,
            'unmatched' => $unmatched,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tahap $tahap)
    {
        return view('menu.admin.tahap.edit', compact('tahap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'tahap' => ['required', 'string', 'max:255'],
            'surat_keputusan' => ['required', 'string', 'max:255'],
            'allowed_kesanggupan_csv' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $allowed = collect(preg_split('/\s*,\s*/', trim($data['allowed_kesanggupan_csv']), -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($v) => (int) $v)
            ->filter(fn ($v) => $v > 0)
            ->unique()
            ->values()
            ->all();

        if (count($allowed) === 0) {
            return back()
                ->withErrors(['allowed_kesanggupan_csv' => 'Kesanggupan Harus di isi minimal 1.'])
                ->withInput();
        }

        DB::transaction(function () use ($tahap, $data, $allowed) {
            $tahap->update([
                'tahap' => $data['tahap'],
                'surat_keputusan' => $data['surat_keputusan'],
                'allowed_kesanggupan' => $allowed,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);
        });

        return redirect()
            ->route('admin.tahap.index')
            ->with('success', 'Tahap updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
