<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $year = $request->get('year');

        $query = Certification::where('user_id', $user->id)->orderBy('issued_at', 'desc');
        if ($year) $query->where('year', $year);

        $certifications = $query->get()->groupBy(function($item){
            return $item->year ?? ($item->issued_at ? $item->issued_at->year : 'unknown');
        });

        $years = Certification::where('user_id', $user->id)->whereNotNull('year')->groupBy('year')->orderBy('year','desc')->pluck('year')->toArray();

        return view('menu.asesor.certifications.index', compact('certifications', 'years', 'year'));
    }

    public function show(Certification $certification)
    {
        $user = auth()->user();
        if ($certification->user_id !== $user->id) {
            abort(403);
        }

        return view('menu.asesor.certifications.show', compact('certification'));
    }

    // helper to download file via controller (optional)
    public function download(Certification $certification)
    {
        $user = auth()->user();
        if ($certification->user_id !== $user->id) abort(403);
        if (! $certification->file_path) abort(404);

        return Storage::disk('public')->download($certification->file_path);
    }
}
