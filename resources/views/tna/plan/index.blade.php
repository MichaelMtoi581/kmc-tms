@extends('adminlte::page')
@section('title', 'Generate Training Plan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-magic mr-2"></i>Generate Annual Training Plan</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Generate Plan</li>
        </ol>
    </div>
@endsection

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Select TNA Exercise</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <select name="tna_exercise_id" class="form-control" onchange="this.form.submit()" style="min-width:350px;">
                        <option value="">Select Closed TNA Exercise...</option>
                        @foreach($exercises as $e)
                            <option value="{{ $e->id }}" {{ $exerciseId == $e->id ? 'selected' : '' }}>
                                {{ $e->title }} ({{ $e->financialYear?->year_name }}) &mdash; {{ $e->responses->count() }} responses
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($exerciseId && $grouped->count() > 0)

        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table mr-1"></i> Proposed Training Plan ({{ $grouped->count() }} training areas)</h3>
                <div class="card-tools">
                    <form method="POST" action="{{ route('tna.plan.generate') }}" class="d-inline" onsubmit="return confirm('Generate planned trainings from this TNA?')">
                        @csrf
                        <input type="hidden" name="tna_exercise_id" value="{{ $exerciseId }}">
                        <div class="form-group d-inline-block mr-1 mb-0">
                            <select name="financial_year_id" class="form-control form-control-sm d-inline-block" style="width:160px;" required>
                                <option value="">FY</option>
                                @foreach($financialYears as $fy)
                                    <option value="{{ $fy->id }}">{{ $fy->year_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-magic mr-1"></i> Generate Plan
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Training Area</th>
                            <th>Participants</th>
                            <th>Departments</th>
                            <th>Avg Priority</th>
                            <th>Preferred Institution</th>
                            <th>Preferred Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grouped as $g)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $g->training_name }}</strong></td>
                                <td><span class="badge badge-info">{{ $g->participants_count }}</span></td>
                                <td>{{ implode(', ', $g->departments) ?: '—' }}</td>
                                <td>
                                    @php
                                        $prioBadge = match(true) {
                                            $g->avg_priority >= 2.5 => 'danger',
                                            $g->avg_priority >= 1.5 => 'warning',
                                            default => 'info',
                                        };
                                        $prioLabel = match(true) {
                                            $g->avg_priority >= 2.5 => 'High',
                                            $g->avg_priority >= 1.5 => 'Medium',
                                            default => 'Low',
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $prioBadge }}">{{ $prioLabel }}</span>
                                </td>
                                <td>{{ $g->preferred_institution ?? '—' }}</td>
                                <td>{{ $g->preferred_duration ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($exerciseId && $grouped->count() === 0)

        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            This exercise has no TNA responses yet. Import responses first.
        </div>

    @endif

    @if($existingPlans->count() > 0)

        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> Existing Planned Trainings ({{ $existingPlans->count() }})</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Title</th>
                            <th>Status</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($existingPlans as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('planned-trainings.show', $p) }}">{{ $p->course_title }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $p->status === 'Completed' ? 'success' : ($p->status === 'Ongoing' ? 'warning' : 'primary') }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td><span class="badge badge-info">{{ $p->participants->count() }}</span></td>
                                <td>
                                    <a href="{{ route('planned-trainings.edit', $p) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('tna.plan.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this TNA-generated training?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

@endsection
