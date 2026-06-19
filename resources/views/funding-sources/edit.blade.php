@extends('adminlte::page')

@section('title', 'Edit Funding Source')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-coins mr-2"></i>Edit Funding Source</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('funding-sources.index') }}">Funding Sources</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Edit "{{ $source->name }}"</h3>
                </div>

                <form method="POST" action="{{ route('funding-sources.update', $source->id) }}">
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
                            <label for="name">Source Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $source->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $source->description) }}</textarea>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('funding-sources.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection
