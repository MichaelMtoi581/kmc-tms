@extends('adminlte::page')

@section('title', 'Training Duration Report')

@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clock mr-2"></i>Training Duration Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Duration Report</li>
        </ol>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalShort }}</h3>
                    <p>Short Training</p>
                </div>
                <div class="icon"><i class="fas fa-hourglass-start"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalLong }}</h3>
                    <p>Long Training</p>
                </div>
                <div class="icon"><i class="fas fa-hourglass-end"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($costShort, 0) }}</h3>
                    <p>Short Training Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($costLong, 0) }}</h3>
                    <p>Long Training Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filters</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <select name="financial_year_id" class="form-control form-control-sm">
                        <option value="">All Financial Years</option>
                        @foreach($financialYears as $year)
                            <option value="{{ $year->id }}" {{ request('financial_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="department_id" class="form-control form-control-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="duration_type" class="form-control form-control-sm">
                        <option value="">All Durations</option>
                        <option value="Short" {{ request('duration_type') == 'Short' ? 'selected' : '' }}>Short</option>
                        <option value="Long" {{ request('duration_type') == 'Long' ? 'selected' : '' }}>Long</option>
                    </select>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i>Filter</button>
                    <a href="{{ route('reports.duration') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i>Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i> Training Records
                <span class="badge badge-info ml-2">{{ $trainings->total() }} total</span>
            </h3>
            <div class="card-tools">
                <a href="{{ route('reports.export', ['type' => 'duration', 'format' => 'xlsx'] + request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('reports.export', ['type' => 'duration', 'format' => 'pdf'] + request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <button onclick="window.print()" class="btn btn-default btn-sm">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="duration-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Course Title</th>
                        <th>Staff</th>
                        <th>Department</th>
                        <th>Financial Year</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Cost (TZS)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainings as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge badge-{{ $t->training_type === 'Planned' ? 'info' : 'warning' }}">
                                    {{ $t->training_type }}
                                </span>
                            </td>
                            <td>{{ $t->course_title }}</td>
                            <td>{{ optional(\App\Models\Staff::find($t->staff_id))->full_name ?? '—' }}</td>
                            <td>{{ optional(\App\Models\Department::find($t->department_id))->name ?? '—' }}</td>
                            <td>{{ optional(\App\Models\FinancialYear::find($t->financial_year_id))->year_name ?? '—' }}</td>
                            <td>{{ optional(\App\Models\TrainingCategory::find($t->training_category_id))->name ?? '—' }}</td>
                            <td>
                                <span class="badge badge-{{ $t->duration_type === 'Long' ? 'danger' : 'success' }}">
                                    {{ $t->duration_type }}
                                </span>
                            </td>
                            <td class="text-right">{{ number_format($t->cost, 0) }}</td>
                            <td>
                                @php
                                    $badge = match($t->status) {
                                        'Planned' => 'primary',
                                        'Ongoing' => 'warning',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $t->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No training records match the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $trainings->links() }}
            </div>
        </div>
    </div>

@stop

@section('adminlte_css')
<style>
@media print {
    .main-header, .main-sidebar, .content-header, .card-header .card-tools, .breadcrumb, nav { display: none !important; }
    .content-wrapper, .main-footer { margin-left: 0 !important; padding-top: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .card-header { padding: 10px 0 !important; }
    body { font-size: 11px; }
    table { width: 100% !important; }
}
</style>
@stop

@section('js')
<script>
$(function () {
    $('#duration-table').DataTable({
        paging: false,
        info: false,
        order: [[0, 'desc']],
        language: { search: '', searchPlaceholder: 'Search records...' }
    });
});
</script>
@stop
