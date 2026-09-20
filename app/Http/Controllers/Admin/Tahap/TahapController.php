<?php

namespace App\Http\Controllers\Admin\Tahap;

use App\Exports\AsesorPairingExport;
use App\Http\Controllers\Controller;
use App\Models\Tahap;
use App\Models\User;
use App\Services\TahapPairingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class TahapController extends Controller
{
    protected TahapPairingService $pairing;

    public function __construct(TahapPairingService $pairing)
    {
        $this->pairing = $pairing;
    }

    /**
     * Pengaman eksekusi untuk dataset besar (ribuan asesor / lembaga).
     */
    protected function bumpLimits(): void
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahaps = Tahap::query()
            ->select(['id', 'slug', 'tahap', 'surat_keputusan', 'allowed_kesanggupan', 'created_at', 'start_date', 'end_date'])
            ->withCount([
                'lembagas',
                'teams as finalized_teams_count' => fn ($q) => $q->whereNotNull('finalized_at'),
                'generationRuns',
                'kesanggupans as bisa_count' => fn ($q) => $q->where('kesediaan', true),
                'kesanggupans as tidak_count' => fn ($q) => $q->where('kesediaan', false),
            ])
            ->latest('id')
            ->get();

        $totalAsesor = User::role('asesor')->count();

        return view('menu.admin.tahap.index', compact('tahaps', 'totalAsesor'));
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
            } while (
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
     * Display the specified resource: data kesanggupan asesor + pasangan asesor.
     */
    public function show(Tahap $tahap)
    {
        return view('menu.admin.tahap.kesanggupan.detilTahapKesanggupan', $this->pairing->pageData($tahap));
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

    // ==================================================================
    // DataTables: data kesanggupan asesor
    // ==================================================================

    /** Asesor yang bersedia (sudah mengisi kesanggupan). */
    public function bisaData(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        $teamMap = $this->pairing->teamCodeMap($tahap);
        $items = [];
        foreach ($this->pairing->bisaQuery($tahap)->get() as $k) {
            $detail = $k->user?->detail;
            $items[] = [
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'email' => (string) ($k->user->email ?? '-'),
                'kota' => (string) ($detail->work_city ?? '-'),
                'gender' => (string) ($detail->gender ?? '-'),
                'tipe' => (string) ($detail->type_asesor ?? '-'),
                'kesanggupan' => $k->kesanggupan,
                'tim' => $teamMap[$k->user_id] ?? '',
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    /** Asesor yang menyatakan tidak bisa beserta alasannya. */
    public function tidakBisaData(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        $items = [];
        foreach ($this->pairing->tidakBisaQuery($tahap)->get() as $k) {
            $detail = $k->user?->detail;
            $items[] = [
                'nia' => (string) ($k->user->nia ?? ''),
                'name' => (string) ($k->user->name ?? '-'),
                'email' => (string) ($k->user->email ?? '-'),
                'kota' => (string) ($detail->work_city ?? '-'),
                'gender' => (string) ($detail->gender ?? '-'),
                'tipe' => (string) ($detail->type_asesor ?? '-'),
                'alasan' => (string) ($k->alasan ?? '-'),
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    /** Asesor yang belum mengisi kesanggupan sama sekali. */
    public function belumMengisiData(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        $items = [];
        foreach ($this->pairing->belumMengisiQuery($tahap)->orderBy('users.name')->get() as $u) {
            $detail = $u->detail;
            $items[] = [
                'nia' => (string) ($u->nia ?? ''),
                'name' => (string) ($u->name ?? '-'),
                'email' => (string) ($u->email ?? '-'),
                'kota' => (string) ($detail->work_city ?? '-'),
                'gender' => (string) ($detail->gender ?? '-'),
                'tipe' => (string) ($detail->type_asesor ?? '-'),
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    // ==================================================================
    // DataTables: pasangan asesor
    // ==================================================================

    public function pairsData(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        $locked = $this->pairing->isLocked($tahap);
        $items = [];

        foreach ($this->pairing->pairs($tahap) as $pair) {
            $a = $pair['slots'][0] ?? null;
            $b = $pair['slots'][1] ?? null;

            $names = array_map(fn ($m) => $m['name'], $pair['members']);
            $items[] = [
                'team_id' => $pair['team_id'],
                'code' => $pair['code'],
                'anggota_title' => $names ? implode(' • ', $names) : 'Belum ada anggota',
                'nia_a' => $a['nia'] ?? '',
                'nama_a' => $a['name'] ?? '',
                'kota_a' => $a['city'] ?? '',
                'kes_a' => $a['kesanggupan'] ?? '',
                'nia_b' => $b['nia'] ?? '',
                'nama_b' => $b['name'] ?? '',
                'kota_b' => $b['city'] ?? '',
                'kes_b' => $b['kesanggupan'] ?? '',
                'anggota' => $pair['members_count'],
                'lembaga' => $pair['lembaga_count'],
                'slot_a_filled' => $a !== null,
                'slot_b_filled' => $b !== null,
                'action' => $this->pairActionHtml($pair, $locked),
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    protected function pairActionHtml(array $pair, bool $locked): string
    {
        if ($locked) {
            return '<span class="text-muted fs-13">Terkunci</span>';
        }

        $labels = ['A', 'B', 'C'];
        $out = '<div class="d-flex flex-wrap gap-1">';

        foreach ($pair['members'] as $i => $member) {
            $label = $labels[$i] ?? (string) ($i + 1);
            $out .= '<button type="button" class="btn btn-sm btn-outline-danger btn-keluarkan"'
                .' data-team="'.$pair['team_id'].'" data-user="'.$member['id'].'"'
                .' title="Keluarkan '.e($member['name']).' dari tim">'
                .'<span class="material-symbols-outlined align-middle" style="font-size:16px">person_remove</span> '
                .$label.'</button>';
        }

        // Slot A/B yang masih kosong bisa diisi dari sini.
        foreach ([0, 1] as $i) {
            if (($pair['slots'][$i] ?? null) !== null || count($pair['members']) > $i) {
                continue;
            }
            $slot = $i === 0 ? 'a' : 'b';
            $out .= '<button type="button" class="btn btn-sm btn-outline-primary btn-isi-slot"'
                .' data-team="'.$pair['team_id'].'" data-slot="'.$slot.'"'
                .' title="Isi slot '.strtoupper($slot).'">'
                .'<span class="material-symbols-outlined align-middle" style="font-size:16px">person_add</span> '
                .strtoupper($slot).'</button>';
        }

        $out .= '</div>';

        return $out;
    }

    public function unmatchedData(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        $locked = $this->pairing->isLocked($tahap);
        $items = [];
        foreach ($this->pairing->unmatched($tahap) as $u) {
            $items[] = $u + [
                'action' => $locked
                    ? '<span class="text-muted fs-13">Terkunci</span>'
                    : '<button type="button" class="btn btn-sm btn-primary btn-pasangkan" data-user="'.$u['id'].'">'
                        .'<span class="material-symbols-outlined align-middle" style="font-size:16px">group_add</span> Pasangkan</button>',
            ];
        }

        return DataTables::of(collect($items))->make(true);
    }

    /** Daftar asesor bersedia (untuk modal pilih/ubah asesor). */
    public function asesorOptions(Request $request, Tahap $tahap)
    {
        $this->assertAjax($request);

        return response()->json(['data' => $this->pairing->eligibleAsesor($tahap)]);
    }

    // ==================================================================
    // Generate pasangan asesor
    // ==================================================================

    public function generatePairs(Request $request, Tahap $tahap)
    {
        $this->bumpLimits();

        $reset = $request->boolean('reset_pairing');
        $result = $this->pairing->generate($tahap, $reset);

        if (! $result['ok']) {
            return back()->with('error', implode(' ', $result['errors']));
        }

        return back()->with('success', $result['message']);
    }

    // ==================================================================
    // Download & upload pasangan asesor (Excel)
    // ==================================================================

    public function downloadPairs(Request $request, Tahap $tahap)
    {
        $this->bumpLimits();

        $rows = $this->pairing->exportRows($tahap);
        if (! $rows) {
            return back()->with('error', 'Belum ada pasangan asesor untuk diunduh. Jalankan Generate terlebih dahulu.');
        }

        $filename = 'pasangan_asesor_tahap_'.$tahap->slug.'_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new AsesorPairingExport($rows), $filename);
    }

    public function uploadPairs(Request $request, Tahap $tahap)
    {
        $this->bumpLimits();

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ]);

        $errors = [];
        $rows = $this->readPairingUpload($request->file('file'), $errors);
        if ($rows === null) {
            return back()->with('error', implode('<br>', $errors));
        }
        if (! $rows) {
            return back()->with('error', 'File tidak berisi baris pasangan asesor. Gunakan file hasil unduhan tanpa mengubah baris header.');
        }

        $result = $this->pairing->applyUploadRows($tahap, $rows);

        if (! $result['ok']) {
            return back()->with('error', 'Upload ditolak:<br>'.implode('<br>', $result['errors']));
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Baca file unduhan "Pasangan Asesor" (xlsx / csv) menjadi baris isian.
     *
     * @return array<int, array{code:string, nia_a:string, nia_b:string, nia_c?:string}>|null
     */
    protected function readPairingUpload($file, array &$errors): ?array
    {
        $ext = strtolower((string) $file->getClientOriginalExtension());
        $raw = [];

        if (in_array($ext, ['csv', 'txt'], true)) {
            $handle = fopen($file->getRealPath(), 'rb');
            if ($handle === false) {
                $errors[] = 'Gagal membuka file CSV.';

                return null;
            }
            while (($row = fgetcsv($handle)) !== false) {
                $raw[] = array_map(fn ($c) => $this->normalizeCell($c), $row);
            }
            fclose($handle);
        } elseif ($ext === 'xlsx') {
            try {
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $names = $reader->listWorksheetNames($file->getRealPath());
                if (in_array('Pasangan Asesor', $names, true)) {
                    $reader->setLoadSheetsOnly(['Pasangan Asesor']);
                }
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getSheetByName('Pasangan Asesor') ?? $spreadsheet->getActiveSheet();
                foreach ($sheet->toArray(null, true, true, false) as $row) {
                    $raw[] = array_map(fn ($c) => $this->normalizeCell($c), (array) $row);
                }
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            } catch (\Throwable $e) {
                $errors[] = 'Gagal membaca file Excel: '.$e->getMessage();

                return null;
            }
        } else {
            $errors[] = 'Format file tidak didukung. Gunakan .xlsx atau .csv.';

            return null;
        }

        $raw = array_values(array_filter($raw, fn ($r) => count(array_filter($r, fn ($c) => trim((string) $c) !== '')) > 0));
        if (! $raw) {
            return [];
        }

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $raw[0]);
        $find = function (array $aliases) use ($header) {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $header, true);
                if ($idx !== false) {
                    return $idx;
                }
            }

            return null;
        };

        $colCode = $find(['kode tim', 'kode_tim', 'team_code', 'team']);
        $colA = $find(['nia asesor a', 'nia a', 'nia_a']);
        $colB = $find(['nia asesor b', 'nia b', 'nia_b']);
        // Kolom NIA Asesor C opsional (file lama belum memilikinya).
        $colC = $find(['nia asesor c', 'nia c', 'nia_c']);

        if ($colCode === null || $colA === null || $colB === null) {
            $errors[] = 'Kolom file tidak dikenali. Gunakan file hasil unduhan (kolom: Kode Tim, NIA Asesor A, NIA Asesor B) tanpa mengubah baris header.';

            return null;
        }

        $out = [];
        foreach (array_slice($raw, 1) as $row) {
            $item = [
                'code' => trim((string) ($row[$colCode] ?? '')),
                'nia_a' => trim((string) ($row[$colA] ?? '')),
                'nia_b' => trim((string) ($row[$colB] ?? '')),
            ];
            if ($colC !== null) {
                $item['nia_c'] = trim((string) ($row[$colC] ?? ''));
            }
            $out[] = $item;
        }

        return $out;
    }

    protected function normalizeCell($value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_float($value) && floor($value) === $value) {
            return (string) (int) $value;
        }

        return trim((string) $value);
    }

    // ==================================================================
    // Perubahan pasangan secara manual
    // ==================================================================

    public function setSlot(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'team_id' => ['required', 'integer'],
            'slot' => ['required', 'in:a,b'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $result = $this->pairing->setSlot($tahap, (int) $data['team_id'], $data['slot'], (int) $data['user_id']);

        return $this->manualResponse($request, $result);
    }

    public function addMember(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'team_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $result = $this->pairing->addMember($tahap, (int) $data['team_id'], (int) $data['user_id']);

        return $this->manualResponse($request, $result);
    }

    public function removeMember(Request $request, Tahap $tahap)
    {
        $data = $request->validate([
            'team_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $result = $this->pairing->removeMember($tahap, (int) $data['team_id'], (int) $data['user_id']);

        return $this->manualResponse($request, $result);
    }

    protected function manualResponse(Request $request, array $result)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result, $result['ok'] ? 200 : 422);
        }

        return back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    protected function assertAjax(Request $request): void
    {
        if (! $request->ajax()) {
            abort(404);
        }
    }
}
