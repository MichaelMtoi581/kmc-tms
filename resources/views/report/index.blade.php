@extends('adminlte::page')

@section('title', 'Reports Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-pie mr-2"></i>Reports Dashboard</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTrainings }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                <a href="{{ route('reports.training-summary') }}" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalCost, 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <a href="{{ route('reports.cost') }}" class="small-box-footer">Cost Analysis <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $staffTrained }}</h3>
                    <p>Staff Trained</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('reports.staff') }}" class="small-box-footer">Staff Report <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $completionRate }}%</h3>
                    <p>Completion Rate</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
                <a href="{{ route('reports.status') }}" class="small-box-footer">Status Report <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Training Status Overview</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-donut mr-1"></i> By Category</h3>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Recent Planned Trainings</h3>
                    <div class="card-tools">
                        <a href="{{ route('planned-trainings.index') }}" class="btn btn-tool">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Staff</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPlanned as $t)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($t->course_title, 30) }}</td>
                                    <td>{{ $t->staff?->full_name ?? '—' }}</td>
                                    <td>{{ $t->department?->name ?? '—' }}</td>
                                    <td><span class="badge badge-{{ $t->status === 'Completed' ? 'success' : ($t->status === 'Ongoing' ? 'warning' : ($t->status === 'Cancelled' ? 'danger' : 'primary')) }}">{{ $t->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Recent Unplanned Trainings</h3>
                    <div class="card-tools">
                        <a href="{{ route('unplanned-trainings.index') }}" class="btn btn-tool">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Staff</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUnplanned as $t)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($t->course_title, 30) }}</td>
                                    <td>{{ $t->staff?->full_name ?? '—' }}</td>
                                    <td>{{ $t->department?->name ?? '—' }}</td>
                                    <td><span class="badge badge-{{ $t->status === 'Completed' ? 'success' : ($t->status === 'Ongoing' ? 'warning' : ($t->status === 'Cancelled' ? 'danger' : 'primary')) }}">{{ $t->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-link mr-1"></i> Quick Reports</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.training-summary') }}" class="btn btn-app btn-block bg-info">
                                <i class="fas fa-list"></i> Training Summary
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.department') }}" class="btn btn-app btn-block bg-success">
                                <i class="fas fa-building"></i> By Department
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.staff') }}" class="btn btn-app btn-block bg-warning">
                                <i class="fas fa-user"></i> By Staff
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.financial') }}" class="btn btn-app btn-block bg-primary">
                                <i class="fas fa-calendar"></i> Financial Year
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.cost') }}" class="btn btn-app btn-block bg-danger">
                                <i class="fas fa-coins"></i> Cost Analysis
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('reports.status') }}" class="btn btn-app btn-block bg-secondary">
                                <i class="fas fa-check-circle"></i> Status Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script>
$(function () {
    new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($statusData)),
            datasets: [{
                label: 'Trainings',
                data: @json(array_values($statusData)),
                backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545'],
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

    var catLabels = @json($categories->pluck('name'));
    var catData = @json($categories->pluck('total'));

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545',
                    '#17a2b8', '#6f42c1', '#fd7e14', '#20c997',
                    '#e83e8c', '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 8, font: { size: 10 } }
                }
            }
        }
    });
});
</script>
@endsection
