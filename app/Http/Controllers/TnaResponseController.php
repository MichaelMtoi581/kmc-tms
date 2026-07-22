<?php

namespace App\Http\Controllers;

use App\Models\TnaExercise;
use App\Models\TnaResponse;
use Illuminate\Http\Request;

class TnaResponseController extends Controller
{
    public function index(Request $request)
    {
        $query = TnaResponse::with('exercise.financialYear');

        if ($request->filled('tna_exercise_id')) {
            $query->where('tna_exercise_id', $request->tna_exercise_id);
        }

        $responses = $query->latest()->paginate(50);
        $exercises = TnaExercise::with('financialYear')->latest()->get();

        return view('tna.responses.index', compact('responses', 'exercises'));
    }

    public function show(TnaResponse $response)
    {
        $response->load('exercise.financialYear', 'staff');
        return view('tna.responses.show', compact('response'));
    }

    public function edit(TnaResponse $response)
    {
        $response->load('exercise');
        $exercises = TnaExercise::where('status', 'Open')->get();
        return view('tna.responses.edit', compact('response', 'exercises'));
    }

    public function update(Request $request, TnaResponse $response)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'training_needed' => 'required|string|max:255',
            'training_reason' => 'nullable|string',
            'expected_outcome' => 'nullable|string',
            'priority' => 'required|in:High,Medium,Low',
            'preferred_duration' => 'nullable|string|max:255',
            'preferred_institution' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $response->update($request->only([
            'full_name', 'department', 'designation', 'training_needed',
            'training_reason', 'expected_outcome', 'priority',
            'preferred_duration', 'preferred_institution', 'remarks',
        ]));

        return redirect()->route('tna.responses.index', ['tna_exercise_id' => $response->tna_exercise_id])
            ->with('success', 'Response updated successfully.');
    }

    public function destroy(TnaResponse $response)
    {
        $exerciseId = $response->tna_exercise_id;
        $response->delete();

        return redirect()->route('tna.responses.index', ['tna_exercise_id' => $exerciseId])
            ->with('success', 'Response deleted.');
    }

    public function export(Request $request)
    {
        $exerciseId = $request->tna_exercise_id;

        $data = TnaResponse::when($exerciseId, fn($q) => $q->where('tna_exercise_id', $exerciseId))
            ->with('exercise.financialYear')
            ->get();

        $headings = ['Check No', 'Full Name', 'Department', 'Designation', 'Training Needed',
            'Reason', 'Expected Outcome', 'Priority', 'Duration', 'Institution', 'Remarks'];

        $rows = $data->map(fn($r) => [
            $r->check_number, $r->full_name, $r->department, $r->designation,
            $r->training_needed, $r->training_reason, $r->expected_outcome,
            $r->priority, $r->preferred_duration, $r->preferred_institution, $r->remarks,
        ])->toArray();

        $excel = new \App\Exports\ReportExport(collect($rows), $headings);
        return \Maatwebsite\Excel\Facades\Excel::download($excel, 'tna-responses-' . now()->format('Y-m-d') . '.xlsx');
    }
}
