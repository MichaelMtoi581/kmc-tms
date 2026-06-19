@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-edit mr-2"></i>Edit Staff</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Edit "{{ $staff->full_name }}"</h3>
                </div>

                <form method="POST" action="{{ route('staff.update', $staff->id) }}">
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
                            <label for="check_number">Check Number</label>
                            <input
                                type="text"
                                id="check_number"
                                name="check_number"
                                value="{{ old('check_number', $staff->check_number) }}"
                                class="form-control @error('check_number') is-invalid @enderror"
                                required>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="first_name">First Name</label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name', $staff->first_name) }}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="middle_name">Middle Name</label>
                                <input
                                    type="text"
                                    id="middle_name"
                                    name="middle_name"
                                    value="{{ old('middle_name', $staff->middle_name) }}"
                                    class="form-control @error('middle_name') is-invalid @enderror">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="last_name">Last Name</label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name', $staff->last_name) }}"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="">-- Select --</option>
                                    <option value="Male" {{ old('gender', $staff->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $staff->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="date_of_birth">Date of Birth</label>
                                <input
                                    type="date"
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}"
                                    class="form-control @error('date_of_birth') is-invalid @enderror">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="department_id">Department</label>
                                <select id="department_id" name="department_id" class="form-control select2 @error('department_id') is-invalid @enderror" style="width:100%" required>
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $staff->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="designation">Designation</label>
                                <input
                                    type="text"
                                    id="designation"
                                    name="designation"
                                    value="{{ old('designation', $staff->designation) }}"
                                    class="form-control @error('designation') is-invalid @enderror"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="education_level">Education Level</label>
                                <input
                                    type="text"
                                    id="education_level"
                                    name="education_level"
                                    value="{{ old('education_level', $staff->education_level) }}"
                                    class="form-control @error('education_level') is-invalid @enderror">
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
