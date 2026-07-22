@extends('adminlte::page')
@section('title', 'TNA Analysis')
@section('plugins.Chartjs', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-bar mr-2"></i>Training Needs Analysis</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">TNA Analysis</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter by Exercise</h3>
            <form method="GET" class="form-inline ml-3">
                <div class="form-group mr-2">
                    <select name="tna_exercise_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Exercises</option>
                        @foreach($exercises as $e)
                            <option value="{{ $e->id }}" {{ $exerciseId == $e->id ? 'selected' : '' }}>
                                {{ $e->title }} ({{ $e->financialYear?->year_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalResponses }}</h3>
                    <p>Total Responses</p>
                </div>
                <div class="icon"><i class="fas fa-list-alt"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $uniqueStaff }}</h3>
                    <p>Staff Participated</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $uniqueTrainings }}</h3>
                    <p>Unique Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $byDepartment->count() }}</h3>
                    <p>Departments</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Top Requested Training</h3>
                </div>
                <div class="card-body">
                    <canvas id="trainingChart" style="max-height:300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Priority Distribution</h3>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" style="max-height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-building mr-1"></i> Requests by Department</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Department</th><th class="text-right">Requests</th></tr></thead>
                        <tbody>
                            @forelse($byDepartment as $d)
                                <tr>
                                    <td>{{ $d->department ?? 'N/A' }}</td>
                                    <td class="text-right"><span class="badge badge-info">{{ $d->total }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-university mr-1"></i> Preferred Institutions</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Institution</th><th class="text-right">Requests</th></tr></thead>
                        <tbody>
                            @forelse($byInstitution as $i)
                                <tr>
                                    <td>{{ $i->preferred_institution }}</td>
                                    <td class="text-right"><span class="badge badge-primary">{{ $i->total }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script>
$(function () {
    new Chart(document.getElementById('trainingChart'), {
        type: 'bar',
        data: {
            labels: @json($topTrainings->pluck('training_needed')),
            datasets: [{
                label: 'Requests',
                data: @json($topTrainings->pluck('total')),
                backgroundColor: '#2c6faa',
                borderWidth: 0,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    var prioData = @json($byPriority->pluck('total', 'priority')->toArray());
    var prioColors = { 'High': '#dc3545', 'Medium': '#ffc107', 'Low': '#17a2b8' };

    new Chart(document.getElementById('priorityChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(prioData),
            datasets: [{
                data: Object.values(prioData),
                backgroundColor: Object.keys(prioData).map(k => prioColors[k] || '#666'),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });
});
</script>
@endsection
