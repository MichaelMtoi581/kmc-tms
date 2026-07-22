@extends('adminlte::page')
@section('title', 'Edit TNA Response')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-2"></i>Edit TNA Response</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tna.responses.index', ['tna_exercise_id' => $response->tna_exercise_id]) }}">TNA Responses</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $response->full_name }} &mdash; {{ $response->training_needed }}</h3>
                </div>
                <form method="POST" action="{{ route('tna.responses.update', $response) }}">
                    @csrf @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $response->full_name) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" name="department" value="{{ old('department', $response->department) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" name="designation" value="{{ old('designation', $response->designation) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Training Needed *</label>
                                    <input type="text" name="training_needed" value="{{ old('training_needed', $response->training_needed) }}" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Training Reason</label>
                            <textarea name="training_reason" class="form-control" rows="2">{{ old('training_reason', $response->training_reason) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Expected Outcome</label>
                            <textarea name="expected_outcome" class="form-control" rows="2">{{ old('expected_outcome', $response->expected_outcome) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Priority *</label>
                                    <select name="priority" class="form-control" required>
                                        <option value="High" {{ old('priority', $response->priority) === 'High' ? 'selected' : '' }}>High</option>
                                        <option value="Medium" {{ old('priority', $response->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="Low" {{ old('priority', $response->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Preferred Duration</label>
                                    <input type="text" name="preferred_duration" value="{{ old('preferred_duration', $response->preferred_duration) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Preferred Institution</label>
                                    <input type="text" name="preferred_institution" value="{{ old('preferred_institution', $response->preferred_institution) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $response->remarks) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Update</button>
                        <a href="{{ route('tna.responses.index', ['tna_exercise_id' => $response->tna_exercise_id]) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
