@extends('adminlte::page')

@section('title', 'Add Financial Year')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-calendar-alt mr-2"></i>Add Financial Year</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('financial-years.index') }}">Financial Years</a></li>
            <li class="breadcrumb-item active">Add</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">Financial Year Details</h3>
                </div>

                <form method="POST" action="{{ route('financial-years.store') }}">

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
                            <label for="year_name">Year Name</label>
                            <input
                                type="text"
                                id="year_name"
                                name="year_name"
                                class="form-control @error('year_name') is-invalid @enderror"
                                placeholder="e.g. 2026/2027"
                                value="{{ old('year_name') }}"
                                required>
                            @error('year_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date') }}"
                                required>
                            @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}"
                                required>
                            @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    class="custom-control-input"
                                    value="1"
                                    {{ old('is_active') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active Year</label>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Save
                        </button>
                        <a href="{{ route('financial-years.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection
