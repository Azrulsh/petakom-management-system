@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Lecturer Details</h3>
            <p class="text-muted mb-0">
                Lecturer registration information.
            </p>
        </div>

        <a href="{{ route('manage-registration.lecturers.index') }}"
           class="btn btn-secondary">
            Back to Lecturers
        </a>

    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">Lecturer Information</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <small class="text-muted">Staff ID</small>
                    <h6>{{ $lecturer->staff_number }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Full Name</small>
                    <h6>{{ $lecturer->full_name }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Email Address</small>
                    <h6>{{ $lecturer->email }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Phone Number</small>
                    <h6>{{ $lecturer->phone ?? '-' }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Department</small>
                    <h6>{{ $lecturer->department }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Status</small>
                    <h6>{{ ucfirst($lecturer->status) }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Registered Date</small>
                    <h6>{{ $lecturer->created_at->format('d M Y') }}</h6>
                </div>

            </div>

            <hr>

            <div class="d-flex gap-2">

                <a href="{{ route('manage-registration.lecturers.edit', $lecturer->id) }}"
                   class="btn btn-warning">
                    Edit Lecturer
                </a>

                <form action="{{ route('manage-registration.lecturers.destroy', $lecturer->id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this lecturer?')">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Delete Lecturer
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection
