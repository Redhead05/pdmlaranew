<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificationRequest;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $certifications = Certification::with('user')
            ->when($year, function ($q) use ($year) {
                $q->where('year', $year);
            })
            ->orderBy('issued_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $years = Certification::selectRaw('year')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('menu.admin.certifications.index', compact('certifications', 'year', 'years'));
    }

    public function create()
    {
        $asesors = User::role('asesor')->get();
        return view('menu.admin.certifications.create', compact('asesors'));
    }

    public function store(CertificationRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['issued_at']) && empty($data['year'])) {
            $data['year'] = Carbon::parse($data['issued_at'])->year;
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('certificates/'.$data['user_id'], 'public');
            $data['file_path'] = $path;
        }

        $cert = Certification::create($data);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Certification created.', 'cert' => $cert]);
        }

        return redirect()->route('admin.certifications.index')->with('success', 'Certification created.');
    }

    public function edit(Certification $certification)
    {
        $asesors = User::role('asesor')->get();
        // If AJAX request return JSON payload for modal population
        if (request()->ajax()) {
            return response()->json(['cert' => $certification]);
        }

        return view('menu.admin.certifications.edit', compact('certification', 'asesors'));
    }

    public function update(CertificationRequest $request, Certification $certification)
    {
        $data = $request->validated();

        if (!empty($data['issued_at']) && empty($data['year'])) {
            $data['year'] = Carbon::parse($data['issued_at'])->year;
        }

        if ($request->hasFile('file')) {
            // remove old file if exists
            if ($certification->file_path) {
                Storage::disk('public')->delete($certification->file_path);
            }
            $path = $request->file('file')->store('certificates/'.$data['user_id'], 'public');
            $data['file_path'] = $path;
        }

        $certification->update($data);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Certification updated.', 'cert' => $certification]);
        }

        return redirect()->route('admin.certifications.index')->with('success', 'Certification updated.');
    }

    public function destroy(Certification $certification)
    {
        $certification->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Certification deleted.']);
        }

        return redirect()->route('admin.certifications.index')->with('success', 'Certification deleted.');
    }

    public function show(Certification $certification)
    {
        return view('menu.admin.certifications.show', compact('certification'));
    }
}


