<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PlannedTraining;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\UnplannedTraining;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $staffCount = Staff::count();
        $departmentCount = Department::count();

        $plannedCount = PlannedTraining::count();
        $unplannedCount = UnplannedTraining::count();
        $totalTrainings = $plannedCount + $unplannedCount;

        $monthTrainings = PlannedTraining::whereBetween('start_date', [$monthStart, $monthEnd])->count()
            + UnplannedTraining::whereBetween('start_date', [$monthStart, $monthEnd])->count();

        $totalCost = PlannedTraining::sum('cost') + UnplannedTraining::sum('cost');

        $plannedCompleted = PlannedTraining::where('status', 'Completed')->count();
        $unplannedCompleted = UnplannedTraining::where('status', 'Completed')->count();
        $completed = $plannedCompleted + $unplannedCompleted;
        $completionRate = $totalTrainings > 0 ? round(($completed / $totalTrainings) * 100) : 0;

        $staffTrained = PlannedTraining::distinct('staff_id')->count('staff_id')
            + UnplannedTraining::distinct('staff_id')->count('staff_id');

        $statusLabels = ['Planned', 'Ongoing', 'Completed', 'Cancelled'];
        $statusData = [];
        foreach ($statusLabels as $s) {
            $statusData[$s] = PlannedTraining::where('status', $s)->count()
                + UnplannedTraining::where('status', $s)->count();
        }

        $categories = TrainingCategory::withCount(['plannedTraining', 'unplannedTraining'])->get()->map(function ($cat) {
            return (object) [
                'name' => $cat->name,
                'total' => $cat->planned_training_count + $cat->unplanned_training_count,
            ];
        })->sortByDesc('total')->values();

        $upcoming = PlannedTraining::with(['staff', 'department', 'trainingCategory'])
            ->where('start_date', '>=', $now)
            ->orderBy('start_date')
            ->take(8)
            ->get();

        $recentPlanned = PlannedTraining::with(['staff', 'department'])
            ->latest()->take(5)->get();
        $recentUnplanned = UnplannedTraining::with(['staff', 'department'])
            ->latest()->take(5)->get();

        $deptStats = Department::withCount(['plannedTraining', 'unplannedTraining'])->orderBy('name')->get()->map(function ($dept) {
            return (object) [
                'name' => $dept->name,
                'total' => $dept->planned_training_count + $dept->unplanned_training_count,
                'staff_count' => $dept->staff->count(),
            ];
        })->sortByDesc('total')->take(5);

        return view('dashboard', compact(
            'staffCount', 'departmentCount', 'totalTrainings', 'monthTrainings',
            'totalCost', 'completionRate', 'staffTrained',
            'statusData', 'categories', 'upcoming',
            'recentPlanned', 'recentUnplanned', 'deptStats',
        ));
    }
}
