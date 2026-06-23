@extends('adminlte::page')

@section('title', 'Unplanned Training Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard mr-2"></i>Training Details</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('unplanned-trainings.index') }}">Unplanned Trainings</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">{{ $unplannedTraining->course_title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('unplanned-trainings.edit', $unplannedTraining->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:200px">Course Title</th>
                            <td>{{ $unplannedTraining->course_title }}</td>
                        </tr>
                        <tr>
                            <th>Staff</th>
                            <td>
                                <a href="{{ route('staff.edit', $unplannedTraining->staff_id) }}">
                                    {{ $unplannedTraining->staff?->full_name ?? '—' }}
                                </a>
                                <small class="text-muted d-block">{{ $unplannedTraining->staff?->check_number ?? '' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $unplannedTraining->department?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Financial Year</th>
                            <td>{{ $unplannedTraining->financialYear?->year_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $unplannedTraining->trainingCategory?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Training Institution</th>
                            <td>{{ $unplannedTraining->trainingInstitution?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Funding Source</th>
                            <td>{{ $unplannedTraining->fundingSource?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Period</th>
                            <td>
                                {{ $unplannedTraining->start_date?->format('d M Y') ?? '—' }}
                                &mdash;
                                {{ $unplannedTraining->end_date?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <td>{{ $unplannedTraining->venue ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Cost (TZS)</th>
                            <td>{{ number_format($unplannedTraining->cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($unplannedTraining->status) {
                                        'Planned' => 'primary',
                                        'Ongoing' => 'warning',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $unplannedTraining->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>
                                <span class="badge badge-{{ $unplannedTraining->duration_type === 'Long' ? 'danger' : 'success' }}">
                                    {{ $unplannedTraining->duration_type }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Source</th>
                            <td>
                                <span class="badge badge-{{ $unplannedTraining->source === 'import' ? 'info' : 'secondary' }}">
                                    {{ ucfirst($unplannedTraining->source) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description / Purpose</th>
                            <td>{{ $unplannedTraining->description ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $unplannedTraining->remarks ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Recorded</th>
                            <td>{{ $unplannedTraining->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>

                </div>

                <div class="card-footer">
                    <a href="{{ route('unplanned-trainings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

            </div>

        </div>
    </div>

@endsection
