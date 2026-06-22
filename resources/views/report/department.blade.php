@extends('adminlte::page')

@section('title', 'Department Training Report')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-building mr-2"></i>Department Training Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">By Department</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $departments->count() }}</h3>
                    <p>Departments</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalAll }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($costAll, 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Trainings by Department</h3>
                </div>
                <div class="card-body">
                    <canvas id="deptChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-donut mr-1"></i> Cost Distribution</h3>
                </div>
                <div class="card-body">
                    <canvas id="costChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Department Breakdown</h3>
            <div class="card-tools">
                <a href="{{ route('reports.export', ['type' => 'department', 'format' => 'xlsx']) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('reports.export', ['type' => 'department', 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <button onclick="window.print()" class="btn btn-default btn-sm">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="dept-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Staff Count</th>
                        <th>Total Trainings</th>
                        <th>Completed</th>
                        <th>Completion Rate</th>
                        <th>Total Cost (TZS)</th>
                        <th>Avg Cost/Training</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>{{ $dept->staff_count }}</td>
                            <td>{{ $dept->total_trainings }}</td>
                            <td>{{ $dept->completed }}</td>
                            <td>
                                @php
                                    $rate = $dept->total_trainings > 0 ? round(($dept->completed / $dept->total_trainings) * 100) : 0;
                                @endphp
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-success" style="width: {{ $rate }}%"></div>
                                </div>
                                <small>{{ $rate }}%</small>
                            </td>
                            <td class="text-right">{{ number_format($dept->total_cost, 0) }}</td>
                            <td class="text-right">{{ $dept->total_trainings > 0 ? number_format($dept->total_cost / $dept->total_trainings, 0) : 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No departments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script>
$(function () {
    var labels = @json($departments->pluck('name'));
    var trainings = @json($departments->pluck('total_trainings'));
    var costs = @json($departments->pluck('total_cost'));

    new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Trainings',
                data: trainings,
                backgroundColor: '#007bff',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('costChart'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: costs,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545',
                    '#17a2b8', '#6f42c1', '#fd7e14', '#20c997',
                    '#e83e8c', '#6610f2', '#343a40', '#f012be'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 8, font: { size: 9 } }
                }
            }
        }
    });

    $('#dept-table').DataTable({
        paging: false,
        info: false,
        order: [[3, 'desc']],
        language: { search: '', searchPlaceholder: 'Search departments...' }
    });
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
