<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kesanggupan;
use App\Models\Tahap;
use Illuminate\Http\Request;

class KesanggupanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kesanggupans = Kesanggupan::query()
            ->with('tahap')
            ->whereHas('tahap', function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderByDesc('id')
            ->get();

        return view('menu.admin.kesanggupan.index', compact('kesanggupans'));
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
            'tahap_id' => ['required', 'integer', 'exists:tahaps,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'kesediaan' => ['required', 'boolean'],
            'kesanggupan' => ['nullable', 'integer'],
            'alasan' => ['nullable', 'string'],
        ]);

        $tahap = Tahap::query()->findOrFail($data['tahap_id']);
        $allowed = $tahap->allowed_kesanggupan ?? [];

        if ((bool) $data['kesediaan'] === false) {
            $data['alasan'] = $request->validate([
                'alasan' => ['required', 'string', 'min:5'],
            ])['alasan'];

            $data['kesanggupan'] = null;
        } else {
            $request->validate([
                'kesanggupan' => ['required', 'integer', 'in:' . implode(',', array_map('intval', $allowed))],
            ]);

            $data['alasan'] = null;
        }

        Kesanggupan::create($data);

        return redirect()->route('admin.kesanggupan.index');
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
