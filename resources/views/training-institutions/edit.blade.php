@extends('adminlte::page')

@section('title', 'Edit Training Institution')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-university mr-2"></i>Edit Training Institution</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-institutions.index') }}">Training Institutions</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Edit "{{ $institution->name }}"</h3>
                </div>

                <form method="POST" action="{{ route('training-institutions.update', $institution->id) }}">
                    @csrf
                    @method('PUT')

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
                            <label for="name">Institution Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $institution->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input
                                type="text"
                                id="location"
                                name="location"
                                value="{{ old('location', $institution->location) }}"
                                class="form-control @error('location') is-invalid @enderror">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $institution->phone) }}"
                                        class="form-control @error('phone') is-invalid @enderror">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $institution->email) }}"
                                        class="form-control @error('email') is-invalid @enderror">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('training-institutions.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection
