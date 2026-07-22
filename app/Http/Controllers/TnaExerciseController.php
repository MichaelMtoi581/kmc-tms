<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use App\Models\TnaExercise;
use Illuminate\Http\Request;

class TnaExerciseController extends Controller
{
    public function index()
    {
        $exercises = TnaExercise::with(['financialYear', 'creator', 'responses'])
            ->latest()
            ->paginate(20);

        return view('tna.exercises.index', compact('exercises'));
    }

    public function create()
    {
        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        return view('tna.exercises.create', compact('financialYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'financial_year_id' => 'required|exists:financial_years,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Open,Closed',
        ]);

        $hasOpen = TnaExercise::where('financial_year_id', $request->financial_year_id)
            ->where('status', 'Open')
            ->exists();

        if ($hasOpen) {
            return back()->withErrors(['financial_year_id' => 'An Open TNA exercise already exists for this financial year.'])->withInput();
        }

        TnaExercise::create(array_merge($request->only([
            'financial_year_id', 'title', 'description',
            'start_date', 'end_date', 'status',
        ]), ['created_by' => auth()->id()]));

        return redirect()->route('tna.exercises.index')
            ->with('success', 'TNA Exercise created successfully.');
    }

    public function show(TnaExercise $exercise)
    {
        $exercise->load(['financialYear', 'creator', 'responses']);
        return view('tna.exercises.show', compact('exercise'));
    }

    public function edit(TnaExercise $exercise)
    {
        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        return view('tna.exercises.edit', compact('exercise', 'financialYears'));
    }

    public function update(Request $request, TnaExercise $exercise)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Open,Closed',
        ]);

        $exercise->update($request->only([
            'title', 'description', 'start_date', 'end_date', 'status',
        ]));

        return redirect()->route('tna.exercises.index')
            ->with('success', 'TNA Exercise updated successfully.');
    }

    public function destroy(TnaExercise $exercise)
    {
        try {
            $exercise->delete();
            return redirect()->route('tna.exercises.index')
                ->with('success', 'TNA Exercise deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete exercise with existing responses. Delete responses first.');
        }
    }
}
