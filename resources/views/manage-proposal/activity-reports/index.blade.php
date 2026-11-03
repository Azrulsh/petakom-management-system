@extends('layouts.header-sidebar')

@section('title', 'Activity Reports')

@section('content')

@php
    $user = auth()->user();

    $isAdmin = $user->isAdmin();

    $isCommittee = $user->isCommittee()
        || $user->isActiveCommitteeMember();

    $isReviewer = $isAdmin
        || $user->isLecturer()
        || $user->isCoordinator();
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
   <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Activity Reports</h3>

            <p class="text-muted">
                Manage reports and evaluate completed PETAKOM activities.
            </p>
        </div>

        <div class="d-flex" style="gap: 10px;">

            @if($isReviewer)

                <a href="{{ route('manage-proposal.activity-reports.history') }}"
                class="btn btn-outline-dark">
                    <i class="fas fa-history"></i>
                    Approval History
                </a>

            @endif

            @if($isAdmin || $isCommittee)

                <a href="{{ route('manage-proposal.activity-reports.create') }}"
                class="btn btn-danger">
                    + Create Activity Report
                </a>

            @endif

        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- ACTIVITY REPORT TABLE --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Activity Report List</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Activity Title</th>
                            <th>Related Proposal</th>
                            <th>Activity Date</th>
                            <th>Prepared By</th>
                            <th>Attendance</th>
                            <th>Expenditure</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($activityReports as $report)

                            @php
                                $canManageReport = $isAdmin ||
                                    (
                                        $isCommittee &&
                                        (int) $report->created_by === (int) $user->id
                                    );

                                $canEditReport = $canManageReport &&
                                    (
                                        $isAdmin ||
                                        in_array($report->status, ['draft', 'rejected'], true)
                                    );

                                $canSubmitReport = $canManageReport &&
                                    in_array($report->status, ['draft', 'rejected'], true);

                                $statusClasses = [
                                    'draft' => 'secondary',
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                ];

                                $statusClass = $statusClasses[$report->status] ?? 'secondary';
                            @endphp

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($activityReports->currentPage() - 1) * $activityReports->perPage() }}
                                </td>

                                <td>
                                    <strong>{{ $report->activity_title }}</strong>
                                </td>

                                <td>
                                    {{ optional($report->proposal)->title ?? 'Independent Activity' }}
                                </td>

                                <td>
                                    {{ $report->activity_date->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $report->prepared_by }}
                                </td>

                                <td>
                                    {{ number_format($report->actual_attendance) }}
                                </td>

                                <td>
                                    RM {{ number_format($report->actual_expenditure, 2) }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    <div class="d-flex flex-wrap" style="gap:5px;">

                                        {{-- VIEW / REVIEW --}}
                                        <a href="{{ route('manage-proposal.activity-reports.show', $report->id) }}"
                                           class="btn btn-sm btn-info">

                                            @if($isReviewer && $report->status === 'pending')
                                                Review
                                            @else
                                                View
                                            @endif

                                        </a>

                                        {{-- EDIT --}}
                                        @if($canEditReport)

                                            <a href="{{ route('manage-proposal.activity-reports.edit', $report->id) }}"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                        @endif

                                        {{-- SUBMIT / RESUBMIT --}}
                                        @if($canSubmitReport)

                                            <form action="{{ route('manage-proposal.activity-reports.submit', $report->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Submit this activity report for approval?')">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-primary">

                                                    {{ $report->status === 'rejected' ? 'Resubmit' : 'Submit' }}

                                                </button>

                                            </form>

                                        @endif

                                        {{-- DELETE --}}
                                        @if($canEditReport)

                                            <form action="{{ route('manage-proposal.activity-reports.destroy', $report->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this activity report?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center text-muted py-4">

                                    No activity reports available.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-end">
                {{ $activityReports->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
