<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Tahap;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TahapLembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Tahap $tahap)
    {
        // If AJAX request (DataTables) return JSON of lembagas attached to this tahap
        if ($request->ajax()) {
            $query = $tahap->lembagas()->select(['lembagas.id', 'lembagas.npsn', 'lembagas.satuan_pen', 'lembagas.kabupaten', 'lembagas.kecamatan', 'lembagas.latitude', 'lembagas.longitude']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($tahap) {
                    $detachUrl = route('admin.tahap.lembaga.detach', ['tahap' => $tahap->slug, 'lembaga' => $row->id]);
                    $token = csrf_token();
                    return "<form action=\"{$detachUrl}\" method=\"POST\" onsubmit=\"return confirm('Hapus lembaga ini dari tahap?');\" class=\"d-inline\">" .
                        "<input type=\"hidden\" name=\"_token\" value=\"{$token}\">" .
                        "<button class=\"btn btn-sm btn-outline-danger\">Detach</button></form>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Non-AJAX: render blade view
        $lembagas = $tahap->lembagas()->orderBy('npsn')->get();
        return view('menu.admin.tahap.lembaga.index', compact('tahap', 'lembagas'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Download CSV template
     */
    public function template(Tahap $tahap)
    {
        $callback = function () {
            $handle = fopen('php://output', 'wb');
            // header
            fputcsv($handle, ['npsn']);
            // sample rows
            fputcsv($handle, ['12345678']);
            fputcsv($handle, ['87654321']);
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="masterlembaga_template.csv"',
        ]);
    }

    /**
     * Upload CSV, match NPSN against master lembaga, attach matches to tahap.
     */
    public function upload(Request $request, Tahap $tahap)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return redirect()->back()->with('error', 'Unable to open uploaded file');
        }

        $header = null;
        $npsns = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (!$header) {
                $header = array_map('strtolower', $row);
                // find index of npsn
                $npsnIndex = array_search('npsn', $header);
                if ($npsnIndex === false) {
                    // assume first column is npsn
                    $npsnIndex = 0;
                }
                continue;
            }

            if (!isset($row[$npsnIndex])) continue;
            $value = trim($row[$npsnIndex]);
            if ($value === '') continue;
            $npsns[] = $value;
        }
        fclose($handle);

        if (empty($npsns)) {
            return redirect()->back()->with('error', 'CSV tidak mengandung NPSN yang valid');
        }

        // find lembagas by npsn
        $found = Lembaga::whereIn('npsn', $npsns)->get()->keyBy('npsn');
        $attachIds = [];
        $unmatched = [];
        $conflicts = []; // lembaga found but already assigned to another tahap

        foreach ($npsns as $npsn) {
            if (!isset($found[$npsn])) {
                $unmatched[] = $npsn;
                continue;
            }

            $lembaga = $found[$npsn];
            // check if lembaga already attached to another tahap
            $attachedTahap = $lembaga->tahaps()->exists() ? $lembaga->tahaps()->first() : null;
            if ($attachedTahap && $attachedTahap->id !== $tahap->id) {
                $conflicts[] = [
                    'npsn' => $npsn,
                    'tahap' => $attachedTahap->tahap ?? $attachedTahap->slug ?? $attachedTahap->id,
                ];
                continue;
            }

            $attachIds[] = $lembaga->id;
        }

        // attach found lembagas to tahap without detaching existing, but skip conflicts
        if (!empty($attachIds)) {
            $tahap->lembagas()->syncWithoutDetaching(array_unique($attachIds));
        }

        $message = 'Upload selesai. ' . count($attachIds) . ' lembaga berhasil ditambahkan.';
        // flash success and errors for toast display
        return redirect()->back()->with([
            'success' => $message,
            'unmatched' => $unmatched,
            // return full conflict details (tahap name + npsn)
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * Confirm attach previewed lembagas to tahap
     */
    public function confirm(Request $request, Tahap $tahap)
    {
        $preview = session('tahap_preview_ids', []);
        if (!empty($preview)) {
            $tahap->lembagas()->syncWithoutDetaching($preview);
        }

        // clear preview session
        session()->forget(['tahap_preview_ids', 'tahap_preview_unmatched']);

        return redirect()->back()->with('success', 'Preview berhasil di-attach ke tahap.');
    }

    /**
     * Cancel preview (clear session)
     */
    public function cancelPreview(Request $request, Tahap $tahap)
    {
        session()->forget(['tahap_preview_ids', 'tahap_preview_unmatched']);
        return redirect()->back()->with('success', 'Preview dibatalkan.');
    }

    /**
     * Halaman pilih lembaga dari master (browse + centang massal).
     * Saat AJAX, kembalikan JSON DataTables dari seluruh master lembaga.
     */
    public function pilih(Request $request, Tahap $tahap)
    {
        if ($request->ajax()) {
            $query = Lembaga::with('tahaps:id,tahap')->select([
                'id', 'npsn', 'satuan_pen', 'kabupaten', 'kecamatan', 'jenjang',
            ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($l) use ($tahap) {
                    $attached = $l->tahaps->first();
                    if ($attached && $attached->id === $tahap->id) {
                        return '<span class="badge bg-secondary">Sudah di tahap ini</span>';
                    }
                    if ($attached) {
                        return '<span class="badge bg-warning text-dark">Dipakai: ' . e($attached->tahap) . '</span>';
                    }
                    return '<span class="badge bg-success">Tersedia</span>';
                })
                ->addColumn('select', function ($l) use ($tahap) {
                    $attached = $l->tahaps->first();
                    $disabled = $attached ? 'disabled' : '';
                    return '<input type="checkbox" class="lembaga-check" data-id="' . $l->id . '" ' . $disabled . '>';
                })
                ->rawColumns(['status', 'select'])
                ->make(true);
        }

        return view('menu.admin.tahap.lembaga.pilih', compact('tahap'));
    }

    /**
     * Attach lembaga terpilih ke tahap (skip yang sudah ada / dipakai tahap lain).
     */
    public function attach(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'lembaga_ids' => ['required', 'array', 'min:1'],
            'lembaga_ids.*' => ['integer', 'exists:lembagas,id'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['lembaga_ids'])));

        $lembagas = Lembaga::whereIn('id', $ids)->with('tahaps:id,tahap')->get()->keyBy('id');

        $already = [];
        $conflicts = [];
        $valid = [];

        foreach ($ids as $id) {
            if (! isset($lembagas[$id])) {
                continue;
            }
            $l = $lembagas[$id];
            $attached = $l->tahaps->first();
            if ($attached && $attached->id === $tahap->id) {
                $already[] = $l->npsn;
            } elseif ($attached) {
                $conflicts[] = ['npsn' => $l->npsn, 'tahap' => $attached->tahap];
            } else {
                $valid[] = $id;
            }
        }

        if (! empty($valid)) {
            $tahap->lembagas()->syncWithoutDetaching($valid);
        }

        return redirect()
            ->route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug])
            ->with([
                'success' => count($valid) . ' lembaga berhasil ditambahkan ke tahap.',
                'already' => $already,
                'conflicts' => $conflicts,
            ]);
    }

    /**
     * Detach lembaga from tahap
     */
    public function detach(Request $request, Tahap $tahap, Lembaga $lembaga)
    {
        $tahap->lembagas()->detach($lembaga->id);
        return redirect()->back()->with('success', 'Lembaga berhasil dilepas dari tahap');
    }
}
