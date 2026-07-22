@extends('adminlte::page')
@section('title', 'TNA Exercises')
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-file-alt mr-2"></i>TNA Exercises</h1>
        <div>
            <a href="{{ route('tna.exercises.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New Exercise
            </a>
        </div>
    </div>
@endsection

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card card-primary card-outline">
        <div class="card-body p-0">
            <table id="exercise-table" class="table table-bordered table-striped mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Financial Year</th>
                        <th>Responses</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exercises as $e)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $e->title }}</td>
                            <td>{{ $e->financialYear?->year_name ?? '—' }}</td>
                            <td><span class="badge badge-info">{{ $e->responses->count() }}</span></td>
                            <td>
                                {{ $e->start_date?->format('d/m/Y') ?? '—' }} &mdash;
                                {{ $e->end_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $e->status === 'Open' ? 'success' : 'secondary' }}">
                                    {{ $e->status }}
                                </span>
                            </td>
                            <td>{{ $e->creator?->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('tna.exercises.show', $e) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tna.exercises.edit', $e) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('tna.exercises.destroy', $e) }}" class="d-inline" onsubmit="return confirm('Delete this exercise and all its responses?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No TNA exercises found. Click "New Exercise" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $exercises->links() }}

@endsection

@section('js')
<script>
$(function () {
    $('#exercise-table').DataTable({
        order: [[0, 'desc']],
        paging: false,
        searching: true,
        info: false,
    });
});
</script>
@endsection
