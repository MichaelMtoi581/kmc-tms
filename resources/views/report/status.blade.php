@extends('adminlte::page')

@section('title', 'Training Status Report')

@section('plugins.Chartjs', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-check-circle mr-2"></i>Training Status Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Status Report</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalAll }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $completedCount }}</h3>
                    <p>Completed</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $pending }}</h3>
                    <p>Planned</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $inProgress }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="icon"><i class="fas fa-spinner"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $cancelled }}</h3>
                    <p>Cancelled</p>
                </div>
                <div class="icon"><i class="fas fa-ban"></i></div>
            </div>
        </div>
        <div class="col-lg-6 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $completionRate }}%</h3>
                    <p>Overall Completion Rate</p>
                </div>
                <div class="icon"><i class="fas fa-percentage"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Status Comparison</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusDetailChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-donut mr-1"></i> Overall Distribution</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusPieChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Detailed Status Breakdown</h3>
            <div class="card-tools">
                <a href="{{ route('reports.export', ['type' => 'status', 'format' => 'xlsx']) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('reports.export', ['type' => 'status', 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <button onclick="window.print()" class="btn btn-default btn-sm">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-center">Planned Trainings</th>
                        <th class="text-center">Unplanned Trainings</th>
                        <th class="text-center">Total</th>
                        <th class="text-right">Planned Cost (TZS)</th>
                        <th class="text-right">Unplanned Cost (TZS)</th>
                        <th class="text-right">Total Cost (TZS)</th>
                        <th class="text-center">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statuses as $status)
                        @php
                            $row = $data[$status];
                            $pct = $totalAll > 0 ? round(($row->total_count / $totalAll) * 100, 1) : 0;
                            $badge = match($status) {
                                'Planned' => 'primary',
                                'Ongoing' => 'warning',
                                'Completed' => 'success',
                                'Cancelled' => 'danger',
                                default => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td><span class="badge badge-{{ $badge }}">{{ $status }}</span></td>
                            <td class="text-center">{{ $row->planned_count }}</td>
                            <td class="text-center">{{ $row->unplanned_count }}</td>
                            <td class="text-center"><strong>{{ $row->total_count }}</strong></td>
                            <td class="text-right">{{ number_format($row->planned_cost, 0) }}</td>
                            <td class="text-right">{{ number_format($row->unplanned_cost, 0) }}</td>
                            <td class="text-right"><strong>{{ number_format($row->total_cost, 0) }}</strong></td>
                            <td class="text-center">{{ $pct }}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold bg-gray-light">
                        <td>TOTAL</td>
                        <td class="text-center">{{ collect($data)->sum('planned_count') }}</td>
                        <td class="text-center">{{ collect($data)->sum('unplanned_count') }}</td>
                        <td class="text-center">{{ $totalAll }}</td>
                        <td class="text-right">{{ number_format(collect($data)->sum('planned_cost'), 0) }}</td>
                        <td class="text-right">{{ number_format(collect($data)->sum('unplanned_cost'), 0) }}</td>
                        <td class="text-right">{{ number_format($totalCost, 0) }}</td>
                        <td class="text-center">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script>
$(function () {
    var statuses = @json($statuses);
    var plannedCounts = @json(collect($data)->map(fn($d) => $d->planned_count)->values());
    var unplannedCounts = @json(collect($data)->map(fn($d) => $d->unplanned_count)->values());
    var totalCounts = @json(collect($data)->map(fn($d) => $d->total_count)->values());

    new Chart(document.getElementById('statusDetailChart'), {
        type: 'bar',
        data: {
            labels: statuses,
            datasets: [
                {
                    label: 'Planned Trainings',
                    data: plannedCounts,
                    backgroundColor: '#007bff',
                    borderWidth: 0
                },
                {
                    label: 'Unplanned Trainings',
                    data: unplannedCounts,
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

    new Chart(document.getElementById('statusPieChart'), {
        type: 'pie',
        data: {
            labels: statuses,
            datasets: [{
                data: totalCounts,
                backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 8, font: { size: 11 } }
                }
            }
        }
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
