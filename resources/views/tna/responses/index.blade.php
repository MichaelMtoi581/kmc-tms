@extends('adminlte::page')
@section('title', 'TNA Responses')
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-list-alt mr-2"></i>TNA Responses</h1>
        <div>
            <a href="{{ route('tna.responses.export', ['tna_exercise_id' => request('tna_exercise_id')]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
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

    @if(session('warning'))
        <div class="alert alert-warning">
            <strong>{{ session('warning') }}</strong>
            @if(session('warning_details'))
                <pre class="mb-0 mt-2" style="white-space:pre-wrap;font-size:10px;">{{ session('warning_details') }}</pre>
            @endif
        </div>
    @endif

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
            <form method="GET" class="form-inline ml-3">
                <div class="form-group mr-2">
                    <select name="tna_exercise_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Exercises</option>
                        @foreach($exercises as $e)
                            <option value="{{ $e->id }}" {{ request('tna_exercise_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->title }} ({{ $e->financialYear?->year_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table id="response-table" class="table table-bordered table-striped mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Check No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Training Needed</th>
                        <th>Priority</th>
                        <th>Duration</th>
                        <th>Institution</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->check_number }}</td>
                            <td>{{ $r->full_name }}</td>
                            <td>{{ $r->department ?? '—' }}</td>
                            <td>{{ $r->training_needed }}</td>
                            <td>
                                @php
                                    $badge = match($r->priority) {
                                        'High' => 'danger', 'Medium' => 'warning', 'Low' => 'info', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $r->priority }}</span>
                            </td>
                            <td>{{ $r->preferred_duration ?? '—' }}</td>
                            <td>{{ $r->preferred_institution ?? '—' }}</td>
                            <td>
                                <a href="{{ route('tna.responses.edit', $r) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('tna.responses.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this response?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No TNA responses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $responses->links() }}

@endsection

@section('js')
<script>
$(function () {
    $('#response-table').DataTable({
        order: [[0, 'asc']],
        paging: false,
        searching: true,
        info: false,
    });
});
</script>
@endsection
