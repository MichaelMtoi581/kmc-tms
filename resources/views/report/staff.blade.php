@extends('adminlte::page')

@section('title', 'Staff Training Report')

@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-graduate mr-2"></i>Staff Training Report</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">By Staff</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users mr-1"></i> All Staff Members</h3>
        </div>
        <div class="card-body p-0">
            <table id="staff-table" class="table table-bordered table-hover mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Check No</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffList as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->check_number }}</td>
                            <td>{{ $s->full_name }}</td>
                            <td>{{ $s->department?->name ?? '—' }}</td>
                            <td>{{ $s->designation ?? '—' }}</td>
                            <td>
                                <a href="{{ route('reports.staff.show', $s->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i> View Trainings
                                </a>
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
        order: [[1, 'asc']],
        paging: true,
        searching: true,
        info: true,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: '',
            searchPlaceholder: 'Search by name, check no, department...',
            lengthMenu: '_MENU_ per page'
        }
    });
});
</script>
@endsection

@section('adminlte_css')
<style>
@media print {
    .main-header, .main-sidebar, .content-header .card-tools, .breadcrumb, nav { display: none !important; }
    .content-wrapper, .main-footer { margin-left: 0 !important; padding-top: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .card-header { padding: 10px 0 !important; }
    body { font-size: 11px; }
}
</style>
@endsection