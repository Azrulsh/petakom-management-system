@extends('layouts.header-sidebar')

@section('title', 'Manage Proposal')

@section('content')

<div class="container-fluid">

    {{-- Page Heading --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="font-weight-bold">Manage Proposal</h3>

            <p class="text-muted">
                Manage and monitor PETAKOM activity proposals.
            </p>
        </div>

        <a href="{{ route('manage-proposal.proposals.create') }}"
           class="btn btn-danger">
            + Create Proposal
        </a>

    </div>


    {{-- Notifications --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">
                &times;
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">
                &times;
            </button>
        </div>
    @endif


    {{-- Search and Filter --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('manage-proposal.proposals.index') }}">

                <div class="row">

                    <div class="col-md-4 mb-2">

                        <label>Search Proposal</label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search title, venue or preparer..."
                               value="{{ request('search') }}">

                    </div>


                    <div class="col-md-3 mb-2">

                        <label>Category</label>

                        <select name="category" class="form-control">

                            <option value="">All Categories</option>

                            @foreach([
                                'Seminar',
                                'Workshop',
                                'Competition',
                                'Community Service',
                                'Sports',
                                'Meeting',
                                'Others'
                            ] as $category)

                                <option value="{{ $category }}"
                                    {{ request('category') === $category ? 'selected' : '' }}>

                                    {{ $category }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 mb-2">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="">All Status</option>

                            <option value="draft"
                                {{ request('status') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="approved"
                                {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="rejected"
                                {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2 mb-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-danger mr-2">
                            Filter
                        </button>

                        <a href="{{ route('manage-proposal.proposals.index') }}"
                           class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Proposal Table --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">Activity Proposal List</h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">

                        <tr>
                            <th>No.</th>
                            <th>Proposal Title</th>
                            <th>Category</th>
                            <th>Proposed Date</th>
                            <th>Estimated Budget</th>
                            <th>Status</th>
                            <th width="260">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($proposals as $proposal)

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($proposals->currentPage() - 1) * $proposals->perPage() }}
                                </td>


                                <td>
                                    <strong>{{ $proposal->title }}</strong>
                                </td>


                                <td>
                                    {{ $proposal->activity_category ?? 'N/A' }}
                                </td>


                                <td>
                                    {{ $proposal->proposed_date->format('d M Y') }}
                                </td>


                                <td>
                                    RM {{ number_format($proposal->estimated_budget, 2) }}
                                </td>


                                <td>

                                    @if($proposal->status === 'approved')

                                        <span class="badge badge-success">
                                            Approved
                                        </span>

                                    @elseif($proposal->status === 'pending')

                                        <span class="badge badge-warning">
                                            Pending
                                        </span>

                                    @elseif($proposal->status === 'rejected')

                                        <span class="badge badge-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge badge-secondary">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('manage-proposal.proposals.show', $proposal->id) }}"
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>


                                    @if(auth()->user()->isAdmin() ||
                                        (
                                            (int) $proposal->user_id === (int) auth()->id() &&
                                            in_array($proposal->status, ['draft', 'rejected'], true)
                                        )
                                    )
                                        <a href="{{ route('manage-proposal.proposals.edit', $proposal->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                    @endif


                                    @if(
                                        (auth()->user()->isAdmin() ||
                                        (int) $proposal->user_id === (int) auth()->id())
                                        && in_array($proposal->status, ['draft', 'rejected'], true)
                                    )

                                        <form action="{{ route('manage-proposal.proposals.submit', $proposal->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Submit this proposal for approval?')">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm btn-success">
                                                Submit
                                            </button>

                                        </form>

                                    @endif

                                    @if(
                                        auth()->user()->isAdmin() ||
                                        (
                                            (int) $proposal->user_id === (int) auth()->id() &&
                                            in_array($proposal->status, ['draft', 'rejected'], true)
                                        )
                                    )

                                        <form action="{{ route('manage-proposal.proposals.destroy', $proposal->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this proposal?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>

                                        </form>

                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center text-muted py-4">

                                    No proposals found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div class="d-flex justify-content-end">

                {{ $proposals->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
