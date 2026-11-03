@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Committee Member Details</h3>

            <p class="text-muted mb-0">
                PETAKOM committee membership information.
            </p>
        </div>

        <a href="{{ route('manage-election.committees.index') }}"
           class="btn btn-secondary">
            Back to Committee
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">Member Information</h5>

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted">Student ID</small>

                    <h6>
                        {{ $committee->student->student_number ?? '-' }}
                    </h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Student Name</small>

                    <h6>
                        {{ $committee->student->full_name ?? '-' }}
                    </h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Email Address</small>

                    <h6>
                        {{ $committee->student->email ?? '-' }}
                    </h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Committee Position</small>

                    <h6>{{ $committee->position }}</h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Academic Session</small>

                    <h6>{{ $committee->academic_session }}</h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Appointment Date</small>

                    <h6>
                        {{ $committee->appointed_at
                            ? \Carbon\Carbon::parse($committee->appointed_at)->format('d M Y')
                            : '-' }}
                    </h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Membership Status</small>

                    <h6>{{ ucfirst($committee->status) }}</h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">Created Date</small>

                    <h6>
                        {{ $committee->created_at->format('d M Y') }}
                    </h6>

                </div>

            </div>

            <hr>

            <div class="d-flex gap-2">

                <a href="{{ route('manage-election.committees.edit', $committee->id) }}"
                   class="btn btn-warning">
                    Edit Member
                </a>

                <form action="{{ route('manage-election.committees.destroy', $committee->id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this committee record?')">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Delete Member
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
