@extends('adminlte::page')

@section('title', 'Import Trainings')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-file-import mr-2"></i>Import Planned Trainings</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planned-trainings.index') }}">Planned Trainings</a></li>
            <li class="breadcrumb-item active">Import</li>
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

                <form method="POST" action="{{ route('planned-trainings.import.store') }}" enctype="multipart/form-data">
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
                            <label for="file">Excel File (.xlsx, .xls, .csv)</label>
                            <div class="custom-file">
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    class="custom-file-input @error('file') is-invalid @enderror"
                                    accept=".xlsx,.xls,.csv"
                                    required>
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                            <small class="form-text text-muted">
                                Max size: 10MB. Expected columns: course_title, check_number, department,
                                financial_year, category, institution, funding_source, start_date, end_date,
                                venue, cost, description, remarks.
                            </small>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload mr-1"></i> Import
                        </button>
                        <a href="{{ route('planned-trainings.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Excel Format Guide</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>Required</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>course_title</td><td><span class="badge badge-success">Yes</span></td><td>Name of the training</td></tr>
                            <tr><td>check_number</td><td><span class="badge badge-warning">No</span></td><td>Staff check number (must exist)</td></tr>
                            <tr><td>department</td><td><span class="badge badge-warning">No</span></td><td>Department name (must exist)</td></tr>
                            <tr><td>financial_year</td><td><span class="badge badge-warning">No</span></td><td>e.g. 2024/2025</td></tr>
                            <tr><td>category</td><td><span class="badge badge-warning">No</span></td><td>Training category name</td></tr>
                            <tr><td>institution</td><td><span class="badge badge-warning">No</span></td><td>Institution name</td></tr>
                            <tr><td>funding_source</td><td><span class="badge badge-warning">No</span></td><td>Funding source name</td></tr>
                            <tr><td>start_date</td><td><span class="badge badge-warning">No</span></td><td>dd/mm/yyyy or Excel date</td></tr>
                            <tr><td>end_date</td><td><span class="badge badge-warning">No</span></td><td>dd/mm/yyyy or Excel date</td></tr>
                            <tr><td>venue</td><td><span class="badge badge-warning">No</span></td><td>Training venue</td></tr>
                            <tr><td>cost</td><td><span class="badge badge-warning">No</span></td><td>Amount in TZS</td></tr>
                            <tr><td>description</td><td><span class="badge badge-warning">No</span></td><td>Purpose/notes</td></tr>
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
