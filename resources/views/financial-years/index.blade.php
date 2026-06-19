@extends('adminlte::page')

@section('content')
    

<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h4>Financial Years</h4>

        <a href="{{ route('financial-years.create') }}"
           class="btn btn-primary">
            Add Financial Year
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>#</th>
                <th>Year</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th width="180">Action</th>
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
                    <span class="badge bg-success">
                        Active
                    </span>
                @else
                    <span class="badge bg-secondary">
                        Inactive
                    </span>
                @endif
            </td>

            <td>

                <a href="{{ route('financial-years.edit',$year->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form
                    action="{{ route('financial-years.destroy',$year->id) }}"
                    method="POST"
                    style="display:inline">

                    @csrf
                    @method('DELETE')

                    <button
                        onclick="return confirm('Delete?')"
                        class="btn btn-danger btn-sm">

                        Delete

                    </button>

                </form>

            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

    {{ $years->links() }}

</div>

@endsection