<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DataTables handles search/sort/pagination client-side.
        $departments = Department::withCount('staff')->orderBy('name')->get();

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return redirect()->route('departments.edit', $department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        if ($department->staff()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with('error', "Cannot delete \"{$department->name}\" — it still has staff assigned to it.");
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }
}
