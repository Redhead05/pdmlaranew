<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
//        if (auth()->user()->role !== 'admin') {
//            abort(403, 'Unauthorized action.');
//        }
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:asesor,internal,umum', // Adjust options as needed
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        //mencatat siapa yang mengcreate data attendance
        $validatedData['created_by'] = auth()->user()->id;
        //membuat genereate auto base on title dan end date
        $validatedData['slug']= Str::slug($request->title . '-' . $request->end_date);
        // Create a new attendance record
        Attendance::create($validatedData);

        // Redirect to the index page with a success message
        return redirect()->route('attendance.index')->with('success', 'Attendance created successfully.');
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
        $attendance = Attendance::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:asesor,internal,umum',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $validatedData['slug'] = Str::slug($request->title . '-' . $request->end_date);

        $attendance->update($validatedData);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
