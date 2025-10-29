<?php

namespace App\Http\Controllers\Asesor\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::orderBy('created_at', 'desc')->paginate(3);

        $attendanceData = $attendances->map(function ($attendance) {
            $detail = \App\Models\AttendanceDetail::where('attendance_id', $attendance->id)
                ->where('user_id', auth()->id())
                ->first();
            return [
                'attendance' => $attendance,
                'isSigned' => $detail ? true : false,
                'signature' => $detail ? $detail->signature : null,
            ];
        });

        return view('menu.asesor.attendance.index', [
            'attendances' => $attendances,
            'attendanceData' => $attendanceData,
        ]);
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
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'signature' => 'required|string',
        ]);

        AttendanceDetail::updateOrCreate(
            [
                'attendance_id' => $request->attendance_id,
                'user_id' => auth()->id(),
            ],
            [
                'signature' => $request->signature,
                'signed_at' => now(),
            ]
        );

        return redirect()->back()
            ->withInput(['attendance_id' => $request->attendance_id])
            ->with('success', 'Tanda Tangan Berhasil di simpan!');
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
