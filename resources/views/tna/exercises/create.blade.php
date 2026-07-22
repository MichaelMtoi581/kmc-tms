@extends('adminlte::page')
@section('title', 'Create TNA Exercise')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-plus-circle mr-2"></i>New TNA Exercise</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tna.exercises.index') }}">TNA Exercises</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Exercise Details</h3>
                </div>
                <form method="POST" action="{{ route('tna.exercises.store') }}">
                    @csrf
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

                        <div class="form-group">
                            <label for="financial_year_id">Financial Year *</label>
                            <select id="financial_year_id" name="financial_year_id" class="form-control @error('financial_year_id') is-invalid @enderror" required>
                                <option value="">Select Financial Year</option>
                                @foreach($financialYears as $fy)
                                    <option value="{{ $fy->id }}" {{ old('financial_year_id') == $fy->id ? 'selected' : '' }}>
                                        {{ $fy->year_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('financial_year_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Exercise Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', 'Annual Training Needs Assessment') }}" class="form-control @error('title') is-invalid @enderror" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Open" {{ old('status', 'Open') === 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ old('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Create Exercise</button>
                        <a href="{{ route('tna.exercises.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
