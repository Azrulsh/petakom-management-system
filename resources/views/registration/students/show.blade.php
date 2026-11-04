@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Student Details</h3>
            <p class="text-muted mb-0">Student registration information.</p>
        </div>

        <a href="{{ route('registration.students.index') }}"
           class="btn btn-secondary">
            Back to Students
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">Personal Information</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <small class="text-muted">Student ID</small>
                    <h6>{{ $student->student_number }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Full Name</small>
                    <h6>{{ $student->full_name }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Email</small>
                    <h6>{{ $student->email }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Phone</small>
                    <h6>{{ $student->phone ?? '-' }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Programme</small>
                    <h6>{{ $student->programme }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Year of Study</small>
                    <h6>{{ $student->year_of_study ?? '-' }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Registration Status</small>
                    <h6>{{ ucfirst($student->status) }}</h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Registered Date</small>
                    <h6>{{ $student->created_at->format('d M Y') }}</h6>
                </div>

            </div>

            <hr>

            <div class="d-flex gap-2">

                <a href="{{ route('registration.students.edit', $student->id) }}"
                   class="btn btn-warning">
                    Edit Student
                </a>

                <form action="{{ route('registration.students.destroy', $student->id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this student?')">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Delete Student
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection
