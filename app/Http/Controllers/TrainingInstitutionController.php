<?php

namespace App\Http\Controllers;

use App\Models\TrainingInstitution;
use Illuminate\Http\Request;

class TrainingInstitutionController extends Controller
{
    public function index()
    {
        $institutions = TrainingInstitution::orderBy('name')->get();

        return view('training-institutions.index', compact('institutions'));
    }

    public function create()
    {
        return view('training-institutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:training_institutions,name',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        TrainingInstitution::create($validated);

        return redirect()
            ->route('training-institutions.index')
            ->with('success', 'Training institution added successfully');
    }

    public function show(TrainingInstitution $trainingInstitution)
    {
        return redirect()->route('training-institutions.edit', $trainingInstitution);
    }

    public function edit(TrainingInstitution $trainingInstitution)
    {
        return view('training-institutions.edit', ['institution' => $trainingInstitution]);
    }

    public function update(Request $request, TrainingInstitution $trainingInstitution)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:training_institutions,name,' . $trainingInstitution->id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $trainingInstitution->update($validated);

        return redirect()
            ->route('training-institutions.index')
            ->with('success', 'Training institution updated successfully');
    }

    public function destroy(TrainingInstitution $trainingInstitution)
    {
        $trainingInstitution->delete();

        return redirect()
            ->route('training-institutions.index')
            ->with('success', 'Training institution deleted successfully');
    }
}
