
@extends('layouts.header-sidebar')

@section('title', 'Approval History')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Approval History</h3>
            <p class="text-muted">
                View all proposal review and approval records.
            </p>
        </div>

        <a href="{{ route('proposal-approvals.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left"></i>
            Pending Proposals

        </a>

    </div>


{{-- APPROVAL HISTORY TABS --}}

<ul class="nav nav-tabs mb-4">

    <li class="nav-item">
        <a class="nav-link active"
           href="{{ route('proposal-approvals.history.index') }}">

            <i class="fas fa-file-alt mr-1"></i>
            Proposal History

        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link"
           href="{{ route('manage-proposal.activity-reports.history') }}">

            <i class="fas fa-clipboard-check mr-1"></i>
            Activity Report History

        </a>
    </li>

</ul>

    {{-- SEARCH AND FILTER --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('proposal-approvals.history.index') }}">

                <div class="row g-2">

                    <div class="col-md-7">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search proposal..."
                               value="{{ request('search') }}">

                    </div>

                    <div class="col-md-3">

                        <select name="status" class="form-select">

                            <option value="">All Status</option>

                            <option value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

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

                    <div class="col-md-2">

                        <button type="submit"
                                class="btn btn-danger w-100">

                            <i class="fas fa-search"></i>
                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- HISTORY TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No.</th>
                            <th>Proposal Title</th>
                            <th>Prepared By</th>
                            <th>Current Status</th>
                            <th>Total Reviews</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($proposals as $proposal)

                            <tr>

                                <td>
                                    {{ $proposals->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $proposal->title }}
                                </td>

                                <td>
                                    {{ $proposal->prepared_by }}
                                </td>

                                <td>

                                    @if($proposal->status === 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($proposal->status === 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @elseif($proposal->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($proposal->status) }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $proposal->reviews_count }}
                                </td>

                                <td>

                                    <a href="{{ route(
                                        'proposal-approvals.history.show',
                                        $proposal
                                    ) }}"
                                       class="btn btn-sm btn-danger">

                                        <i class="fas fa-history"></i>
                                        View History

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center text-muted py-4">

                                    <i class="fas fa-history fa-2x mb-2"></i>

                                    <p class="mb-0">
                                        No approval history available.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $proposals->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
