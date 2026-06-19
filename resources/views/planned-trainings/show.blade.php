@extends('adminlte::page')

@section('title', 'Training Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard-list mr-2"></i>Training Details</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planned-trainings.index') }}">Planned Trainings</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">{{ $plannedTraining->course_title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('planned-trainings.edit', $plannedTraining->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:200px">Course Title</th>
                            <td>{{ $plannedTraining->course_title }}</td>
                        </tr>
                        <tr>
                            <th>Staff</th>
                            <td>
                                <a href="{{ route('staff.edit', $plannedTraining->staff_id) }}">
                                    {{ $plannedTraining->staff?->full_name ?? '—' }}
                                </a>
                                <small class="text-muted d-block">{{ $plannedTraining->staff?->check_number ?? '' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $plannedTraining->department?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Financial Year</th>
                            <td>{{ $plannedTraining->financialYear?->year_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $plannedTraining->trainingCategory?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Training Institution</th>
                            <td>{{ $plannedTraining->trainingInstitution?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Funding Source</th>
                            <td>{{ $plannedTraining->fundingSource?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Period</th>
                            <td>
                                {{ $plannedTraining->start_date?->format('d M Y') ?? '—' }}
                                &mdash;
                                {{ $plannedTraining->end_date?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <td>{{ $plannedTraining->venue ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Cost (TZS)</th>
                            <td>{{ number_format($plannedTraining->cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($plannedTraining->status) {
                                        'Planned' => 'primary',
                                        'Ongoing' => 'warning',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $plannedTraining->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Source</th>
                            <td>
                                <span class="badge badge-{{ $plannedTraining->source === 'import' ? 'info' : 'secondary' }}">
                                    {{ ucfirst($plannedTraining->source) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description / Purpose</th>
                            <td>{{ $plannedTraining->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $plannedTraining->remarks ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Recorded</th>
                            <td>{{ $plannedTraining->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>

                </div>

                <div class="card-footer">
                    <a href="{{ route('planned-trainings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

            </div>

        </div>
    </div>

@endsection
