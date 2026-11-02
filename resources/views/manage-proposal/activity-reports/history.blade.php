
@extends('layouts.header-sidebar')

@section('title', 'Activity Report Approval History')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">
                Activity Report Approval History
            </h3>

            <p class="text-muted">
                View previously approved and rejected activity reports.
            </p>
        </div>

        <a href="{{ route('manage-proposal.activity-reports.index') }}"
           class="btn btn-secondary">
            Back to Activity Reports
        </a>

    </div>

    
{{-- APPROVAL HISTORY TABS --}}

<ul class="nav nav-tabs mb-4">

    <li class="nav-item">
        <a class="nav-link"
           href="{{ route('proposal-approvals.history.index') }}">

            <i class="fas fa-file-alt mr-1"></i>
            Proposal History

        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link active"
           href="{{ route('manage-proposal.activity-reports.history') }}">

            <i class="fas fa-clipboard-check mr-1"></i>
            Activity Report History

        </a>
    </li>

</ul>

    {{-- FILTER --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('manage-proposal.activity-reports.history') }}">

                <div class="row">

                    <div class="col-md-6 mb-2">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search activity or reviewer..."
                               value="{{ request('search') }}">

                    </div>

                    <div class="col-md-3 mb-2">

                        <select name="status" class="form-control">

                            <option value="">All Decisions</option>

                            <option value="approved"
                                {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="rejected"
                                {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3 mb-2">

                        <button type="submit"
                                class="btn btn-danger">
                            Search
                        </button>

                        <a href="{{ route('manage-proposal.activity-reports.history') }}"
                           class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- HISTORY TABLE --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Approval Records
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">

                        <tr>
                            <th>No.</th>
                            <th>Activity Title</th>
                            <th>Related Proposal</th>
                            <th>Prepared By</th>
                            <th>Decision</th>
                            <th>Reviewed By</th>
                            <th>Reviewed At</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($activityReports as $report)

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($activityReports->currentPage() - 1) * $activityReports->perPage() }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $report->activity_title }}
                                    </strong>
                                </td>

                                <td>
                                    {{ optional($report->proposal)->title ?? 'Independent Activity' }}
                                </td>

                                <td>
                                    {{ $report->prepared_by }}
                                </td>

                                <td>

                                    @if($report->status === 'approved')

                                        <span class="badge badge-success">
                                            Approved
                                        </span>

                                    @else

                                        <span class="badge badge-danger">
                                            Rejected
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ optional($report->reviewer)->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $report->reviewed_at
                                        ? $report->reviewed_at->format('d M Y, h:i A')
                                        : 'N/A' }}
                                </td>

                                <td>

                                    <a href="{{ route('manage-proposal.activity-reports.show', $report->id) }}"
                                       class="btn btn-sm btn-info">

                                        View Details

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center text-muted py-4">

                                    No approval records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end">

                {{ $activityReports->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
