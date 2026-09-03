@extends('adminlte::page')

@section('title', 'Department Trainings')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-building mr-2"></i>{{ $department->name }} — Department Trainings</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('unplanned-trainings.index') }}">Unplanned Trainings</a></li>
            <li class="breadcrumb-item active">{{ $department->name }}</li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $trainings->count() }}</h3>
                    <p>Total Trainings</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $trainings->where('status', 'Completed')->count() }}</h3>
                    <p>Completed</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $trainings->where('status', 'Ongoing')->count() }}</h3>
                    <p>Ongoing</p>
                </div>
                <div class="icon"><i class="fas fa-spinner"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($trainings->sum('cost'), 0) }}</h3>
                    <p>Total Cost (TZS)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card card-warning card-outline">

        <div class="card-header">
            <h3 class="card-title">All Trainings for {{ $department->name }}</h3>

            <div class="card-tools">
                <a href="{{ route('unplanned-trainings.create', ['department_id' => $department->id, 'registration_type' => 'department']) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Training
                </a>
            </div>
        </div>

        <div class="card-body">

            <form method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <select name="financial_year_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Financial Years</option>
                        @foreach($financialYears as $year)
                            <option value="{{ $year->id }}" {{ request('financial_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @if(request()->anyFilled(['financial_year_id', 'status']))
                    <a href="{{ route('unplanned-trainings.department', $department->id) }}" class="btn btn-sm btn-secondary">Clear</a>
                @endif
            </form>

            <table id="department-trainings-table" class="table table-bordered table-striped" style="width:100%">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Title</th>
                        <th>Financial Year</th>
                        <th>Category</th>
                        <th>Period</th>
                        <th>Duration</th>
                        <th>Cost (TZS)</th>
                        <th>Status</th>
                        <th class="text-center" style="width:130px">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($trainings as $training)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('unplanned-trainings.show', $training->id) }}">
                                {{ $training->course_title }}
                            </a>
                        </td>
                        <td>{{ $training->financialYear?->year_name ?? '—' }}</td>
                        <td>{{ $training->trainingCategory?->name ?? '—' }}</td>
                        <td>
                            {{ $training->start_period_label ?? $training->start_date?->format('d M Y') ?? '—' }}
                            &mdash;
                            {{ $training->end_period_label ?? $training->end_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $training->duration_type === 'Long' ? 'danger' : 'success' }}">
                                {{ $training->duration_type }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($training->cost, 0) }}</td>
                        <td>
                            @php
                                $badge = match($training->status) {
                                    'Planned' => 'primary',
                                    'Ongoing' => 'warning',
                                    'Completed' => 'success',
                                    'Cancelled' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ $training->status }}</span>
                        </td>
                        <td class="text-center">

                            <a href="{{ route('unplanned-trainings.show', $training->id) }}"
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('unplanned-trainings.edit', $training->id) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('unplanned-trainings.destroy', $training->id) }}"
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

                @empty

                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            No department trainings found for {{ $department->name }}.
                            <a href="{{ route('unplanned-trainings.create', ['department_id' => $department->id, 'registration_type' => 'department']) }}">Add one</a>.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('#department-trainings-table').DataTable({
                order: [[0, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search trainings...'
                }
            });

            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete this training?',
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
        });
    </script>
@endsection
