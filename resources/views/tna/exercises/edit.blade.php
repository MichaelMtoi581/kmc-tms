@extends('adminlte::page')
@section('title', 'Edit TNA Exercise')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-2"></i>Edit TNA Exercise</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tna.exercises.index') }}">TNA Exercises</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $exercise->title }}</h3>
                </div>
                <form method="POST" action="{{ route('tna.exercises.update', $exercise) }}">
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

                        <div class="form-group">
                            <label for="title">Exercise Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $exercise->title) }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $exercise->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $exercise->start_date?->format('Y-m-d')) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $exercise->end_date?->format('Y-m-d')) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Open" {{ old('status', $exercise->status) === 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ old('status', $exercise->status) === 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Update</button>
                        <a href="{{ route('tna.exercises.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
