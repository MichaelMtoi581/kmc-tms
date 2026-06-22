<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\FundingSource;
use App\Models\PlannedTraining;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use App\Models\UnplannedTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $plannedCount = PlannedTraining::count();
        $unplannedCount = UnplannedTraining::count();
        $totalTrainings = $plannedCount + $unplannedCount;
        $totalCost = PlannedTraining::sum('cost') + UnplannedTraining::sum('cost');
        $staffTrained = PlannedTraining::distinct('staff_id')->count('staff_id')
            + UnplannedTraining::distinct('staff_id')->count('staff_id');

        $plannedCompleted = PlannedTraining::where('status', 'Completed')->count();
        $unplannedCompleted = UnplannedTraining::where('status', 'Completed')->count();
        $totalCompleted = $plannedCompleted + $unplannedCompleted;
        $completionRate = $totalTrainings > 0 ? round(($totalCompleted / $totalTrainings) * 100) : 0;

        $statusLabels = ['Planned', 'Ongoing', 'Completed', 'Cancelled'];
        $statusData = [];
        foreach ($statusLabels as $status) {
            $statusData[$status] =
                PlannedTraining::where('status', $status)->count()
                + UnplannedTraining::where('status', $status)->count();
        }

        $categories = TrainingCategory::withCount(['plannedTraining', 'unplannedTraining'])->get()->map(function ($cat) {
            return (object) [
                'name' => $cat->name,
                'total' => $cat->planned_training_count + $cat->unplanned_training_count,
            ];
        })->sortByDesc('total')->values();

        $recentPlanned = PlannedTraining::with(['staff', 'department'])
            ->latest()->take(5)->get();
        $recentUnplanned = UnplannedTraining::with(['staff', 'department'])
            ->latest()->take(5)->get();

        return view('report.index', compact(
            'totalTrainings', 'totalCost', 'staffTrained', 'completionRate',
            'statusData', 'categories', 'recentPlanned', 'recentUnplanned'
        ));
    }

    public function trainingSummary(Request $request)
    {
        $query = $this->unionQuery();

        if ($request->filled('financial_year_id')) {
            $query->where('financial_year_id', $request->financial_year_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('training_category_id')) {
            $query->where('training_category_id', $request->training_category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $totalCost = (clone $query)->sum('cost');
        $total = $query->count();

        $trainings = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $departments = Department::orderBy('name')->get();
        $categories = TrainingCategory::orderBy('name')->get();

        $summary = (object) [
            'total' => $total,
            'totalCost' => $totalCost,
            'avgCost' => $total > 0 ? $totalCost / $total : 0,
        ];

        return view('report.training-summary', compact(
            'trainings', 'financialYears', 'departments', 'categories', 'summary'
        ));
    }

    public function departmentReport(Request $request)
    {
        $departments = Department::withCount([
            'plannedTraining', 'unplannedTraining',
        ])->orderBy('name')->get()->map(function ($dept) {
            $plannedCost = PlannedTraining::where('department_id', $dept->id)->sum('cost');
            $unplannedCost = UnplannedTraining::where('department_id', $dept->id)->sum('cost');
            $plannedCompleted = PlannedTraining::where('department_id', $dept->id)->where('status', 'Completed')->count();
            $unplannedCompleted = UnplannedTraining::where('department_id', $dept->id)->where('status', 'Completed')->count();
            $total = $dept->planned_training_count + $dept->unplanned_training_count;

            return (object) [
                'id' => $dept->id,
                'name' => $dept->name,
                'total_trainings' => $total,
                'completed' => $plannedCompleted + $unplannedCompleted,
                'total_cost' => $plannedCost + $unplannedCost,
                'staff_count' => $dept->staff->count(),
            ];
        });

        $totalAll = $departments->sum('total_trainings');
        $costAll = $departments->sum('total_cost');

        return view('report.department', compact('departments', 'totalAll', 'costAll'));
    }

    public function staffReport(Request $request)
    {
        $staffId = $request->staff_id;

        $staffList = Staff::with('department')->orderBy('first_name')->get();

        $staffData = null;
        if ($staffId) {
            $staffMember = Staff::with('department')->findOrFail($staffId);
            $planned = PlannedTraining::with([
                'financialYear', 'trainingCategory', 'trainingInstitution', 'fundingSource',
            ])->where('staff_id', $staffId)->orderBy('start_date', 'desc')->get();
            $unplanned = UnplannedTraining::with([
                'financialYear', 'trainingCategory', 'trainingInstitution', 'fundingSource',
            ])->where('staff_id', $staffId)->orderBy('start_date', 'desc')->get();
            $allTrainings = collect($planned)->merge($unplanned)->sortByDesc('start_date');

            $staffData = (object) [
                'staff' => $staffMember,
                'planned_count' => $planned->count(),
                'unplanned_count' => $unplanned->count(),
                'total_trainings' => $allTrainings->count(),
                'total_cost' => $planned->sum('cost') + $unplanned->sum('cost'),
                'trainings' => $allTrainings,
            ];
        }

        return view('report.staff', compact('staffList', 'staffData', 'staffId'));
    }

    public function financialReport(Request $request)
    {
        $years = FinancialYear::orderBy('year_name', 'desc')->get();

        $yearData = $years->map(function ($year) {
            $planned = PlannedTraining::where('financial_year_id', $year->id);
            $unplanned = UnplannedTraining::where('financial_year_id', $year->id);

            return (object) [
                'id' => $year->id,
                'year_name' => $year->year_name,
                'planned_count' => $planned->count(),
                'unplanned_count' => $unplanned->count(),
                'planned_cost' => $planned->sum('cost'),
                'unplanned_cost' => $unplanned->sum('cost'),
                'completed' => (clone $planned)->where('status', 'Completed')->count()
                    + (clone $unplanned)->where('status', 'Completed')->count(),
            ];
        });

        return view('report.financial', compact('yearData'));
    }

    public function costReport(Request $request)
    {
        $financialYearId = $request->financial_year_id;

        $plannedQuery = PlannedTraining::query();
        $unplannedQuery = UnplannedTraining::query();

        if ($financialYearId) {
            $plannedQuery->where('financial_year_id', $financialYearId);
            $unplannedQuery->where('financial_year_id', $financialYearId);
        }

        $totalPlannedCost = (clone $plannedQuery)->sum('cost');
        $totalUnplannedCost = (clone $unplannedQuery)->sum('cost');
        $totalCost = $totalPlannedCost + $totalUnplannedCost;
        $avgCost = $totalCost > 0
            ? $totalCost / ((clone $plannedQuery)->count() + (clone $unplannedQuery)->count())
            : 0;
        $maxCost = max(
            (clone $plannedQuery)->max('cost') ?? 0,
            (clone $unplannedQuery)->max('cost') ?? 0
        );

        $fundingSources = FundingSource::orderBy('name')->get()->map(function ($fs) use ($financialYearId) {
            $plannedQ = PlannedTraining::where('funding_source_id', $fs->id);
            $unplannedQ = UnplannedTraining::where('funding_source_id', $fs->id);
            if ($financialYearId) {
                $plannedQ->where('financial_year_id', $financialYearId);
                $unplannedQ->where('financial_year_id', $financialYearId);
            }
            return (object) [
                'name' => $fs->name,
                'cost' => (clone $plannedQ)->sum('cost') + (clone $unplannedQ)->sum('cost'),
                'count' => (clone $plannedQ)->count() + (clone $unplannedQ)->count(),
            ];
        })->filter(fn($f) => $f->cost > 0)->values();

        $categoryCosts = TrainingCategory::orderBy('name')->get()->map(function ($cat) use ($financialYearId) {
            $plannedQ = PlannedTraining::where('training_category_id', $cat->id);
            $unplannedQ = UnplannedTraining::where('training_category_id', $cat->id);
            if ($financialYearId) {
                $plannedQ->where('financial_year_id', $financialYearId);
                $unplannedQ->where('financial_year_id', $financialYearId);
            }
            return (object) [
                'name' => $cat->name,
                'cost' => (clone $plannedQ)->sum('cost') + (clone $unplannedQ)->sum('cost'),
                'count' => (clone $plannedQ)->count() + (clone $unplannedQ)->count(),
            ];
        })->filter(fn($c) => $c->cost > 0)->values();

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();

        return view('report.cost', compact(
            'totalCost', 'totalPlannedCost', 'totalUnplannedCost',
            'avgCost', 'maxCost', 'fundingSources', 'categoryCosts',
            'financialYears', 'financialYearId'
        ));
    }

    public function statusReport(Request $request)
    {
        $statuses = ['Planned', 'Ongoing', 'Completed', 'Cancelled'];

        $data = [];
        foreach ($statuses as $status) {
            $plannedCount = PlannedTraining::where('status', $status)->count();
            $unplannedCount = UnplannedTraining::where('status', $status)->count();
            $plannedCost = PlannedTraining::where('status', $status)->sum('cost');
            $unplannedCost = UnplannedTraining::where('status', $status)->sum('cost');

            $data[$status] = (object) [
                'planned_count' => $plannedCount,
                'unplanned_count' => $unplannedCount,
                'total_count' => $plannedCount + $unplannedCount,
                'planned_cost' => $plannedCost,
                'unplanned_cost' => $unplannedCost,
                'total_cost' => $plannedCost + $unplannedCost,
            ];
        }

        $totalAll = collect($data)->sum('total_count');
        $completedCount = $data['Completed']->total_count;
        $completionRate = $totalAll > 0 ? round(($completedCount / $totalAll) * 100, 1) : 0;

        $inProgress = $data['Ongoing']->total_count;
        $pending = $data['Planned']->total_count;
        $cancelled = $data['Cancelled']->total_count;
        $totalCost = collect($data)->sum('total_cost');

        return view('report.status', compact(
            'data', 'totalAll', 'completedCount', 'completionRate',
            'inProgress', 'pending', 'cancelled', 'totalCost', 'statuses'
        ));
    }

    private function unionQuery()
    {
        $plannedQ = PlannedTraining::select(
            DB::raw("'Planned' as training_type"),
            'id', 'course_title', 'staff_id', 'department_id',
            'financial_year_id', 'training_category_id',
            'training_institution_id', 'funding_source_id',
            'start_date', 'end_date', 'venue', 'cost', 'status', 'source',
            'description', 'remarks', 'created_at', 'updated_at'
        );

        $unplannedQ = UnplannedTraining::select(
            DB::raw("'Unplanned' as training_type"),
            'id', 'course_title', 'staff_id', 'department_id',
            'financial_year_id', 'training_category_id',
            'training_institution_id', 'funding_source_id',
            'start_date', 'end_date', 'venue', 'cost', 'status', 'source',
            'description', 'remarks', 'created_at', 'updated_at'
        );

        $union = $plannedQ->union($unplannedQ);

        return DB::table(DB::raw("({$union->toSql()}) as trainings"))
            ->mergeBindings($union->getQuery())
            ->select(
                'training_type', 'id', 'course_title',
                'staff_id', 'department_id', 'financial_year_id',
                'training_category_id', 'training_institution_id', 'funding_source_id',
                'start_date', 'end_date', 'venue', 'cost', 'status', 'source',
                'description', 'remarks', 'created_at', 'updated_at'
            );
    }
}
