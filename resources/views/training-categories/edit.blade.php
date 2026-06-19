@extends('adminlte::page')

@section('title', 'Edit Training Category')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tags mr-2"></i>Edit Training Category</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-categories.index') }}">Training Categories</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Edit "{{ $category->name }}"</h3>
                </div>

                <form method="POST" action="{{ route('training-categories.update', $category->id) }}">
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
                            <label for="name">Category Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $category->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('training-categories.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection
