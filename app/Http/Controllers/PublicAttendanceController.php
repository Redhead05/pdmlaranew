<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use Illuminate\Support\Facades\Auth;

class PublicAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        $attendance = Attendance::findOrFail($request->input('attendance_id'));
        $type = $attendance->type;

        if ($type === 'internal') {
            $v = $request->validate([
                'user_id'   => 'required|exists:users,id',
                'signature' => 'required|string',
            ]);

            $formData = [
                'selected_user_id' => (int) $v['user_id'],
            ];
            $userId = (int) $v['user_id'];

        } elseif ($type === 'umum') {
            $v = $request->validate([
                'name'      => 'required|string|max:191',
                'phone'     => 'required|string|max:50',
                'unsur'     => 'nullable|string|max:255',
                'instansi'  => 'nullable|string|max:255',
                'domisili'  => 'nullable|string|max:255',
                'signature' => 'required|string',
            ]);

            $formData = [
                'name'     => $v['name'],
                'phone'    => $v['phone'],
                'unsur'    => $v['unsur'] ?? null,
                'instansi' => $v['instansi'] ?? null,
                'domisili' => $v['domisili'] ?? null,
            ];
            $userId = null;

        } else { // asesor
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors('Login required for asesor submission.');
            }

            $v = $request->validate([
                'signature' => 'required|string',
            ]);

            $user = Auth::user();
            $formData = [
                'user_id'   => $user->id,
                'user_name' => $user->name,
            ];
            $userId = $user->id;
        }

        AttendanceDetail::create([
            'attendance_id' => $attendance->id,
            'user_id'       => $userId,
            'signature'     => $v['signature'],
            'signed_at'     => now(),
            'form_data'     => $formData,
        ]);

        if($type ==='umum'){
            return redirect()->route('pub.umum', ['slug' => $attendance->slug])
                ->with('success', 'Attendance submitted successfully.');
        }
        if($type ==='internal'){
            return redirect()->route('pub.internal', ['slug' => $attendance->slug])
                ->with('success', 'Attendance submitted successfully.');
        }

        return redirect()->route('asesor.attendance.index')
            ->with('success', 'Attendance submitted successfully.');
    }
    /**
     * Show public (umum) attendance form.
     */
    public function showUmum(string $slug)
    {
        $attendance = Attendance::where('slug', $slug)->where('type', 'umum')->firstOrFail();

        $now = Carbon::now(config('app.timezone'));

        // Use raw DB value to parse (model accessor returns formatted string)
        $rawEnd = $attendance->getRawOriginal('end_date');
        try {
            $endDate = $rawEnd ? Carbon::parse($rawEnd, config('app.timezone')) : null;
        } catch (\Exception $e) {
            $endDate = null;
        }

        $isOpen = $endDate ? $now->lte($endDate) : true;

        return view('menu.umum', compact('attendance', 'endDate', 'isOpen'));
    }
    /**
     * Show internal attendance form.
     */
    public function showInternal(string $slug)
    {
        $attendance = Attendance::where('slug', $slug)->where('type', 'internal')->firstOrFail();

        $users = User::orderBy('name')->get();

        $now = Carbon::now(config('app.timezone'));
        $rawEnd = $attendance->getRawOriginal('end_date');
        try {
            $endDate = $rawEnd ? Carbon::parse($rawEnd, config('app.timezone')) : null;
        } catch (\Exception $e) {
            $endDate = null;
        }

        $isOpen = $endDate ? $now->lte($endDate) : true;

        return view('menu.internal', compact('attendance', 'users', 'endDate', 'isOpen'));
    }
}

