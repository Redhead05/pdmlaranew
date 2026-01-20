<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // ambil daftar perioda unik dari database (start_year + end_year)
        $periodsQuery = Employee::select('start_year', 'end_year')
            ->whereNotNull('start_year')
            ->whereNotNull('end_year')
            ->distinct()
            ->orderBy('start_year', 'desc')
            ->get();

        $periods = $periodsQuery->map(function ($p) {
            return [
                'start' => (int) $p->start_year,
                'end' => (int) $p->end_year,
                'label' => $p->start_year . '-' . $p->end_year,
            ];
        })->values();

        $start = $request->query('start') ? (int)$request->query('start') : null;
        $end = $request->query('end') ? (int)$request->query('end') : null;

        // Jika tidak ada periode dipilih, default ke perioda pertama (yang paling baru) jika ada
        if (($start === null || $end === null) && $periods->count() > 0) {
            $start = $start ?? $periods[0]['start'];
            $end = $end ?? $periods[0]['end'];
        }

        // Ambil employees yang cocok EXACT dengan pasangan start & end
        if ($start !== null && $end !== null) {
            $employees = Employee::where('start_year', $start)->where('end_year', $end)->orderBy('position')->orderBy('name')->get();
        } else {
            // fallback: ambil semua jika tidak ada periode
            $employees = Employee::orderBy('position')->orderBy('name')->get();
        }

        $ketua = $employees->firstWhere('position', 'Ketua');
        $sekretaris = $employees->firstWhere('position', 'Sekretaris');
        $anggota = $employees->where('position', 'Anggota')->values();
        $sekretariat = $employees->where('position', 'Sekretariat')->values();

        return view('frontend.pages.employes', compact('ketua', 'sekretaris', 'anggota', 'sekretariat', 'start', 'end', 'periods'));
    }
}
