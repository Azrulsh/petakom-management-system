
@extends('layouts.header-sidebar')

@section('title', 'Proposal Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Proposal Details</h3>

            <p class="text-muted">
                View complete activity proposal information.
            </p>
        </div>

        <a href="{{ route('manage-proposal.proposals.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">{{ $proposal->title }}</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Proposal Title</th>
                    <td>{{ $proposal->title }}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>{{ $proposal->activity_category ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Background</th>
                    <td>{{ $proposal->background ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Objectives</th>
                    <td>{{ $proposal->objectives }}</td>
                </tr>

                <tr>
                    <th>Start Date</th>
                    <td>{{ $proposal->proposed_date->format('d M Y') }}</td>
                </tr>

                <tr>
                    <th>End Date</th>
                    <td>{{ $proposal->end_date?->format('d M Y') ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Venue</th>
                    <td>{{ $proposal->venue }}</td>
                </tr>

                <tr>
                    <th>Target Participants</th>
                    <td>{{ $proposal->target_participants }}</td>
                </tr>

                <tr>
                    <th>Estimated Budget</th>
                    <td>RM {{ number_format($proposal->estimated_budget, 2) }}</td>
                </tr>

                <tr>
                    <th>Prepared By</th>
                    <td>{{ $proposal->prepared_by }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        {{ ucfirst($proposal->status) }}
                    </td>
                </tr>

                <tr>
                    <th>Review Remarks</th>
                    <td>{{ $proposal->review_remarks ?? 'No remarks available.' }}</td>
                </tr>

            </table>

            <div class="mt-3">

                <a href="{{ route('manage-proposal.proposals.edit', $proposal->id) }}"
                   class="btn btn-warning">
                    Edit Proposal
                </a>

            </div>

        </div>
    </div>


    {{-- Related Activity Reports --}}

    <div class="card shadow-sm mt-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Related Activity Reports</h5>
        </div>

        <div class="card-body">

            @forelse($proposal->activityReports as $report)

                <div class="border-bottom py-2">

                    <strong>{{ $report->activity_title }}</strong>

                    <p class="text-muted mb-1">
                        {{ $report->activity_date->format('d M Y') }}
                    </p>

                    <a href="{{ route('manage-proposal.activity-reports.show', $report->id) }}">
                        View Activity Report
                    </a>

                </div>

            @empty

                <p class="text-muted mb-0">
                    No activity reports have been submitted for this proposal.
                </p>

            @endforelse

        </div>
    </div>

</div>

@endsection
