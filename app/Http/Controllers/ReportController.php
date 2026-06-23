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
use App\Exports\ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Build training-summary rows for a (already filtered) union query,
     * resolving staff/department/year/category names via pre-loaded maps
     * instead of running 4 queries per row.
     */
    private function summaryRows($query): array
    {
        $trainings = $query->orderBy('created_at', 'desc')->get();

        $staffMap = Staff::all()->keyBy('id');
        $deptMap = Department::all()->keyBy('id');
        $yearMap = FinancialYear::all()->keyBy('id');
        $catMap = TrainingCategory::all()->keyBy('id');

        return $trainings->map(fn ($t) => [
            $t->training_type,
            $t->course_title,
            $staffMap->get($t->staff_id)?->full_name ?? '—',
            $deptMap->get($t->department_id)?->name ?? '—',
            $yearMap->get($t->financial_year_id)?->year_name ?? '—',
            $catMap->get($t->training_category_id)?->name ?? '—',
            $t->duration_type,
            $t->cost,
            $t->status,
        ])->toArray();
    }

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
        if ($request->filled('duration_type')) {
            $query->where('duration_type', $request->duration_type);
        }

        if ($request->input('format') === 'xlsx') {
            $rows = $this->summaryRows(clone $query);
            return $this->exportExcel($rows, ['Type', 'Course Title', 'Staff', 'Department', 'Financial Year', 'Category', 'Cost (TZS)', 'Status'], 'training-summary');
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

    public function durationReport(Request $request)
    {
        $query = $this->unionQuery();

        if ($request->filled('financial_year_id')) {
            $query->where('financial_year_id', $request->financial_year_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('duration_type')) {
            $query->where('duration_type', $request->duration_type);
        }

        $totalShort = (clone $query)->where('duration_type', 'Short')->count();
        $totalLong = (clone $query)->where('duration_type', 'Long')->count();
        $costShort = (clone $query)->where('duration_type', 'Short')->sum('cost');
        $costLong = (clone $query)->where('duration_type', 'Long')->sum('cost');

        $trainings = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $financialYears = FinancialYear::orderBy('year_name', 'desc')->get();
        $departments = Department::orderBy('name')->get();

        return view('report.duration', compact(
            'trainings', 'financialYears', 'departments',
            'totalShort', 'totalLong', 'costShort', 'costLong'
        ));
    }

    public function export($type, Request $request)
    {
        $request->validate(['format' => 'required|in:xlsx,pdf']);

        $titles = [
            'summary' => 'Training Summary Report',
            'department' => 'Department Training Report',
            'staff' => 'Staff Training Report',
            'financial' => 'Financial Year Report',
            'cost' => 'Training Cost Analysis',
            'status' => 'Training Status Report',
            'duration' => 'Training Duration Report',
        ];

        $data = match ($type) {
            'summary' => $this->exportSummaryData($request),
            'department' => $this->exportDepartmentData(),
            'staff' => $this->exportStaffData($request),
            'financial' => $this->exportFinancialData(),
            'cost' => $this->exportCostData($request),
            'status' => $this->exportStatusData(),
            'duration' => $this->exportDurationData($request),
            default => abort(404),
        };

        if ($request->input('format') === 'pdf') {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('report.export-pdf', [
                'title' => $titles[$type],
                'subtitle' => $data['subtitle'] ?? null,
                'headings' => $data['headings'],
                'rows' => $data['rows'],
            ]);
            return $pdf->download(str_replace('.xlsx', '.pdf', $data['filename']));
        }

        return Excel::download(
            new ReportExport(collect($data['rows']), $data['headings']),
            $data['filename']
        );
    }

    private function exportExcel($rows, $headings, $name)
    {
        return Excel::download(
            new ReportExport(collect($rows), $headings),
            "{$name}-" . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private function exportSummaryData(Request $request): array
    {
        $query = $this->unionQuery();
        if ($request->filled('financial_year_id')) $query->where('financial_year_id', $request->financial_year_id);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);
        if ($request->filled('training_category_id')) $query->where('training_category_id', $request->training_category_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('source')) $query->where('source', $request->source);

        $rows = $this->summaryRows(clone $query);

        $filters = collect(['financial_year_id', 'department_id', 'training_category_id', 'status', 'source', 'duration_type'])
            ->filter(fn($f) => $request->filled($f))
            ->map(fn($f) => ucwords(str_replace('_', ' ', $f)) . ': ' . $request->$f)
            ->implode(' | ');

        return [
            'headings' => ['Type', 'Course Title', 'Staff', 'Department', 'Financial Year', 'Category', 'Duration', 'Cost (TZS)', 'Status'],
            'rows' => $rows,
            'filename' => 'training-summary-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => $filters ?: null,
        ];
    }

    private function exportDepartmentData(): array
    {
        $departments = Department::withCount(['plannedTraining', 'unplannedTraining'])->orderBy('name')->get();
        $rows = $departments->map(function ($dept) {
            $plannedCost = PlannedTraining::where('department_id', $dept->id)->sum('cost');
            $unplannedCost = UnplannedTraining::where('department_id', $dept->id)->sum('cost');
            $totalCost = $plannedCost + $unplannedCost;
            $total = $dept->planned_training_count + $dept->unplanned_training_count;
            $completed = PlannedTraining::where('department_id', $dept->id)->where('status', 'Completed')->count()
                + UnplannedTraining::where('department_id', $dept->id)->where('status', 'Completed')->count();
            $rate = $total > 0 ? round(($completed / $total) * 100) . '%' : '0%';
            return [
                $dept->name,
                $dept->staff->count(),
                $total,
                $completed,
                $rate,
                number_format($totalCost, 0),
                $total > 0 ? number_format($totalCost / $total, 0) : '0',
            ];
        })->toArray();

        return [
            'headings' => ['Department', 'Staff Count', 'Total Trainings', 'Completed', 'Rate', 'Total Cost (TZS)', 'Avg Cost/Training'],
            'rows' => $rows,
            'filename' => 'department-report-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => 'All departments overview',
        ];
    }

    private function exportStaffData(Request $request): array
    {
        $staffId = $request->staff_id;
        if (!$staffId) return ['headings' => [], 'rows' => [], 'filename' => 'empty.xlsx'];

        $planned = PlannedTraining::where('staff_id', $staffId)->get();
        $unplanned = UnplannedTraining::where('staff_id', $staffId)->get();
        $all = collect($planned)->merge($unplanned)->sortByDesc('start_date');

        $rows = $all->map(fn($t) => [
            $t->training_type ?? (get_class($t) === PlannedTraining::class ? 'Planned' : 'Unplanned'),
            $t->course_title,
            $t->financialYear?->year_name ?? '—',
            $t->trainingCategory?->name ?? '—',
            $t->start_date ? $t->start_date->format('d/m/Y') : '—',
            $t->end_date ? $t->end_date->format('d/m/Y') : '—',
            $t->cost,
            $t->status,
        ])->toArray();

        $staff = Staff::find($staffId);
        return [
            'headings' => ['Type', 'Course Title', 'Financial Year', 'Category', 'Start Date', 'End Date', 'Cost (TZS)', 'Status'],
            'rows' => $rows,
            'filename' => 'staff-report-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => $staff ? 'Staff: ' . $staff->full_name . ' (' . $staff->check_number . ')' : null,
        ];
    }

    private function exportFinancialData(): array
    {
        $years = FinancialYear::orderBy('year_name', 'desc')->get();
        $rows = $years->map(function ($year) {
            $planned = PlannedTraining::where('financial_year_id', $year->id);
            $unplanned = UnplannedTraining::where('financial_year_id', $year->id);
            $total = $planned->count() + $unplanned->count();
            $completed = (clone $planned)->where('status', 'Completed')->count()
                + (clone $unplanned)->where('status', 'Completed')->count();
            $rate = $total > 0 ? round(($completed / $total) * 100) . '%' : '0%';
            return [
                $year->year_name,
                $planned->count(),
                $unplanned->count(),
                $total,
                $completed,
                $rate,
                $planned->sum('cost'),
                $unplanned->sum('cost'),
                $planned->sum('cost') + $unplanned->sum('cost'),
            ];
        })->toArray();

        return [
            'headings' => ['Financial Year', 'Planned', 'Unplanned', 'Total', 'Completed', 'Rate', 'Planned Cost', 'Unplanned Cost', 'Total Cost'],
            'rows' => $rows,
            'filename' => 'financial-report-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => 'Year-by-year training comparison',
        ];
    }

    private function exportCostData(Request $request): array
    {
        $fyId = $request->financial_year_id;
        $plannedQ = PlannedTraining::query();
        $unplannedQ = UnplannedTraining::query();
        if ($fyId) { $plannedQ->where('financial_year_id', $fyId); $unplannedQ->where('financial_year_id', $fyId); }

        $fundingSources = FundingSource::orderBy('name')->get()->map(function ($fs) use ($fyId) {
            $pq = PlannedTraining::where('funding_source_id', $fs->id);
            $uq = UnplannedTraining::where('funding_source_id', $fs->id);
            if ($fyId) { $pq->where('financial_year_id', $fyId); $uq->where('financial_year_id', $fyId); }
            return ['name' => $fs->name, 'cost' => $pq->sum('cost') + $uq->sum('cost'), 'count' => $pq->count() + $uq->count()];
        })->filter(fn($f) => $f['cost'] > 0)->values();

        $rows = $fundingSources->map(fn($fs) => [
            $fs['name'],
            $fs['count'],
            number_format($fs['cost'], 0),
            $fs['count'] > 0 ? number_format($fs['cost'] / $fs['count'], 0) : '0',
        ])->toArray();

        $subtitle = $fyId ? 'Filtered by financial year' : 'All financial years';
        return [
            'headings' => ['Funding Source', 'Trainings', 'Total Cost (TZS)', 'Avg Cost (TZS)'],
            'rows' => $rows,
            'filename' => 'cost-report-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => $subtitle,
        ];
    }

    private function exportDurationData(Request $request): array
    {
        $query = $this->unionQuery();
        if ($request->filled('financial_year_id')) $query->where('financial_year_id', $request->financial_year_id);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);
        if ($request->filled('duration_type')) $query->where('duration_type', $request->duration_type);

        $rows = $this->summaryRows(clone $query);

        return [
            'headings' => ['Type', 'Course Title', 'Staff', 'Department', 'Financial Year', 'Category', 'Duration', 'Cost (TZS)', 'Status'],
            'rows' => $rows,
            'filename' => 'training-duration-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => 'Training Duration Report',
        ];
    }

    private function exportStatusData(): array
    {
        $statuses = ['Planned', 'Ongoing', 'Completed', 'Cancelled'];
        $rows = [];
        foreach ($statuses as $status) {
            $pc = PlannedTraining::where('status', $status)->count();
            $uc = UnplannedTraining::where('status', $status)->count();
            $ps = PlannedTraining::where('status', $status)->sum('cost');
            $us = UnplannedTraining::where('status', $status)->sum('cost');
            $rows[] = [$status, $pc, $uc, $pc + $uc, $ps, $us, $ps + $us];
        }
        $allP = collect($rows)->sum(fn($r) => $r[1]);
        $allU = collect($rows)->sum(fn($r) => $r[2]);
        $allC = collect($rows)->sum(fn($r) => $r[3]);
        $allPs = collect($rows)->sum(fn($r) => $r[4]);
        $allUs = collect($rows)->sum(fn($r) => $r[5]);
        $allCs = collect($rows)->sum(fn($r) => $r[6]);
        $rows[] = ['TOTAL', $allP, $allU, $allC, $allPs, $allUs, $allCs];

        return [
            'headings' => ['Status', 'Planned Count', 'Unplanned Count', 'Total', 'Planned Cost', 'Unplanned Cost', 'Total Cost'],
            'rows' => $rows,
            'filename' => 'status-report-' . now()->format('Y-m-d') . '.xlsx',
            'subtitle' => 'Training status distribution',
        ];
    }

    /** @return \Illuminate\Database\Query\Builder */
    private function unionQuery()
    {
        $plannedQ = PlannedTraining::select(
            DB::raw("'Planned' as training_type"),
            'id', 'course_title', 'staff_id', 'department_id',
            'financial_year_id', 'training_category_id',
            'training_institution_id', 'funding_source_id',
            'start_date', 'end_date', 'venue', 'cost', 'status', 'duration_type', 'source',
            'description', 'remarks', 'created_at', 'updated_at'
        );

        $unplannedQ = UnplannedTraining::select(
            DB::raw("'Unplanned' as training_type"),
            'id', 'course_title', 'staff_id', 'department_id',
            'financial_year_id', 'training_category_id',
            'training_institution_id', 'funding_source_id',
            'start_date', 'end_date', 'venue', 'cost', 'status', 'duration_type', 'source',
            'description', 'remarks', 'created_at', 'updated_at'
        );

        $union = $plannedQ->union($unplannedQ);

        return DB::table(DB::raw("({$union->toSql()}) as trainings"))
            ->mergeBindings($union->getQuery())
            ->select(
                'training_type', 'id', 'course_title',
                'staff_id', 'department_id', 'financial_year_id',
                'training_category_id', 'training_institution_id', 'funding_source_id',
                'start_date', 'end_date', 'venue', 'cost', 'status', 'duration_type', 'source',
                'description', 'remarks', 'created_at', 'updated_at'
            );
    }
}
