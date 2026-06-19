@extends('adminlte::page')

@section('title', 'Staff')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users mr-2"></i>Staff</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Staff</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $staff->count() }}</h3>
                    <p>Total Staff</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $staff->where('gender', 'Male')->count() }} / {{ $staff->where('gender', 'Female')->count() }}</h3>
                    <p>Male / Female</p>
                </div>
                <div class="icon"><i class="fas fa-venus-mars"></i></div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title">All Staff</h3>

            <div class="card-tools">
                <a href="{{ route('staff.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Staff
                </a>
            </div>
        </div>

        <div class="card-body">

            <table id="staff-table" class="table table-bordered table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Check No</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($staff as $member)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->check_number }}</td>
                        <td>{{ $member->full_name }}</td>
                        <td>{{ $member->gender }}</td>
                        <td>{{ $member->designation }}</td>
                        <td>
                            <span class="badge badge-secondary">{{ $member->department->name }}</span>
                        </td>
                        <td class="text-center">

                            <a href="{{ route('staff.edit', $member->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('staff.destroy', $member->id) }}"
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
            $('#staff-table').DataTable({
                order: [[2, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search staff...'
                }
            });

            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete this staff member?',
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
