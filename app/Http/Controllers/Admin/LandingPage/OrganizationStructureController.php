<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\OrganizationStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = OrganizationStructure::latest()->paginate(20);
        $users = User::all(); // add this so the create partial has $users
        return view('menu.adminlanding.organization_structure.index', compact('items', 'users'));
    }

    public function create()
    {
        // ambil semua user (atau sesuaikan query)
        $users = User::all();

        // kirim ke view
        return view('menu.adminlanding.organization_structure.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|integer|exists:users,id',
            'position'  => 'required|string|max:255',
            'period'    => 'nullable|string|max:255',
            'avatar'    => 'nullable|image|max:2048',
            'email'     => 'nullable|email|max:255',
            'instagram' => 'nullable|url|max:255',
            'facebook'  => 'nullable|url|max:255',
            'linkedin'  => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('organization_structures', 'public');
        }

        OrganizationStructure::create($validated);

        return redirect()->route('adminlanding.StrukturOrganisasi.index')
            ->with('success', 'Data berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrganizationStructure $StrukturOrganisasi)
    {
        return view('menu.adminlanding.organization_structure.show', ['item' => $StrukturOrganisasi]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrganizationStructure $StrukturOrganisasi)
    {
        $users = User::all();
        return view('menu.adminlanding.organization_structure.edit', [
            'item' => $StrukturOrganisasi,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrganizationStructure $StrukturOrganisasi)
    {
        $data = $request->validate([
            'user_id'   => 'required|integer|exists:users,id',
            'position'  => 'required|string|max:255',
            'period'    => 'nullable|string|max:255',
            'avatar'    => 'nullable|image|max:2048',
            'email'     => 'nullable|email|max:255',
            'instagram' => 'nullable|url|max:255',
            'facebook'  => 'nullable|url|max:255',
            'linkedin'  => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('avatar')) {
            if ($StrukturOrganisasi->avatar) {
                Storage::disk('public')->delete($StrukturOrganisasi->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('organization_structures', 'public');
        }

        $StrukturOrganisasi->update($data);

        return redirect()->route('adminlanding.StrukturOrganisasi.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrganizationStructure $StrukturOrganisasi)
    {
        if ($StrukturOrganisasi->avatar) {
            Storage::disk('public')->delete($StrukturOrganisasi->avatar);
        }

        $StrukturOrganisasi->delete();

        return redirect()->route('adminlanding.StrukturOrganisasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
