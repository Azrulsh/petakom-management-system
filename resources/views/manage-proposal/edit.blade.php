@extends('layouts.header-sidebar')

@section('title', 'Edit Proposal')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="font-weight-bold">Edit Activity Proposal</h3>

        <p class="text-muted">
            Update the information of the selected proposal.
        </p>
    </div>

    {{-- REJECTION NOTICE --}}

    @if($proposal->status === 'rejected')

        <div class="alert alert-danger">

            <h6 class="font-weight-bold">
                <i class="fas fa-exclamation-circle"></i>
                Proposal Rejected
            </h6>

            <p class="mb-1">
                Your proposal has been rejected.
                Please review the remarks below and make the necessary corrections.
            </p>

            <strong>Reviewer Remarks:</strong>

            <p class="mb-0">
                {{ $proposal->review_remarks ?? 'No remarks provided.' }}
            </p>

        </div>

    @endif

    {{-- EDIT PROPOSAL FORM --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Proposal Information</h5>
        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('manage-proposal.proposals.update', $proposal->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('manage-proposal._form')

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('manage-proposal.proposals.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>
                        Cancel

                    </a>

                    <button type="submit" class="btn btn-danger">

                        <i class="fas fa-save"></i>
                        Update Proposal

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- RESUBMISSION SECTION --}}

    @if($proposal->status === 'rejected')

        <div class="card shadow-sm border-danger">

            <div class="card-header bg-white">

                <h5 class="mb-0 text-danger">
                    <i class="fas fa-paper-plane"></i>
                    Resubmit Proposal
                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">
                    After correcting your proposal, click the button below
                    to resubmit it for review.
                </p>

                <div class="alert alert-warning">

                    <i class="fas fa-info-circle"></i>

                    Previous approval and rejection history will be preserved.

                </div>

                <form method="POST"
                      action="{{ route('manage-proposal.proposals.submit', $proposal->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-success"
                            onclick="return confirm('Are you sure you want to resubmit this proposal?')">

                        <i class="fas fa-paper-plane"></i>
                        Resubmit Proposal

                    </button>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection
