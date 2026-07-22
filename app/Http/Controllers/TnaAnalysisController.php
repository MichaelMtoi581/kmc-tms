<?php

namespace App\Http\Controllers;

use App\Models\TnaExercise;
use App\Models\TnaResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TnaAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $exerciseId = $request->tna_exercise_id;

        $exercises = TnaExercise::with('financialYear')->latest()->get();

        $query = TnaResponse::query();
        if ($exerciseId) {
            $query->where('tna_exercise_id', $exerciseId);
        }

        $totalResponses = (clone $query)->count();
        $uniqueStaff = (clone $query)->distinct('check_number')->count('check_number');

        $topTrainings = (clone $query)
            ->select('training_needed', DB::raw('count(*) as total'))
            ->groupBy('training_needed')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $byDepartment = (clone $query)
            ->select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->orderByDesc('total')
            ->get();

        $byPriority = (clone $query)
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        $uniqueTrainings = $topTrainings->count();

        $byInstitution = (clone $query)
            ->select('preferred_institution', DB::raw('count(*) as total'))
            ->whereNotNull('preferred_institution')
            ->where('preferred_institution', '!=', '')
            ->groupBy('preferred_institution')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('tna.analysis.index', compact(
            'exercises', 'exerciseId', 'totalResponses', 'uniqueStaff',
            'topTrainings', 'byDepartment', 'byPriority', 'uniqueTrainings',
            'byInstitution',
        ));
    }
}
