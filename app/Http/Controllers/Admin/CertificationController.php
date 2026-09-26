<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificationRequest;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $rows = Certification::with('user')
            ->when($year, function ($q) use ($year) {
                $q->where('year', $year);
            })
            ->orderBy('issued_at', 'desc')
            ->get();

        // Group per batch agar datatable ringan: satu baris = satu batch sertifikat.
        $certifications = $rows->groupBy(function ($c) {
            return $c->batch_id ?? 'single-'.$c->id;
        })->map(function ($group) {
            $first = $group->first();
            $first->recipient_count = $group->count();

            return $first;
        })->values();

        $years = Certification::selectRaw('year')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('menu.admin.certifications.index', compact('certifications', 'year', 'years'));
    }

    /**
     * Detail satu batch: asesor yang mendapat + yang tidak mendapat (JSON).
     */
    public function detail(string $batch)
    {
        if (str_starts_with($batch, 'single-')) {
            $id = (int) substr($batch, 7);
            $rows = Certification::with('user')->where('id', $id)->get();
        } else {
            $rows = Certification::with('user')->where('batch_id', $batch)->get();
        }

        if ($rows->isEmpty()) {
            abort(404);
        }

        $recipients = $rows->map->user->filter()->unique('id')->values();
        $all = User::role('asesor')->orderBy('name')->get();
        $recipientIds = $recipients->pluck('id');
        $nonRecipients = $all->whereNotIn('id', $recipientIds)->values();

        return response()->json([
            'batch' => $rows->first(),
            'recipients' => $recipients,
            'non_recipients' => $nonRecipients,
        ]);
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

        $sendToAll = ! empty($data['send_to_all']);

        if ($sendToAll) {
            $exceptNia = array_filter(array_map('trim', preg_split('/[\s,;]+/', $data['except_nia'] ?? '')));
            $users = User::role('asesor')
                ->when($exceptNia, fn ($q) => $q->whereNotIn('nia', $exceptNia))
                ->get();
        } else {
            $users = User::where('id', $data['user_id'])->get();
        }

        if ($users->isEmpty()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada asesor penerima.'], 422);
            }

            return back()->with('error', 'Tidak ada asesor penerima.');
        }

        $sharedPath = null;
        if ($request->hasFile('file')) {
            $sharedPath = $request->file('file')->store('certificates/_tmp', 'public');
        }

        $batchId = (string) Str::uuid();

        $created = 0;
        foreach ($users as $user) {
            $row = $data;
            unset($row['send_to_all'], $row['except_nia']);
            $row['user_id'] = $user->id;
            $row['batch_id'] = $batchId;

            if ($sharedPath) {
                $dest = 'certificates/'.$user->id.'/'.basename($sharedPath);
                Storage::disk('public')->copy($sharedPath, $dest);
                $row['file_path'] = $dest;
            }

            Certification::create($row);
            $created++;
        }

        if ($sharedPath) {
            Storage::disk('public')->delete($sharedPath);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $created.' sertifikat dibuat.', 'count' => $created]);
        }

        return redirect()->route('admin.certifications.index')->with('success', $created.' sertifikat dibuat.');
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


