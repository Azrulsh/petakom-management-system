@extends('layouts.header-sidebar')

@section('title', 'Activity Report Details')

@section('content')

@php
    $user = auth()->user();

    $isAdmin = $user->isAdmin();

    $isCommittee = $user->isCommittee()
        || $user->isActiveCommitteeMember();

    $isReviewer = $isAdmin
        || $user->isLecturer()
        || $user->isCoordinator();

    $canManageReport = $isAdmin || (
        $isCommittee &&
        (int) $activityReport->created_by === (int) $user->id
    );

    $canEditReport = $canManageReport && (
        $isAdmin ||
        in_array($activityReport->status, ['draft', 'rejected'], true)
    );

    $canSubmitReport = $canManageReport &&
        in_array($activityReport->status, ['draft', 'rejected'], true);

    $statusClasses = [
        'draft' => 'secondary',
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
    ];

    $statusClass = $statusClasses[$activityReport->status] ?? 'secondary';
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Activity Report Details</h3>

            <p class="text-muted">
                Complete activity performance and evaluation.
            </p>
        </div>

        <a href="{{ route('proposal.activity-reports.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>

    {{-- NOTIFICATIONS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ACTIVITY INFORMATION --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                {{ $activityReport->activity_title }}
            </h5>

            <span class="badge bg-{{ $statusClass }} p-2">
                {{ ucfirst($activityReport->status) }}
            </span>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Activity Title</th>
                    <td>{{ $activityReport->activity_title }}</td>
                </tr>

                <tr>
                    <th>Related Proposal</th>
                    <td>
                        {{ optional($activityReport->proposal)->title ?? 'Independent Activity' }}
                    </td>
                </tr>

                <tr>
                    <th>Activity Date</th>
                    <td>
                        {{ $activityReport->activity_date->format('d M Y') }}
                    </td>
                </tr>

                <tr>
                    <th>Venue</th>
                    <td>{{ $activityReport->venue ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Actual Attendance</th>
                    <td>
                        {{ number_format($activityReport->actual_attendance) }}
                    </td>
                </tr>

                <tr>
                    <th>Actual Expenditure</th>
                    <td>
                        RM {{ number_format($activityReport->actual_expenditure, 2) }}
                    </td>
                </tr>

                <tr>
                    <th>Activity Summary</th>
                    <td>{{ $activityReport->summary }}</td>
                </tr>

                <tr>
                    <th>Achievements</th>
                    <td>{{ $activityReport->achievements ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Challenges</th>
                    <td>{{ $activityReport->challenges ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Recommendations</th>
                    <td>{{ $activityReport->recommendations ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Prepared By</th>
                    <td>{{ $activityReport->prepared_by }}</td>
                </tr>

                <tr>
                    <th>Created By</th>
                    <td>
                        {{ optional($activityReport->creator)->name ?? 'Legacy Record / Unknown' }}
                    </td>
                </tr>

                <tr>
                    <th>Submitted At</th>
                    <td>
                        {{ $activityReport->submitted_at
                            ? $activityReport->submitted_at->format('d M Y, h:i A')
                            : 'Not submitted' }}
                    </td>
                </tr>

            </table>

            {{-- COMMITTEE ACTIONS --}}
            @if($canManageReport)

                <hr>

                <h5 class="font-weight-bold mb-3">
                    Report Management
                </h5>

                @if($canEditReport)

                    <a href="{{ route('proposal.activity-reports.edit', $activityReport->id) }}"
                       class="btn btn-warning">
                        Edit Report
                    </a>

                @endif

                @if($canSubmitReport)

                    <form action="{{ route('proposal.activity-reports.submit', $activityReport->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Submit this report for approval?')">

                        @csrf

                        <button type="submit" class="btn btn-primary">
                            {{ $activityReport->status === 'rejected' ? 'Resubmit Report' : 'Submit for Approval' }}
                        </button>

                    </form>

                @endif

            @endif

        </div>

    </div>


    {{-- APPROVAL REVIEW SECTION --}}
    @if($isReviewer)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0 font-weight-bold">
                    Activity Report Approval
                </h5>
            </div>

            <div class="card-body">

                @if($activityReport->status === 'pending')

                    <div class="alert alert-warning">
                        This activity report is awaiting approval.
                    </div>

                    {{-- APPROVE FORM --}}
                    <form action="{{ route('proposal.activity-reports.approve', $activityReport->id) }}"
                          method="POST"
                          class="mb-4">

                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Approval Remarks (Optional)
                            </label>

                            <textarea name="review_remarks"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Enter approval remarks...">{{ old('review_remarks') }}</textarea>
                        </div>

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Approve this activity report?')">

                            Approve Report

                        </button>

                    </form>

                    <hr>

                    {{-- REJECT FORM --}}
                    <form action="{{ route('proposal.activity-reports.reject', $activityReport->id) }}"
                          method="POST">

                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">
                                Rejection Remarks
                                <span class="text-danger">*</span>
                            </label>

                            <textarea name="review_remarks"
                                      class="form-control"
                                      rows="4"
                                      required
                                      maxlength="5000"
                                      placeholder="Explain why this report is rejected...">{{ old('review_remarks') }}</textarea>
                        </div>

                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Reject this activity report?')">

                            Reject Report

                        </button>

                    </form>

                @elseif($activityReport->status === 'approved')

                    <div class="alert alert-success">
                        <strong>Activity Report Approved</strong>
                        <p class="mb-0">
                            This report has been successfully approved.
                        </p>
                    </div>

                @elseif($activityReport->status === 'rejected')

                    <div class="alert alert-danger">
                        <strong>Activity Report Rejected</strong>
                        <p class="mb-0">
                            The report was rejected and requires correction.
                        </p>
                    </div>

                @else

                    <div class="alert alert-secondary">
                        This report is still in Draft status.
                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- REVIEW INFORMATION --}}
    @if($activityReport->reviewed_by || $activityReport->review_remarks)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0 font-weight-bold">
                    Review Information
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered mb-0">

                    <tr>
                        <th width="30%">Reviewed By</th>
                        <td>
                            {{ optional($activityReport->reviewer)->name ?? 'Unknown Reviewer' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Reviewed At</th>
                        <td>
                            {{ $activityReport->reviewed_at
                                ? $activityReport->reviewed_at->format('d M Y, h:i A')
                                : 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Review Remarks</th>
                        <td>
                            {{ $activityReport->review_remarks ?? 'No remarks provided.' }}
                        </td>
                    </tr>

                </table>

            </div>

        </div>

    @endif

</div>

@endsection
