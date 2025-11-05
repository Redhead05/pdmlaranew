<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceFormController extends Controller
{
    /**
     * Show the attendance form based on type
     */
    public function show(Attendance $attendance)
    {
        // Check if attendance is active (within date range)
        $now = now();
        if ($now->lt($attendance->start_date) || $now->gt($attendance->end_date)) {
            return redirect()->back()->with('error', 'Attendance form is not available at this time.');
        }

        // For 'asesor' type, require authentication
        // TODO: Add role check using hasRole('asesor') if using Spatie Permission
        if ($attendance->type === 'asesor' && !Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in as an asesor to access this attendance.');
        }

        // Return appropriate view based on type
        return view("attendance.forms.{$attendance->type}", compact('attendance'));
    }

    /**
     * Submit attendance form response
     */
    public function submit(Request $request, Attendance $attendance)
    {
        // Check if attendance is active
        $now = now();
        if ($now->lt($attendance->start_date) || $now->gt($attendance->end_date)) {
            return redirect()->back()->with('error', 'Attendance form is not available at this time.');
        }

        // For 'asesor' type, require authentication
        // TODO: Add role check using hasRole('asesor') if using Spatie Permission
        if ($attendance->type === 'asesor' && !Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in as an asesor to submit this attendance.');
        }

        // Validate based on type
        $rules = $this->getValidationRules($attendance->type);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare response data
        $responseData = [
            'attendance_id' => $attendance->id,
            'ip' => $request->ip(),
        ];

        // For asesor type, user must be authenticated
        if ($attendance->type === 'asesor') {
            $responseData['user_id'] = Auth::id();
            $responseData['name'] = Auth::user()->name;
            $responseData['email'] = Auth::user()->email;
        } else {
            // For umum and internal, capture provided data
            $responseData['name'] = $request->input('name');
            $responseData['email'] = $request->input('email');
            $responseData['user_id'] = null;
        }

        // Store additional fields in payload based on type
        $payload = $this->buildPayload($request, $attendance->type);
        $responseData['payload'] = $payload;

        // Create the response
        AttendanceResponse::create($responseData);

        // Redirect to thank you page
        return redirect()->route('attendance.thankyou', $attendance);
    }

    /**
     * Show thank you page
     */
    public function thankyou(Attendance $attendance)
    {
        return view('attendance.thankyou', compact('attendance'));
    }

    /**
     * Get validation rules based on attendance type
     */
    private function getValidationRules($type)
    {
        $commonRules = [];

        switch ($type) {
            case 'umum':
                $commonRules = [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone' => 'required|string|max:20',
                    'unsur' => 'required|string|max:255',
                    'instansi' => 'required|string|max:255',
                    'domisili' => 'required|string|max:255',
                    'signature' => 'required|string',
                ];
                break;

            case 'internal':
                $commonRules = [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'signature' => 'required|string',
                ];
                break;

            case 'asesor':
                // For asesor, name and email come from authenticated user
                $commonRules = [
                    'signature' => 'required|string',
                    'notes' => 'nullable|string|max:1000',
                ];
                break;
        }

        return $commonRules;
    }

    /**
     * Build payload array based on type
     */
    private function buildPayload(Request $request, $type)
    {
        $payload = [];

        switch ($type) {
            case 'umum':
                $payload = [
                    'phone' => $request->input('phone'),
                    'unsur' => $request->input('unsur'),
                    'instansi' => $request->input('instansi'),
                    'domisili' => $request->input('domisili'),
                    'signature' => $request->input('signature'),
                ];
                break;

            case 'internal':
                $payload = [
                    'signature' => $request->input('signature'),
                ];
                break;

            case 'asesor':
                $payload = [
                    'signature' => $request->input('signature'),
                    'notes' => $request->input('notes'),
                ];
                break;
        }

        return $payload;
    }
}
