
@extends('layouts.header-sidebar')

@section('title', 'Review Proposal')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Review Activity Proposal</h3>
            <p class="text-muted">
                Review proposal details before making a decision.
            </p>
        </div>

        <a href="{{ route('proposal-approvals.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- PROPOSAL DETAILS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white fw-bold">
            Proposal Information
        </div>

        <div class="card-body">

            <h4 class="fw-bold text-danger mb-3">
                {{ $proposal->title }}
            </h4>

            <div class="row mb-3">

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Activity Category</label>
                    <p class="fw-semibold">
                        {{ $proposal->activity_category ?? '-' }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Prepared By</label>
                    <p class="fw-semibold">
                        {{ $proposal->prepared_by }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Proposed Date</label>
                    <p class="fw-semibold">
                        {{ $proposal->proposed_date?->format('d M Y') }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">End Date</label>
                    <p class="fw-semibold">
                        {{ $proposal->end_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Venue</label>
                    <p class="fw-semibold">
                        {{ $proposal->venue }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Target Participants</label>
                    <p class="fw-semibold">
                        {{ $proposal->target_participants }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Estimated Budget</label>
                    <p class="fw-semibold">
                        RM {{ number_format((float) $proposal->estimated_budget, 2) }}
                    </p>
                </div>

            </div>

            <hr>

            <h6 class="fw-bold">Background</h6>

            <p>
                {{ $proposal->background ?? 'Not provided.' }}
            </p>

            <hr>

            <h6 class="fw-bold">Objectives</h6>

            <p>
                {!! nl2br(e($proposal->objectives)) !!}
            </p>

        </div>

    </div>


    {{-- REVIEW FORM --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white fw-bold">
            Reviewer Decision
        </div>

        <div class="card-body">

            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i>

                This proposal is awaiting your review.
            </div>


            {{-- APPROVE FORM --}}

            <form method="POST"
                  action="{{ route('proposal-approvals.proposals.approve', $proposal) }}"
                  class="mb-4">

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Reviewer Remarks (Optional)
                    </label>

                    <textarea name="review_remarks"
                              class="form-control"
                              rows="4"
                              maxlength="5000"
                              placeholder="Enter your approval remarks...">{{ old('review_remarks') }}</textarea>

                </div>

                <button type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Are you sure you want to approve this proposal?')">

                    <i class="fas fa-check-circle"></i>
                    Approve Proposal

                </button>

            </form>


            <hr>


            {{-- REJECT FORM --}}

            <form method="POST"
                  action="{{ route('proposal-approvals.proposals.reject', $proposal) }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Rejection Remarks
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="review_remarks"
                              class="form-control"
                              rows="4"
                              maxlength="5000"
                              required
                              placeholder="Explain why this proposal is rejected...">{{ old('review_remarks') }}</textarea>

                    @error('review_remarks')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to reject this proposal?')">

                    <i class="fas fa-times-circle"></i>
                    Reject Proposal

                </button>

            </form>

        </div>

    </div>

</div>

@endsection
