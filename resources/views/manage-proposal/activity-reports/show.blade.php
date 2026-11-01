
@extends('layouts.header-sidebar')

@section('title', 'Activity Report Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Activity Report Details</h3>

            <p class="text-muted">
                Complete activity performance and evaluation.
            </p>
        </div>

        <a href="{{ route('manage-proposal.activity-reports.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">{{ $activityReport->activity_title }}</h5>
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
                    <td>{{ $activityReport->activity_date->format('d M Y') }}</td>
                </tr>

                <tr>
                    <th>Venue</th>
                    <td>{{ $activityReport->venue ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Actual Attendance</th>
                    <td>{{ number_format($activityReport->actual_attendance) }}</td>
                </tr>

                <tr>
                    <th>Actual Expenditure</th>
                    <td>RM {{ number_format($activityReport->actual_expenditure, 2) }}</td>
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
                    <th>Report Status</th>
                    <td>{{ ucfirst($activityReport->status) }}</td>
                </tr>

            </table>


            <a href="{{ route('manage-proposal.activity-reports.edit', $activityReport->id) }}"
               class="btn btn-warning">
                Edit Report
            </a>

        </div>

    </div>

</div>

@endsection
