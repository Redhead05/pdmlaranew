<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lembaga;

class LembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lembagas = Lembaga::orderBy('npsn')->get();
        return view('menu.admin.lembagas.index', compact('lembagas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.admin.lembagas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'npsn' => ['required','string','max:20','unique:lembagas,npsn'],
            'satuan_pen' => ['required','string','max:255'],
            'alamat' => ['nullable','string'],
            'kelurahan' => ['nullable','string','max:255'],
            'kecamatan' => ['nullable','string','max:255'],
            'kabupaten' => ['nullable','string','max:255'],
            'status' => ['nullable','string','max:50'],
            'jenjang' => ['nullable','string','max:50'],
            'bentuk_pendidikan' => ['nullable','string','max:255'],
            'latitude' => ['nullable','numeric'],
            'longitude' => ['nullable','numeric'],
        ]);

        Lembaga::create($data);
        return redirect()->route('admin.lembagas.index')->with('success', 'Lembaga berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lembaga $lembaga)
    {
        return view('menu.admin.lembagas.edit', compact('lembaga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lembaga $lembaga)
    {
        $data = $request->validate([
            'npsn' => ['required','string','max:20','unique:lembagas,npsn,' . $lembaga->id],
            'satuan_pen' => ['required','string','max:255'],
            'alamat' => ['nullable','string'],
            'kelurahan' => ['nullable','string','max:255'],
            'kecamatan' => ['nullable','string','max:255'],
            'kabupaten' => ['nullable','string','max:255'],
            'status' => ['nullable','string','max:50'],
            'jenjang' => ['nullable','string','max:50'],
            'bentuk_pendidikan' => ['nullable','string','max:255'],
            'latitude' => ['nullable','numeric'],
            'longitude' => ['nullable','numeric'],
        ]);

        $lembaga->update($data);
        return redirect()->route('admin.lembagas.index')->with('success', 'Lembaga berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lembaga $lembaga)
    {
        $lembaga->delete();
        return back()->with('success', 'Lembaga berhasil dihapus');
    }
}

