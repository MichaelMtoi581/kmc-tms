<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DataTables handles search/sort/pagination client-side.
        $staff = Staff::with('department')->latest()->get();

        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('staff.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateStaff($request);

        Staff::create($validated);

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return redirect()->route('staff.edit', $staff);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $departments = Department::orderBy('name')->get();

        return view('staff.edit', compact('staff', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $this->validateStaff($request, $staff->id);

        $staff->update($validated);

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member deleted successfully');
    }

    /**
     * Shared validation rules for store() and update().
     */
    private function validateStaff(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'check_number' => 'required|string|max:50|unique:staff,check_number' . ($ignoreId ? ",{$ignoreId}" : ''),
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'nullable|date|before:today',
            'designation' => 'required|string|max:150',
            'education_level' => 'nullable|string|max:150',
            'department_id' => 'required|exists:departments,id',
        ]);
    }
}
