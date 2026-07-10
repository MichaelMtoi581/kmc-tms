@extends('adminlte::page')

@section('title', 'Audit Log')

@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-history mr-2"></i>Audit Log</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Audit Log</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
        </div>

        <div class="card-body">

            <table id="audit-log-table" class="table table-bordered table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($logs as $log)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            @switch($log->action)
                                @case('created')
                                    <span class="badge badge-success">Created</span>
                                    @break
                                @case('updated')
                                    <span class="badge badge-info">Updated</span>
                                    @break
                                @case('deleted')
                                    <span class="badge badge-danger">Deleted</span>
                                    @break
                                @case('imported')
                                    <span class="badge badge-warning">Imported</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $log->action }}</span>
                            @endswitch
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center text-muted">No activity recorded yet.</td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('#audit-log-table').DataTable({
                order: [[0, 'desc']],
                paging: false,
                searching: true,
                info: false,
                language: {
                    search: '',
                    searchPlaceholder: 'Search audit log...'
                }
            });
        });
    </script>
@endsection
