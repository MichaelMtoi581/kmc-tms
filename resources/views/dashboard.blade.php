@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</h1>
        <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $staffCount }}</h3>
                    <p>Staff</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('staff.index') }}" class="small-box-footer">Manage Staff <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTrainings }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="{{ route('reports.index') }}" class="small-box-footer">View Reports <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $monthTrainings }}</h3>
                    <p>This Month</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-week"></i></div>
                <a href="{{ route('reports.training-summary') }}" class="small-box-footer">Training Summary <i class="fas fa-arrow-circle-right"></i></a>
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
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totalCost, 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <a href="{{ route('reports.cost') }}" class="small-box-footer">Cost Analysis <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $staffTrained }}</h3>
                    <p>Staff Trained</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <a href="{{ route('reports.staff') }}" class="small-box-footer">Staff Report <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $departmentCount }}</h3>
                    <p>Departments</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
                <a href="{{ route('departments.index') }}" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $upcoming->count() }}</h3>
                    <p>Upcoming Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <a href="{{ route('planned-trainings.index') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Upcoming Trainings</h3>
                    <div class="card-tools">
                        <a href="{{ route('planned-trainings.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Schedule
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Staff</th>
                                <th>Department</th>
                                <th>Start Date</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcoming as $t)
                                <tr>
                                    <td>
                                        <a href="{{ route('planned-trainings.show', $t->id) }}">{{ \Illuminate\Support\Str::limit($t->course_title, 28) }}</a>
                                    </td>
                                    <td>{{ $t->staff?->full_name ?? '—' }}</td>
                                    <td>{{ $t->department?->name ?? '—' }}</td>
                                    <td>{{ $t->start_date?->format('d M Y') ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $t->duration_type === 'Long' ? 'danger' : 'success' }}">
                                            {{ $t->duration_type }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">No upcoming trainings scheduled</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Training Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-donut mr-1"></i> By Category</h3>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-building mr-1"></i> Top Departments</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.department') }}" class="btn btn-tool">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Staff</th>
                                <th>Trainings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deptStats as $d)
                                <tr>
                                    <td>{{ $d->name }}</td>
                                    <td>{{ $d->staff_count }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $d->total }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Recent Planned</h3>
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
                                    <td>{{ \Illuminate\Support\Str::limit($t->course_title, 28) }}</td>
                                    <td>{{ $t->staff?->full_name ?? '—' }}</td>
                                    <td>{{ $t->department?->name ?? '—' }}</td>
                                    <td><span class="badge badge-{{ $t->status === 'Completed' ? 'success' : ($t->status === 'Ongoing' ? 'warning' : ($t->status === 'Cancelled' ? 'danger' : 'primary')) }}">{{ $t->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Recent Unplanned</h3>
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
                                    <td>{{ \Illuminate\Support\Str::limit($t->course_title, 28) }}</td>
                                    <td>{{ $t->staff?->full_name ?? '—' }}</td>
                                    <td>{{ $t->department?->name ?? '—' }}</td>
                                    <td><span class="badge badge-{{ $t->status === 'Completed' ? 'success' : ($t->status === 'Ongoing' ? 'warning' : ($t->status === 'Cancelled' ? 'danger' : 'primary')) }}">{{ $t->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

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

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: @json(session('success')),
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif
});
</script>
@stop
