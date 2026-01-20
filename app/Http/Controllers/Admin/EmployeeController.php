<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start') ? (int)$request->query('start') : null;
        $end = $request->query('end') ? (int)$request->query('end') : null;

        $employees = Employee::yearRange($start, $end)->orderBy('start_year','desc')->orderBy('position')->paginate(20);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'position' => ['required','string', Rule::in(['Ketua','Sekretaris','Anggota','Sekretariat'])],
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_year' => 'nullable|integer|min:1900|max:2100',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'photo' => 'nullable|string',
        ]);

        $employee = Employee::create($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:191',
            'position' => ['sometimes','required','string', Rule::in(['Ketua','Sekretaris','Anggota','Sekretariat'])],
            'start_year' => 'nullable|integer|min:1900|max:2100',
            'end_year' => 'nullable|integer|min:1900|max:2100',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'photo' => 'nullable|string',
        ]);

        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted');
    }

    // assign single employee to a position (update only that employee)
    public function assignPosition(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'position' => ['required','string', Rule::in(['Ketua','Sekretaris','Anggota','Sekretariat'])],
            'start_year' => 'nullable|integer',
            'end_year' => 'nullable|integer',
        ]);

        $employee->update($data);

        return redirect()->back()->with('success', 'Position assigned');
    }
}

