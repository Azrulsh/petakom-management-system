@extends('layouts.header-sidebar')

@section('title', 'Proposal Review')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Proposal Review</h3>
            <p class="text-muted">
                Review and manage submitted activity proposals.
            </p>
        </div>

    </div>


    {{-- SUCCESS / ERROR MESSAGE --}}

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


    {{-- SEARCH --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('proposal-approvals.index') }}">

                <div class="row g-2">

                    <div class="col-md-10">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search proposal title, venue or prepared by..."
                               value="{{ request('search') }}">

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


    {{-- PROPOSAL TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No.</th>
                            <th>Proposal Title</th>
                            <th>Category</th>
                            <th>Activity Date</th>
                            <th>Venue</th>
                            <th>Prepared By</th>
                            <th>Status</th>
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
                                    {{ $proposal->activity_category ?? '-' }}
                                </td>

                                <td>
                                    {{ $proposal->proposed_date?->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $proposal->venue }}
                                </td>

                                <td>
                                    {{ $proposal->prepared_by }}
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                </td>

                                <td>

                                    <a href="{{ route('proposal-approvals.show', $proposal) }}"
                                       class="btn btn-sm btn-danger">

                                        <i class="fas fa-eye"></i>
                                        Review

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center text-muted py-4">

                                    <i class="fas fa-inbox fa-2x mb-2"></i>

                                    <p class="mb-0">
                                        No pending proposals available.
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
