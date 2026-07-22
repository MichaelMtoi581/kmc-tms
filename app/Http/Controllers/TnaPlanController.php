<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FundingSource;
use App\Models\FinancialYear;
use App\Models\PlannedTraining;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use App\Models\TnaExercise;
use App\Models\TnaResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TnaPlanController extends Controller
{
    public function index(Request $request)
    {
        $exerciseId = $request->tna_exercise_id;

        $exercises = TnaExercise::with('financialYear')
            ->where('status', 'Closed')
            ->latest()
            ->get();

        $grouped = collect();
        $existingPlans = collect();

        if ($exerciseId) {
            $responses = TnaResponse::where('tna_exercise_id', $exerciseId)->get();

            $grouped = $responses->groupBy('training_needed')->map(function ($items, $trainingName) {
                $participants = $items->count();
                $deptNames = $items->pluck('department')->filter()->unique()->values()->toArray();
                $avgPriority = $this->priorityScore($items->pluck('priority'));
                $preferredInstitution = $items->pluck('preferred_institution')->filter()->first();
                $preferredDuration = $items->pluck('preferred_duration')->filter()->first();

                return (object) [
                    'training_name' => $trainingName,
                    'participants_count' => $participants,
                    'departments' => $deptNames,
                    'avg_priority' => $avgPriority,
                    'preferred_institution' => $preferredInstitution,
                    'preferred_duration' => $preferredDuration,
                    'response_ids' => $items->pluck('id')->toArray(),
                ];
            })->sortByDesc('avg_priority')->values();

            $existingPlans = PlannedTraining::where('tna_exercise_id', $exerciseId)->get();
        }

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $categories = TrainingCategory::orderBy('name')->get();
        $institutions = TrainingInstitution::orderBy('name')->get();
        $fundingSources = FundingSource::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('tna.plan.index', compact(
            'exercises', 'exerciseId', 'grouped', 'existingPlans',
            'financialYears', 'categories', 'institutions', 'fundingSources', 'departments',
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tna_exercise_id' => 'required|exists:tna_exercises,id',
            'financial_year_id' => 'required|exists:financial_years,id',
        ]);

        $exercise = TnaExercise::findOrFail($request->tna_exercise_id);

        $responses = TnaResponse::where('tna_exercise_id', $exercise->id)->get();

        if ($responses->isEmpty()) {
            return back()->with('error', 'No TNA responses found for this exercise.');
        }

        $existingCount = PlannedTraining::where('tna_exercise_id', $exercise->id)->count();
        if ($existingCount > 0) {
            return back()->with('error', "This exercise already has $existingCount planned training(s) linked. Delete them first to regenerate.");
        }

        $grouped = $responses->groupBy('training_needed');
        $created = 0;

        DB::beginTransaction();

        try {
            foreach ($grouped as $trainingName => $items) {
                $firstResponse = $items->first();
                $staffMember = $items->pluck('check_number')->first();

                $staff = \App\Models\Staff::where('check_number', $staffMember)->first();

                $dept = $items->pluck('department')->filter()->first();
                $department = $dept ? Department::where('name', $dept)->first() : null;

                $training = PlannedTraining::create([
                    'course_title' => $trainingName,
                    'staff_id' => $staff?->id,
                    'department_id' => $department?->id,
                    'financial_year_id' => $request->financial_year_id,
                    'training_category_id' => null,
                    'training_institution_id' => null,
                    'funding_source_id' => null,
                    'start_date' => null,
                    'end_date' => null,
                    'venue' => null,
                    'cost' => 0,
                    'status' => 'Planned',
                    'source' => 'Generated from TNA',
                    'tna_exercise_id' => $exercise->id,
                    'description' => "Auto-generated from TNA Exercise: {$exercise->title}. " .
                        $items->count() . " staff member(s) requested this training.",
                    'remarks' => "Departments: " . $items->pluck('department')->filter()->unique()->join(', '),
                ]);

                foreach ($items as $response) {
                    $training->participants()->create([
                        'tna_response_id' => $response->id,
                    ]);
                }

                $created++;
            }

            DB::commit();

            return redirect()->route('tna.plan.index', ['tna_exercise_id' => $exercise->id])
                ->with('success', "$created planned training(s) generated from TNA responses. You can now edit each training to assign institutions, dates, and costs.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate plan: ' . $e->getMessage());
        }
    }

    public function destroy(PlannedTraining $training)
    {
        $exerciseId = $training->tna_exercise_id;

        if ($training->source !== 'Generated from TNA') {
            return back()->with('error', 'Only TNA-generated trainings can be deleted from here.');
        }

        try {
            $training->delete();
            return back()->with('success', 'TNA-generated training deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete this training. It may have linked records.');
        }
    }

    private function priorityScore($priorities): float
    {
        $scores = ['High' => 3, 'Medium' => 2, 'Low' => 1];
        $values = $priorities->map(fn($p) => $scores[$p] ?? 2);
        return $values->avg();
    }
}
