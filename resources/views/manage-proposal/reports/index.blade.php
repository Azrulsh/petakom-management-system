
@extends('layouts.header-sidebar')

@section('title', 'Generate Reports')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="font-weight-bold">Generate Reports</h3>

        <p class="text-muted">
            PETAKOM proposal and activity performance overview.
        </p>

    </div>


    {{-- Proposal Statistics --}}

    <h5 class="font-weight-bold mb-3">Proposal Overview</h5>

    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-left-danger">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Proposals</p>
                    <h3 class="font-weight-bold">
                        {{ number_format($totalProposals) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Draft</p>
                    <h3 class="font-weight-bold text-secondary">
                        {{ number_format($draftProposals) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending</p>
                    <h3 class="font-weight-bold text-warning">
                        {{ number_format($pendingProposals) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Approved</p>
                    <h3 class="font-weight-bold text-success">
                        {{ number_format($approvedProposals) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Rejected</p>
                    <h3 class="font-weight-bold text-danger">
                        {{ number_format($rejectedProposals) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Activity Report Statistics --}}

    <h5 class="font-weight-bold mb-3 mt-4">Activity Report Overview</h5>

    <div class="row">

        <div class="col-md-4 mb-3">

            <div class="card shadow-sm">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Activity Reports
                    </p>

                    <h3 class="font-weight-bold">
                        {{ number_format($totalActivityReports) }}
                    </h3>

                </div>
            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card shadow-sm">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Attendance
                    </p>

                    <h3 class="font-weight-bold">
                        {{ number_format($totalAttendance) }}
                    </h3>

                </div>
            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card shadow-sm">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Actual Expenditure
                    </p>

                    <h3 class="font-weight-bold">
                        RM {{ number_format($totalExpenditure, 2) }}
                    </h3>

                </div>
            </div>

        </div>

    </div>


    {{-- Proposal Report Table --}}

    <div class="card shadow-sm mt-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Proposal Report</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Proposal Title</th>
                            <th>Proposed Date</th>
                            <th>Estimated Budget</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($proposals as $proposal)

                            <tr>

                                <td>{{ $loop->iteration + ($proposals->currentPage() - 1) * $proposals->perPage() }}</td>

                                <td>{{ $proposal->title }}</td>

                                <td>{{ $proposal->proposed_date->format('d M Y') }}</td>

                                <td>
                                    RM {{ number_format($proposal->estimated_budget, 2) }}
                                </td>

                                <td>{{ ucfirst($proposal->status) }}</td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No proposal records available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $proposals->links() }}

        </div>

    </div>


    {{-- Activity Report Table --}}

    <div class="card shadow-sm mt-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Activity Performance Report</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">

                        <tr>
                            <th>No.</th>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Attendance</th>
                            <th>Actual Expenditure</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($activityReports as $report)

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($activityReports->currentPage() - 1) * $activityReports->perPage() }}
                                </td>

                                <td>{{ $report->activity_title }}</td>

                                <td>{{ $report->activity_date->format('d M Y') }}</td>

                                <td>{{ number_format($report->actual_attendance) }}</td>

                                <td>
                                    RM {{ number_format($report->actual_expenditure, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No activity reports available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $activityReports->links() }}

        </div>

    </div>

</div>

@endsection
