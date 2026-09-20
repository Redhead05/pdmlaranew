<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Tahap;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        $redirect = redirect()->route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return $redirect->with('error', 'Unable to open uploaded file');
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
            return $redirect->with('error', 'CSV tidak mengandung NPSN yang valid');
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
        return $redirect->with([
            'success' => $message,
            'unmatched' => $unmatched,
            // return full conflict details (tahap name + npsn)
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * Parse NPSN dari file Excel/CSV (upload via halaman pilih),
     * lalu kembalikan status setiap NPSN terhadap master lembaga.
     * Dipakai untuk auto-centang checkbox yang cocok + toast NPSN yang tidak ada.
     */
    public function checkNpsn(Request $request, Tahap $tahap)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        $file = $request->file('file');
        $ext = strtolower((string) $file->getClientOriginalExtension());
        $raw = [];

        if (in_array($ext, ['csv', 'txt'], true)) {
            $handle = fopen($file->getRealPath(), 'rb');
            if ($handle === false) {
                return response()->json(['message' => 'Gagal membuka file.'], 422);
            }
            while (($row = fgetcsv($handle)) !== false) {
                $raw[] = array_map(fn ($c) => $this->normalizeNpsnCell($c), (array) $row);
            }
            fclose($handle);
        } else {
            try {
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                foreach ($sheet->toArray(null, true, true, false) as $row) {
                    $raw[] = array_map(fn ($c) => $this->normalizeNpsnCell($c), (array) $row);
                }
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            } catch (\Throwable $e) {
                return response()->json(['message' => 'Gagal membaca file Excel: '.$e->getMessage()], 422);
            }
        }

        // Buang baris kosong.
        $raw = array_values(array_filter($raw, fn ($r) => count(array_filter($r, fn ($c) => trim((string) $c) !== '')) > 0));
        if (! $raw) {
            return response()->json(['message' => 'File kosong.'], 422);
        }

        // Cari kolom NPSN; jika tidak ada header NPSN, asumsikan kolom pertama.
        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $raw[0]);
        $npsnIndex = array_search('npsn', $header, true);
        if ($npsnIndex === false) {
            $npsnIndex = 0;
        }

        $npsns = [];
        foreach (array_slice($raw, 1) as $row) {
            $value = trim((string) ($row[$npsnIndex] ?? ''));
            if ($value === '') {
                continue;
            }
            $npsns[] = $value;
        }
        $npsns = array_values(array_unique($npsns));

        if (empty($npsns)) {
            return response()->json(['message' => 'Tidak ada NPSN yang terbaca dari file.'], 422);
        }

        $found = Lembaga::whereIn('npsn', $npsns)->with('tahaps:id,tahap')->get()->keyBy('npsn');

        $available = [];
        $already = [];
        $conflict = [];
        $missing = [];

        foreach ($npsns as $npsn) {
            $lembaga = $found->get($npsn);
            if (! $lembaga) {
                $missing[] = $npsn;
                continue;
            }

            $attached = $lembaga->tahaps->first();
            if ($attached && $attached->id === $tahap->id) {
                $already[] = $npsn;
            } elseif ($attached) {
                $conflict[] = ['npsn' => $npsn, 'tahap' => $attached->tahap];
            } else {
                $available[] = ['id' => $lembaga->id, 'npsn' => $npsn];
            }
        }

        return response()->json([
            'message' => count($available).' NPSN ditemukan dan siap dicentang.',
            'available' => $available,
            'already' => $already,
            'conflict' => $conflict,
            'missing' => $missing,
        ]);
    }

    /**
     * Normalisasi nilai sel NPSN: angka float bulat diubah ke string integer,
     * sisanya di-trim sebagai string (pertahankan nol di depan).
     */
    protected function normalizeNpsnCell($value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_float($value) && floor($value) === $value) {
            return (string) (int) $value;
        }

        return trim((string) $value);
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

        return redirect()
            ->route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug])
            ->with('success', 'Preview berhasil di-attach ke tahap.');
    }

    /**
     * Cancel preview (clear session)
     */
    public function cancelPreview(Request $request, Tahap $tahap)
    {
        session()->forget(['tahap_preview_ids', 'tahap_preview_unmatched']);
        return redirect()
            ->route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug])
            ->with('success', 'Preview dibatalkan.');
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
        return redirect()
            ->route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug])
            ->with('success', 'Lembaga berhasil dilepas dari tahap');
    }
}
