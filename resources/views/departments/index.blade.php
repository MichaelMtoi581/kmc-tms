@extends('adminlte::page')

@section('title', 'Departments')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-building mr-2"></i>Departments</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Departments</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $departments->count() }}</h3>
                    <p>Total Departments</p>
                </div>
                <div class="icon"><i class="fas fa-sitemap"></i></div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title">All Departments</h3>

            <div class="card-tools">
                <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Department
                </a>
            </div>
        </div>

        <div class="card-body">

            <table id="departments-table" class="table table-bordered table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Staff Count</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($departments as $department)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->description ?? '—' }}</td>
                        <td><span class="badge badge-info">{{ $department->staff_count }}</span></td>
                        <td class="text-center">

                            <a href="{{ route('departments.edit', $department->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('departments.destroy', $department->id) }}"
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
            $('#departments-table').DataTable({
                order: [[1, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search departments...'
                }
            });

            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete this department?',
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
