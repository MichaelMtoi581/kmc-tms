<?php

namespace App\Http\Controllers;

use App\Models\TrainingCategory;
use Illuminate\Http\Request;

class TrainingCategoryController extends Controller
{
    public function index()
    {
        $categories = TrainingCategory::orderBy('name')->get();

        return view('training-categories.index', compact('categories'));
    }

    public function show(TrainingCategory $trainingCategory)
    {
        return redirect()->route('training-categories.edit', $trainingCategory);
    }

    public function create()
    {
        return view('training-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:training_categories,name',
        ]);

        TrainingCategory::create($validated);

        return redirect()
            ->route('training-categories.index')
            ->with('success', 'Training category added successfully');
    }

    public function edit(TrainingCategory $trainingCategory)
    {
        return view('training-categories.edit', ['category' => $trainingCategory]);
    }

    public function update(Request $request, TrainingCategory $trainingCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:training_categories,name,' . $trainingCategory->id,
        ]);

        $trainingCategory->update($validated);

        return redirect()
            ->route('training-categories.index')
            ->with('success', 'Training category updated successfully');
    }

    public function destroy(TrainingCategory $trainingCategory)
    {
        $trainingCategory->delete();

        return redirect()
            ->route('training-categories.index')
            ->with('success', 'Training category deleted successfully');
    }
}
