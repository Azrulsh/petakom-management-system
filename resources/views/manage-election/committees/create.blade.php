@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold">Add Committee Member</h3>

        <p class="text-muted">
            Register a student as a PETAKOM committee member.
        </p>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            @include('manage-election.committees.form', [
                'committee' => null,
                'students' => $students
            ])

        </div>

    </div>

</div>

@endsection
