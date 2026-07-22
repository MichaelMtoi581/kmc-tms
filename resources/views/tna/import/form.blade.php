@extends('adminlte::page')
@section('title', 'Import TNA')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-file-import mr-2"></i>Import TNA Responses</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Import TNA</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">

            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">Upload Excel File</h3>
                </div>

                <form method="POST" action="{{ route('tna.import.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        @if(session('import_errors'))
                            <div class="alert alert-danger">
                                <strong>Import failed &mdash; the following rows have errors:</strong>
                                <pre class="mb-0 mt-2" style="white-space:pre-wrap;">{{ session('import_errors') }}</pre>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="tna_exercise_id">TNA Exercise *</label>
                            <select id="tna_exercise_id" name="tna_exercise_id" class="form-control @error('tna_exercise_id') is-invalid @enderror" required>
                                <option value="">Select TNA Exercise</option>
                                @foreach($exercises as $e)
                                    <option value="{{ $e->id }}" {{ old('tna_exercise_id') == $e->id ? 'selected' : '' }}>
                                        {{ $e->title }} ({{ $e->financialYear?->year_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tna_exercise_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">Excel File (.xlsx, .xls, .csv)</label>
                            <div class="custom-file">
                                <input type="file" id="file" name="file" class="custom-file-input @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                            <small class="form-text text-muted">
                                Max size: 10MB. Expected columns: check_number, full_name, department, designation, training_needed, training_reason, expected_outcome, priority, preferred_duration, preferred_institution, remarks.
                            </small>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload mr-1"></i> Import</button>
                        <a href="{{ route('tna.import.template') }}" class="btn btn-info"><i class="fas fa-download mr-1"></i> Download Template</a>
                        <a href="{{ route('tna.exercises.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>

            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Expected Excel Columns</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Column</th><th>Required</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td>check_number</td><td><span class="badge badge-success">Yes</span></td><td>Staff check number</td></tr>
                            <tr><td>full_name</td><td><span class="badge badge-success">Yes</span></td><td>Full name of staff</td></tr>
                            <tr><td>department</td><td><span class="badge badge-warning">No</span></td><td>Department name</td></tr>
                            <tr><td>designation</td><td><span class="badge badge-warning">No</span></td><td>Job title</td></tr>
                            <tr><td>training_needed</td><td><span class="badge badge-success">Yes</span></td><td>Training course requested</td></tr>
                            <tr><td>training_reason</td><td><span class="badge badge-warning">No</span></td><td>Why the training is needed</td></tr>
                            <tr><td>expected_outcome</td><td><span class="badge badge-warning">No</span></td><td>What they expect to gain</td></tr>
                            <tr><td>priority</td><td><span class="badge badge-warning">No</span></td><td>High, Medium, or Low</td></tr>
                            <tr><td>preferred_duration</td><td><span class="badge badge-warning">No</span></td><td>e.g., 1 Week, 1 Month</td></tr>
                            <tr><td>preferred_institution</td><td><span class="badge badge-warning">No</span></td><td>Training provider</td></tr>
                            <tr><td>remarks</td><td><span class="badge badge-warning">No</span></td><td>Additional comments</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
<script>
$(function () {
    $('.custom-file-input').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endsection
