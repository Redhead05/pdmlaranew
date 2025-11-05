<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::all();
        return view('menu.admin.attendance.index', compact('attendance'));
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
    public function store(AttendanceRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request -> validated();
        //mencatat siapa yang mengcreate data attendance
        $validatedData['created_by'] = auth()->user()->id;
        //membuat genereate auto base on title dan end date
        $validatedData['slug']= Str::slug($request->title . '-' . $request->end_date);
        // Create a new attendance record
        Attendance::create($validatedData);

        // Redirect to the index page with a success message
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance created successfully.');
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
    public function update(AttendanceRequest $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        //validate request from attendance request
        $validatedData = $request -> validated();

        $validatedData['slug'] = Str::slug($request->title . '-' . $request->end_date);

        $attendance->update($validatedData);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted successfully.');
    }

    public function detail($slug)
    {
        $attendance = Attendance::with('attendanceDetail.user')->where('slug', $slug)->firstOrFail();
        $details = $attendance->attendanceDetail; // get related details
        return view('menu.admin.attendance.detail', compact('attendance', 'details'));
    }

    /**
     * Show attendance responses (new response system)
     */
    public function showResponses($slug)
    {
        $attendance = Attendance::with('responses.user')->where('slug', $slug)->firstOrFail();
        $responses = $attendance->responses;
        return view('menu.admin.attendance.responses', compact('attendance', 'responses'));
    }
}
