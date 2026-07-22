@extends('adminlte::page')
@section('title', 'TNA Exercise Details')
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-file-alt mr-2"></i>{{ $exercise->title }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tna.exercises.index') }}">TNA Exercises</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-{{ $exercise->isOpen() ? 'success' : 'secondary' }}">
                <div class="inner">
                    <h3>{{ $exercise->status }}</h3>
                    <p>Status</p>
                </div>
                <div class="icon"><i class="fas fa-toggle-on"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $exercise->responses->count() }}</h3>
                    <p>Total Responses</p>
                </div>
                <div class="icon"><i class="fas fa-list-alt"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $exercise->responses->pluck('training_needed')->unique()->count() }}</h3>
                    <p>Unique Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $exercise->responses->pluck('department')->filter()->unique()->count() }}</h3>
                    <p>Departments</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6"><strong>Financial Year:</strong> {{ $exercise->financialYear?->year_name ?? '—' }}</div>
                        <div class="col-sm-6"><strong>Created By:</strong> {{ $exercise->creator?->name ?? '—' }}</div>
                        <div class="col-sm-6 mt-2"><strong>Start Date:</strong> {{ $exercise->start_date?->format('d/m/Y') ?? '—' }}</div>
                        <div class="col-sm-6 mt-2"><strong>End Date:</strong> {{ $exercise->end_date?->format('d/m/Y') ?? '—' }}</div>
                        @if($exercise->description)
                            <div class="col-12 mt-2"><strong>Description:</strong> {{ $exercise->description }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Responses</h3>
            <div class="card-tools">
                <a href="{{ route('tna.responses.index', ['tna_exercise_id' => $exercise->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-external-link-alt mr-1"></i> View All Responses
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Check No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Training Needed</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exercise->responses->take(20) as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->check_number }}</td>
                            <td>{{ $r->full_name }}</td>
                            <td>{{ $r->department ?? '—' }}</td>
                            <td>{{ $r->training_needed }}</td>
                            <td>
                                @php
                                    $badge = match($r->priority) {
                                        'High' => 'danger',
                                        'Medium' => 'warning',
                                        'Low' => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $r->priority }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No responses yet. Import data from the Import TNA page.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($exercise->responses->count() > 20)
                <div class="text-center py-2">
                    <a href="{{ route('tna.responses.index', ['tna_exercise_id' => $exercise->id]) }}" class="text-primary">
                        Showing 20 of {{ $exercise->responses->count() }} responses &mdash; View All
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
