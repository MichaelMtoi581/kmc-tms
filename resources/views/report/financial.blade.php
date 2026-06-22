@extends('adminlte::page')

@section('title', 'Financial Year Report')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-calendar-alt mr-2"></i>Financial Year Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Financial Year</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Trainings per Financial Year</h3>
                </div>
                <div class="card-body">
                    <canvas id="fyTrainingsChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Cost per Financial Year</h3>
                </div>
                <div class="card-body">
                    <canvas id="fyCostChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Year-by-Year Breakdown</h3>
        </div>
        <div class="card-body">
            <table id="fy-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Financial Year</th>
                        <th>Planned Trainings</th>
                        <th>Unplanned Trainings</th>
                        <th>Total Trainings</th>
                        <th>Completed</th>
                        <th>Completion Rate</th>
                        <th>Planned Cost (TZS)</th>
                        <th>Unplanned Cost (TZS)</th>
                        <th>Total Cost (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($yearData as $year)
                        @php
                            $total = $year->planned_count + $year->unplanned_count;
                            $totalCost = $year->planned_cost + $year->unplanned_cost;
                            $rate = $total > 0 ? round(($year->completed / $total) * 100) : 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $year->year_name }}</td>
                            <td>{{ $year->planned_count }}</td>
                            <td>{{ $year->unplanned_count }}</td>
                            <td><strong>{{ $total }}</strong></td>
                            <td>{{ $year->completed }}</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-success" style="width: {{ $rate }}%"></div>
                                </div>
                                <small>{{ $rate }}%</small>
                            </td>
                            <td class="text-right">{{ number_format($year->planned_cost, 0) }}</td>
                            <td class="text-right">{{ number_format($year->unplanned_cost, 0) }}</td>
                            <td class="text-right"><strong>{{ number_format($totalCost, 0) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No financial years found.
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
    var labels = @json($yearData->pluck('year_name'));
    var planned = @json($yearData->pluck('planned_count'));
    var unplanned = @json($yearData->pluck('unplanned_count'));
    var plannedCost = @json($yearData->pluck('planned_cost'));
    var unplannedCost = @json($yearData->pluck('unplanned_cost'));

    new Chart(document.getElementById('fyTrainingsChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Planned',
                    data: planned,
                    backgroundColor: '#007bff',
                    borderWidth: 0
                },
                {
                    label: 'Unplanned',
                    data: unplanned,
                    backgroundColor: '#ffc107',
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('fyCostChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Planned Cost',
                    data: plannedCost,
                    backgroundColor: '#007bff',
                    borderWidth: 0
                },
                {
                    label: 'Unplanned Cost',
                    data: unplannedCost,
                    backgroundColor: '#ffc107',
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: function(v) { return v.toLocaleString(); } } },
                x: { grid: { display: false } }
            }
        }
    });

    $('#fy-table').DataTable({
        paging: false,
        info: false,
        order: [[0, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endsection
