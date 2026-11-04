@extends('layouts.header-sidebar')

@section('title', 'Manage Committee Election')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Manage Committee Election</h3>
            <p class="text-muted mb-0">
                Manage PETAKOM committee elections.
            </p>
        </div>
    </div>
    
    @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())

        <a href="{{ route('election.create') }}"
        class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Add Election

        </a>

    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button" 
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    @error('status')
    <div class="alert alert-danger">
        {{ $message }}
    </div>
    @enderror

    {{-- Election List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Election List</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Election Title</th>
                            <th>Academic Session</th>
                            <th>Nomination Period</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($elections as $election)
                            <tr>
                                <td>
                                    {{ $elections->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $election->title }}
                                </td>

                                <td>
                                    {{ $election->academic_session }}
                                </td>

                                <td>
                                    {{ $election->nomination_start->format('d M Y') }}
                                    -
                                    {{ $election->nomination_end->format('d M Y') }}
                                </td>

                                <td>
                                    @switch($election->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">
                                                Draft
                                            </span>
                                            @break

                                        @case('open')
                                            <span class="badge bg-success">
                                                Open
                                            </span>
                                            @break

                                        @case('closed')
                                            <span class="badge bg-warning text-dark">
                                                Closed
                                            </span>
                                            @break

                                        @case('completed')
                                            <span class="badge bg-primary">
                                                Completed
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($election->status) }}
                                            </span>
                                    @endswitch
                                </td>

                                <td>
                                    {{ $election->creator?->name ?? '-' }}
                                </td>
                          
                                <td>
                                    <a href="{{ route('election.show', $election->id) }}"
                                    class="btn btn-sm btn-info text-white">

                                        <i class="fas fa-eye me-1"></i>
                                        View

                                    </a>
         
                                    @if(
                                        (auth()->user()->isAdmin() || auth()->user()->isCoordinator())
                                        && $election->status === 'draft'
                                    )
                                        <a href="{{ route('election.edit', $election->id) }}"
                                        class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i>
                                            Edit
                                        </a>
                                    @endif

                                    @if(auth()->user()->isAdmin() && $election->status === 'draft')

                                        <form action="{{ route('election.destroy', $election->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this election?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i>
                                                Delete
                                            </button>

                                        </form>

                                    @endif

                                    @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())

                                        @if($election->status === 'draft')
                                            <form action="{{ route('election.update-status', $election->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Publish this election?');">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="open">

                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Publish
                                                </button>
                                            </form>
                                        @elseif($election->status === 'open')
                                            <form action="{{ route('election.update-status', $election->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Close this election?');">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="closed">

                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-lock me-1"></i>
                                                    Close
                                                </button>
                                            </form>
                                        @endif

                                    @endif

                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No elections found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $elections->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
