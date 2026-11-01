
@extends('layouts.header-sidebar')

@section('title', 'Activity Reports')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Activity Reports</h3>

            <p class="text-muted">
                Manage reports and evaluate completed PETAKOM activities.
            </p>
        </div>

        <a href="{{ route('manage-proposal.activity-reports.create') }}"
           class="btn btn-danger">
            + Create Activity Report
        </a>

    </div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


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
                            <th>Attendance</th>
                            <th>Expenditure</th>
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
                                    <strong>{{ $report->activity_title }}</strong>
                                </td>

                                <td>
                                    {{ optional($report->proposal)->title ?? 'Independent Activity' }}
                                </td>

                                <td>
                                    {{ $report->activity_date->format('d M Y') }}
                                </td>

                                <td>
                                    {{ number_format($report->actual_attendance) }}
                                </td>

                                <td>
                                    RM {{ number_format($report->actual_expenditure, 2) }}
                                </td>

                                <td>

                                    <a href="{{ route('manage-proposal.activity-reports.show', $report->id) }}"
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    <a href="{{ route('manage-proposal.activity-reports.edit', $report->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('manage-proposal.activity-reports.destroy', $report->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this activity report?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No activity reports available.
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
