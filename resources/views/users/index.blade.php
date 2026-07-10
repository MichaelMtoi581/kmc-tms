@extends('adminlte::page')

@section('title', 'User Management')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users-cog mr-2"></i>User Management</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>
    </div>
@stop

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">System Users</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> New User
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th class="text-center" style="width:120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge badge-{{ $u->isAdmin() ? 'danger' : 'info' }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td>{{ $u->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$u->isAdmin())
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No users found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('js')
<script>
$(function () {
    $('#users-table').DataTable({
        order: [[0, 'asc']],
        language: { search: '', searchPlaceholder: 'Search users...' }
    });

    $('.delete-form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Delete this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    @if(session('success'))
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: @json(session('success')), showConfirmButton: false, timer: 2500 });
    @endif
    @if(session('error'))
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: @json(session('error')), showConfirmButton: false, timer: 3000 });
    @endif
});
</script>
@stop
