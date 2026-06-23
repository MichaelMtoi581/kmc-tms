@extends('adminlte::page')

@section('title', 'Edit Unplanned Training')

@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard mr-2"></i>Edit Unplanned Training</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('unplanned-trainings.index') }}">Unplanned Trainings</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Edit "{{ $unplannedTraining->course_title }}"</h3>
                </div>

                <form method="POST" action="{{ route('unplanned-trainings.update', $unplannedTraining->id) }}">
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
                            <label for="course_title">Course Title</label>
                            <input
                                type="text"
                                id="course_title"
                                name="course_title"
                                value="{{ old('course_title', $unplannedTraining->course_title) }}"
                                class="form-control @error('course_title') is-invalid @enderror"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staff_id">Staff</label>
                                    <select
                                        id="staff_id"
                                        name="staff_id"
                                        class="form-control select2 @error('staff_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Staff</option>
                                        @foreach($staff as $s)
                                            <option value="{{ $s->id }}" {{ old('staff_id', $unplannedTraining->staff_id) == $s->id ? 'selected' : '' }}>
                                                {{ $s->full_name }} ({{ $s->check_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department_id">Department</label>
                                    <select
                                        id="department_id"
                                        name="department_id"
                                        class="form-control select2 @error('department_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id', $unplannedTraining->department_id) == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="financial_year_id">Financial Year</label>
                                    <select
                                        id="financial_year_id"
                                        name="financial_year_id"
                                        class="form-control @error('financial_year_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Year</option>
                                        @foreach($financialYears as $year)
                                            <option value="{{ $year->id }}" {{ old('financial_year_id', $unplannedTraining->financial_year_id) == $year->id ? 'selected' : '' }}>
                                                {{ $year->year_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="training_category_id">Category</label>
                                    <select
                                        id="training_category_id"
                                        name="training_category_id"
                                        class="form-control @error('training_category_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('training_category_id', $unplannedTraining->training_category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select
                                        id="status"
                                        name="status"
                                        class="form-control @error('status') is-invalid @enderror"
                                        required>
                                        <option value="Planned" {{ old('status', $unplannedTraining->status) == 'Planned' ? 'selected' : '' }}>Planned</option>
                                        <option value="Ongoing" {{ old('status', $unplannedTraining->status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Completed" {{ old('status', $unplannedTraining->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ old('status', $unplannedTraining->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="training_institution_id">Training Institution</label>
                                    <select
                                        id="training_institution_id"
                                        name="training_institution_id"
                                        class="form-control select2 @error('training_institution_id') is-invalid @enderror">
                                        <option value="">Select Institution</option>
                                        @foreach($institutions as $inst)
                                            <option value="{{ $inst->id }}" {{ old('training_institution_id', $unplannedTraining->training_institution_id) == $inst->id ? 'selected' : '' }}>
                                                {{ $inst->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="funding_source_id">Funding Source</label>
                                    <select
                                        id="funding_source_id"
                                        name="funding_source_id"
                                        class="form-control @error('funding_source_id') is-invalid @enderror">
                                        <option value="">Select Funding Source</option>
                                        @foreach($fundingSources as $fs)
                                            <option value="{{ $fs->id }}" {{ old('funding_source_id', $unplannedTraining->funding_source_id) == $fs->id ? 'selected' : '' }}>
                                                {{ $fs->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Duration</label>
                            <div>
                                <span id="duration-badge" class="badge badge-{{ $unplannedTraining->duration_type === 'Long' ? 'danger' : 'success' }}">{{ $unplannedTraining->duration_type }}</span>
                                <small class="text-muted ml-2">Auto-calculated from dates</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        value="{{ old('start_date', $unplannedTraining->start_date?->format('Y-m-d')) }}"
                                        class="form-control @error('start_date') is-invalid @enderror">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        value="{{ old('end_date', $unplannedTraining->end_date?->format('Y-m-d')) }}"
                                        class="form-control @error('end_date') is-invalid @enderror">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cost">Cost (TZS)</label>
                                    <input
                                        type="number"
                                        id="cost"
                                        name="cost"
                                        value="{{ old('cost', $unplannedTraining->cost) }}"
                                        class="form-control @error('cost') is-invalid @enderror"
                                        min="0"
                                        step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="venue">Venue</label>
                            <input
                                type="text"
                                id="venue"
                                name="venue"
                                value="{{ old('venue', $unplannedTraining->venue) }}"
                                class="form-control @error('venue') is-invalid @enderror">
                        </div>

                        <div class="form-group">
                            <label for="description">Description / Purpose</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $unplannedTraining->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea
                                id="remarks"
                                name="remarks"
                                rows="2"
                                class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $unplannedTraining->remarks) }}</textarea>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('unplanned-trainings.index') }}" class="btn btn-secondary">
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
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            function calcDuration() {
                var start = $('#start_date').val();
                var end = $('#end_date').val();
                var $badge = $('#duration-badge');

                if (start && end) {
                    var s = new Date(start);
                    var e = new Date(end);
                    var months = (e.getFullYear() - s.getFullYear()) * 12 + (e.getMonth() - s.getMonth());
                    if (months >= 6) {
                        $badge.text('Long').removeClass('badge-success').addClass('badge-danger');
                    } else {
                        $badge.text('Short').removeClass('badge-danger').addClass('badge-success');
                    }
                } else {
                    $badge.text('Short').removeClass('badge-danger').addClass('badge-success');
                }
            }

            $('#start_date, #end_date').on('change', calcDuration);
        });
    </script>
@endsection
