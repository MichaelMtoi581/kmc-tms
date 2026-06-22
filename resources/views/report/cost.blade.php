@extends('adminlte::page')

@section('title', 'Training Cost Analysis')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-coins mr-2"></i>Training Cost Analysis</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Cost Analysis</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalCost, 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalPlannedCost, 0) }}</h3>
                    <p>Planned Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalUnplannedCost, 0) }}</h3>
                    <p>Unplanned Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($maxCost, 0) }}</h3>
                    <p>Highest Single Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-arrow-up"></i></div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter by Financial Year</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <select name="financial_year_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Financial Years</option>
                        @foreach($financialYears as $year)
                            <option value="{{ $year->id }}" {{ $financialYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($financialYearId)
                    <a href="{{ route('reports.cost') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i>Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-donut mr-1"></i> Cost by Funding Source</h3>
                </div>
                <div class="card-body">
                    <canvas id="fundingChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Cost by Category</h3>
                </div>
                <div class="card-body">
                    <canvas id="categoryCostChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Funding Source Breakdown</h3>
            <div class="card-tools">
                <a href="{{ route('reports.export', ['type' => 'cost', 'format' => 'xlsx'] + request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('reports.export', ['type' => 'cost', 'format' => 'pdf'] + request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <button onclick="window.print()" class="btn btn-default btn-sm">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Funding Source</th>
                                <th class="text-right">Trainings</th>
                                <th class="text-right">Total Cost (TZS)</th>
                                <th class="text-right">Avg Cost (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fundingSources as $fs)
                                <tr>
                                    <td>{{ $fs->name }}</td>
                                    <td class="text-right">{{ $fs->count }}</td>
                                    <td class="text-right">{{ number_format($fs->cost, 0) }}</td>
                                    <td class="text-right">{{ $fs->count > 0 ? number_format($fs->cost / $fs->count, 0) : 0 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">No funding source data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-1"></i> Category Cost Breakdown</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-right">Trainings</th>
                                <th class="text-right">Total Cost (TZS)</th>
                                <th class="text-right">Avg Cost (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryCosts as $cc)
                                <tr>
                                    <td>{{ $cc->name }}</td>
                                    <td class="text-right">{{ $cc->count }}</td>
                                    <td class="text-right">{{ number_format($cc->cost, 0) }}</td>
                                    <td class="text-right">{{ $cc->count > 0 ? number_format($cc->cost / $cc->count, 0) : 0 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">No category data</td></tr>
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
    var fundLabels = @json($fundingSources->pluck('name'));
    var fundData = @json($fundingSources->pluck('cost'));

    new Chart(document.getElementById('fundingChart'), {
        type: 'doughnut',
        data: {
            labels: fundLabels,
            datasets: [{
                data: fundData,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545',
                    '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
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

    var catLabels = @json($categoryCosts->pluck('name'));
    var catData = @json($categoryCosts->pluck('cost'));

    new Chart(document.getElementById('categoryCostChart'), {
        type: 'bar',
        data: {
            labels: catLabels,
            datasets: [{
                label: 'Cost (TZS)',
                data: catData,
                backgroundColor: '#007bff',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: function(v) { return v.toLocaleString(); } } },
                x: { grid: { display: false } }
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
