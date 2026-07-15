<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\PlannedTraining;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\UnplannedTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $fyId = $request->financial_year_id;
        $deptId = $request->department_id;

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $departments = Department::orderBy('name')->get();

        $plannedQuery = PlannedTraining::query();
        $unplannedQuery = UnplannedTraining::query();

        if ($fyId) {
            $plannedQuery->where('financial_year_id', $fyId);
            $unplannedQuery->where('financial_year_id', $fyId);
        }
        if ($deptId) {
            $plannedQuery->where('department_id', $deptId);
            $unplannedQuery->where('department_id', $deptId);
        }

        $staffCount = Staff::count();
        $departmentCount = Department::count();

        $plannedCount = (clone $plannedQuery)->count();
        $unplannedCount = (clone $unplannedQuery)->count();
        $totalTrainings = $plannedCount + $unplannedCount;

        $monthPlanned = (clone $plannedQuery)->whereBetween('start_date', [$monthStart, $monthEnd])->count();
        $monthUnplanned = (clone $unplannedQuery)->whereBetween('start_date', [$monthStart, $monthEnd])->count();
        $monthTrainings = $monthPlanned + $monthUnplanned;

        $totalCost = (clone $plannedQuery)->sum('cost') + (clone $unplannedQuery)->sum('cost');

        $plannedCompleted = (clone $plannedQuery)->where('status', 'Completed')->count();
        $unplannedCompleted = (clone $unplannedQuery)->where('status', 'Completed')->count();
        $completed = $plannedCompleted + $unplannedCompleted;
        $completionRate = $totalTrainings > 0 ? round(($completed / $totalTrainings) * 100) : 0;

        $staffTrained = (clone $plannedQuery)->distinct('staff_id')->count('staff_id')
            + (clone $unplannedQuery)->distinct('staff_id')->count('staff_id');

        $statusLabels = ['Planned', 'Ongoing', 'Completed', 'Cancelled'];
        $statusData = [];
        foreach ($statusLabels as $s) {
            $statusData[$s] = (clone $plannedQuery)->where('status', $s)->count()
                + (clone $unplannedQuery)->where('status', $s)->count();
        }

        $categoryPlannedIds = (clone $plannedQuery)->pluck('training_category_id');
        $categoryUnplannedIds = (clone $unplannedQuery)->pluck('training_category_id');
        $categories = TrainingCategory::whereIn('id', $categoryPlannedIds->merge($categoryUnplannedIds)->unique())
            ->get()->map(function ($cat) use ($fyId, $deptId) {
                $p = PlannedTraining::where('training_category_id', $cat->id);
                $u = UnplannedTraining::where('training_category_id', $cat->id);
                if ($fyId) { $p->where('financial_year_id', $fyId); $u->where('financial_year_id', $fyId); }
                if ($deptId) { $p->where('department_id', $deptId); $u->where('department_id', $deptId); }
                return (object) [
                    'name' => $cat->name,
                    'total' => $p->count() + $u->count(),
                ];
            })->filter(fn($c) => $c->total > 0)->sortByDesc('total')->values();

        $upcoming = (clone $plannedQuery)->with(['staff', 'department', 'trainingCategory'])
            ->where('start_date', '>=', $now)
            ->orderBy('start_date')
            ->take(8)
            ->get();

        $recentPlanned = (clone $plannedQuery)->with(['staff', 'department'])
            ->latest()->take(5)->get();
        $recentUnplanned = (clone $unplannedQuery)->with(['staff', 'department'])
            ->latest()->take(5)->get();

        $deptStats = Department::withCount(['plannedTraining', 'unplannedTraining'])->orderBy('name')->get()->map(function ($dept) use ($fyId) {
            $p = PlannedTraining::where('department_id', $dept->id);
            $u = UnplannedTraining::where('department_id', $dept->id);
            if ($fyId) { $p->where('financial_year_id', $fyId); $u->where('financial_year_id', $fyId); }
            return (object) [
                'name' => $dept->name,
                'total' => $p->count() + $u->count(),
                'staff_count' => $dept->staff->count(),
            ];
        })->sortByDesc('total')->take(5);

        return view('dashboard', compact(
            'staffCount', 'departmentCount', 'totalTrainings', 'monthTrainings',
            'totalCost', 'completionRate', 'staffTrained',
            'statusData', 'categories', 'upcoming',
            'recentPlanned', 'recentUnplanned', 'deptStats',
            'financialYears', 'departments', 'fyId', 'deptId',
        ));
    }
}
