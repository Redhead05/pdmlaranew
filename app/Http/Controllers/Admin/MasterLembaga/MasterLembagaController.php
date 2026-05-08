<?php

namespace App\Http\Controllers\Admin\MasterLembaga;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterLembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If the request is AJAX (DataTables), return server-side JSON via Yajra DataTables
        if ($request->ajax()) {
            $query = Lembaga::select(['id', 'npsn', 'satuan_pen', 'kabupaten', 'jenjang', 'latitude', 'longitude']);

            return DataTables::of($query)
                ->editColumn('latitude', fn($row) => $row->latitude ?? '-')
                ->editColumn('longitude', fn($row) => $row->longitude ?? '-')
                ->make(true);
        }

        // Non-AJAX: render the page which will initialize DataTables and request data via the same route
        return view('menu.admin.masterlembaga.index');
    }

    /**
     * Server-side data endpoint for DataTables
     */



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
}
