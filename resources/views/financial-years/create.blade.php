@extends('layouts.app')

@section('content')

<div class="container">

<form method="POST"
      action="{{ route('financial-years.store') }}">

    @csrf

    <div class="card">

        <div class="card-header">
            Add Financial Year
        </div>

        <div class="card-body">

            <div class="mb-3">

                <label>Year Name</label>

                <input
                    type="text"
                    name="year_name"
                    class="form-control"
                    placeholder="2026/2027">

            </div>

            <div class="mb-3">

                <label>Start Date</label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control">

            </div>

            <div class="mb-3">

                <label>End Date</label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control">

            </div>

            <div class="mb-3">

                <label>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1">

                    Active Year

                </label>

            </div>

            <button class="btn btn-success">
                Save
            </button>

        </div>

    </div>

</form>

</div>

@endsection