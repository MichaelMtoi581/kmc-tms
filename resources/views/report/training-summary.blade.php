@extends('adminlte::page')

@section('title', 'Training Summary Report')

@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-list mr-2"></i>Training Summary Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Training Summary</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary->total }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($summary->totalCost, 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($summary->avgCost, 0) }}</h3>
                    <p>Average Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-calculator"></i></div>
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
                    <select name="training_category_id" class="form-control form-control-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('training_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="source" class="form-control form-control-sm">
                        <option value="">All Sources</option>
                        <option value="Planned" {{ request('source') == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="Unplanned" {{ request('source') == 'Unplanned' ? 'selected' : '' }}>Unplanned</option>
                        <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>Manual Import</option>
                    </select>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i>Filter</button>
                    <a href="{{ route('reports.training-summary') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i>Reset</a>
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
        </div>
        <div class="card-body">
            <table id="summary-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Course Title</th>
                        <th>Staff</th>
                        <th>Department</th>
                        <th>Financial Year</th>
                        <th>Category</th>
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
                            <td colspan="9" class="text-center text-muted py-4">
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

@endsection

@section('js')
<script>
$(function () {
    $('#summary-table').DataTable({
        paging: false,
        info: false,
        order: [[0, 'desc']],
        language: { search: '', searchPlaceholder: 'Search records...' }
    });
});
</script>
@endsection
