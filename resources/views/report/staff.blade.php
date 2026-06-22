@extends('adminlte::page')

@section('title', 'Staff Training Report')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-graduate mr-2"></i>Staff Training Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">By Staff</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user mr-1"></i> Select Staff Member</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <select name="staff_id" class="form-control select2" style="min-width: 350px;" onchange="this.form.submit()">
                        <option value="">— Select Staff —</option>
                        @foreach($staffList as $s)
                            <option value="{{ $s->id }}" {{ $staffId == $s->id ? 'selected' : '' }}>
                                {{ $s->full_name }} ({{ $s->check_number }}) — {{ $s->department?->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($staffId)
                    <a href="{{ route('reports.staff') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i>Clear</a>
                @endif
            </form>
        </div>
    </div>

    @if($staffData)
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $staffData->total_trainings }}</h3>
                        <p>Total Trainings</p>
                    </div>
                    <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $staffData->planned_count }}</h3>
                        <p>Planned</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $staffData->unplanned_count }}</h3>
                        <p>Unplanned</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($staffData->total_cost, 0) }}</h3>
                        <p>Total Cost (TZS)</p>
                    </div>
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Training History
                    <span class="badge badge-info ml-2">{{ $staffData->total_trainings }} records</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('reports.export', ['type' => 'staff', 'format' => 'xlsx', 'staff_id' => $staffId]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                    <a href="{{ route('reports.export', ['type' => 'staff', 'format' => 'pdf', 'staff_id' => $staffId]) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-default btn-sm"><i class="fas fa-print mr-1"></i> Print</button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Course Title</th>
                            <th>Financial Year</th>
                            <th>Category</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Cost (TZS)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffData->trainings as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-{{ $t->training_type ?? (get_class($t) === 'App\Models\PlannedTraining' ? 'info' : 'warning') }}">
                                        {{ $t->training_type ?? (get_class($t) === 'App\Models\PlannedTraining' ? 'Planned' : 'Unplanned') }}
                                    </span>
                                </td>
                                <td>{{ $t->course_title }}</td>
                                <td>{{ $t->financialYear?->year_name ?? '—' }}</td>
                                <td>{{ $t->trainingCategory?->name ?? '—' }}</td>
                                <td>{{ $t->start_date ? $t->start_date->format('d/m/Y') : '—' }}</td>
                                <td>{{ $t->end_date ? $t->end_date->format('d/m/Y') : '—' }}</td>
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
                                    No training records found for this staff member.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-address-card mr-1"></i> Staff Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>Name:</strong> {{ $staffData->staff->full_name }}</div>
                    <div class="col-md-3"><strong>Check No:</strong> {{ $staffData->staff->check_number }}</div>
                    <div class="col-md-3"><strong>Department:</strong> {{ $staffData->staff->department?->name ?? '—' }}</div>
                    <div class="col-md-3"><strong>Designation:</strong> {{ $staffData->staff->designation }}</div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('js')
<script>
$(function () {
    $('.select2').select2({ theme: 'bootstrap4' });
});
</script>
@endsection

@section('adminlte_css')
<style>
@media print {
    .main-header, .main-sidebar, .content-header .card-tools, .breadcrumb, nav { display: none !important; }
    .content-wrapper, .main-footer { margin-left: 0 !important; padding-top: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .card-header { padding: 10px 0 !important; }
    body { font-size: 11px; }
}
</style>
@endsection

