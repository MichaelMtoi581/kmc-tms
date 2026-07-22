@extends('adminlte::page')
@section('title', 'TNA Response Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-eye mr-2"></i>TNA Response Details</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tna.responses.index', ['tna_exercise_id' => $response->tna_exercise_id]) }}">TNA Responses</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $response->full_name }} &mdash; {{ $response->training_needed }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Full Name</strong>
                            <p>{{ $response->full_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Check Number</strong>
                            <p>{{ $response->check_number ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Department</strong>
                            <p>{{ $response->department ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Designation</strong>
                            <p>{{ $response->designation ?? '—' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Training Needed</strong>
                            <p>{{ $response->training_needed }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Priority</strong>
                            <p>
                                @php
                                    $badge = match($response->priority) {
                                        'High' => 'danger', 'Medium' => 'warning', 'Low' => 'info', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $response->priority }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Preferred Duration</strong>
                            <p>{{ $response->preferred_duration ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Preferred Institution</strong>
                            <p>{{ $response->preferred_institution ?? '—' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <strong>Training Reason</strong>
                        <p>{{ $response->training_reason ?? '—' }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Expected Outcome</strong>
                        <p>{{ $response->expected_outcome ?? '—' }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Remarks</strong>
                        <p>{{ $response->remarks ?? '—' }}</p>
                    </div>

                    @if($response->exercise)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>TNA Exercise</strong>
                            <p>{{ $response->exercise->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Financial Year</strong>
                            <p>{{ $response->exercise->financialYear?->year_name ?? '—' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('tna.responses.edit', $response) }}" class="btn btn-warning"><i class="fas fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('tna.responses.index', ['tna_exercise_id' => $response->tna_exercise_id]) }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
