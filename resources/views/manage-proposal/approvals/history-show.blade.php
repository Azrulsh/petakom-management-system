@extends('layouts.header-sidebar')

@section('title', 'Proposal Review History')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Proposal Review History</h3>
            <p class="text-muted">
                Complete record of proposal review decisions.
            </p>
        </div>

        <a href="{{ route('proposal-approvals.history.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- PROPOSAL INFORMATION --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white fw-bold">
            Proposal Information
        </div>

        <div class="card-body">

            <h4 class="fw-bold text-danger">
                {{ $proposal->title }}
            </h4>

            <div class="row mt-3">

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Prepared By</label>
                    <p class="fw-semibold">
                        {{ $proposal->prepared_by }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Venue</label>
                    <p class="fw-semibold">
                        {{ $proposal->venue }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Activity Date</label>
                    <p class="fw-semibold">
                        {{ $proposal->proposed_date?->format('d M Y') }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Current Status</label>

                    <p class="fw-semibold">
                        {{ ucfirst($proposal->status) }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- APPROVAL HISTORY --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white fw-bold">
            <i class="fas fa-history me-2"></i>
            Review Timeline
        </div>

        <div class="card-body">

            @forelse($proposal->reviews as $review)

                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h6 class="fw-bold mb-1">

                                {{ $review->reviewer?->name ?? 'Unknown Reviewer' }}

                            </h6>

                            <small class="text-muted">

                                {{ ucfirst($review->reviewer?->role ?? 'User') }}

                            </small>

                        </div>


                        @if($review->decision === 'approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($review->decision === 'rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ ucfirst($review->decision) }}
                            </span>

                        @endif

                    </div>


                    <div class="mb-2">

                        <small class="text-muted">
                            Review Date
                        </small>

                        <p class="mb-0 fw-semibold">

                            {{ $review->reviewed_at?->format('d M Y, h:i A') }}

                        </p>

                    </div>


                    <div>

                        <small class="text-muted">
                            Reviewer Remarks
                        </small>

                        <p class="mb-0">

                            {{ $review->remarks ?? 'No remarks provided.' }}

                        </p>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    <i class="fas fa-inbox fa-2x mb-2"></i>

                    <p>No review records found.</p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
