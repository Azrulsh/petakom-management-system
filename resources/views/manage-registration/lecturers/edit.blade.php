
@extends('layouts.registration')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold">Edit Lecturer</h3>
        <p class="text-muted">
            Update lecturer information.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            @include('manage-registration.lecturers.form', [
                'lecturer' => $lecturer
            ])

        </div>
    </div>

</div>

@endsection
