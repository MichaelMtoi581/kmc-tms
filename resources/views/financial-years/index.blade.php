@extends('adminlte::page')

@section('title', 'Financial Years')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-calendar-alt mr-2"></i>Financial Years</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Financial Years</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalYears }}</h3>
                    <p>Total Financial Years</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeYears }}</h3>
                    <p>Active Years</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $totalYears - $activeYears }}</h3>
                    <p>Inactive Years</p>
                </div>
                <div class="icon"><i class="fas fa-pause-circle"></i></div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{ session('warning') }}</strong>
            @if(session('warning_details'))
                <pre class="mb-0 mt-2" style="white-space:pre-wrap;">{{ session('warning_details') }}</pre>
            @endif
        </div>
    @endif

    <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title">All Financial Years</h3>

            <div class="card-tools">
                <a href="{{ route('financial-years.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Financial Year
                </a>
            </div>
        </div>

        <div class="card-body">

            <table id="financial-years-table" class="table table-bordered table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($years as $year)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $year->year_name }}</td>
                        <td>{{ $year->start_date }}</td>
                        <td>{{ $year->end_date }}</td>
                        <td>
                            @if($year->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">

                            <a href="{{ route('financial-years.edit', $year->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('financial-years.destroy', $year->id) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('#financial-years-table').DataTable({
                order: [[1, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search financial years...'
                }
            });

            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete this financial year?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Cannot delete',
                    text: @json(session('error'))
                });
            @endif
        });
    </script>
@endsection
