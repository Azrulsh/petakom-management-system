@extends('layouts.header-sidebar')

@section('title', 'Manage Activities')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Manage Activities</h3>
            <p class="text-muted mb-0">
                Manage and monitor PETAKOM activities.
            </p>
        </div>

        {{-- Add Activity Button --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator() || auth()->user()->isCommittee())

            <a href="{{ route('activity.create') }}"
            class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Add Activity

            </a>

        @endif

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" 
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Activity List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Activity List</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Activity Name</th>
                            <th>Category</th>
                            <th>Start Date</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>
                                    {{ $activities->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $activity->title }}
                                </td>

                                <td>
                                    {{ $activity->activity_category ?? '-' }}
                                </td>

                                <td>
                                    {{ $activity->start_date->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $activity->venue }}
                                </td>

                                <td>                              
                                    @if(
                                        auth()->user()->isAdmin()
                                        || auth()->user()->isCoordinator()
                                        || (
                                            auth()->user()->isCommittee()
                                            && $activity->user_id === auth()->id()
                                        )
                                    )

                                        <form action="{{ route('activity.update-status', $activity->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <select name="status"
                                                    class="form-select form-select-sm"
                                                    onchange="this.form.submit()">

                                                <option value="upcoming"
                                                    @selected($activity->status === 'upcoming')>
                                                    Upcoming
                                                </option>

                                                <option value="ongoing"
                                                    @selected($activity->status === 'ongoing')>
                                                    Ongoing
                                                </option>

                                                <option value="completed"
                                                    @selected($activity->status === 'completed')>
                                                    Completed
                                                </option>

                                            </select>
                                        </form>

                                    @else
                                        <span class="text-muted">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $activity->user->name ?? '-' }}
                                </td>
  
                                <td>
                                    <a href="{{ route('activity.show', $activity->id) }}"
                                    class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> View
                                    </a>

                                    
                                    @if(
                                        auth()->user()->isAdmin()
                                        || auth()->user()->isCoordinator()
                                        || (
                                            auth()->user()->isCommittee()
                                            && $activity->user_id === auth()->id()
                                        )
                                    )
                                        <a href="{{ route('activity.edit', $activity->id) }}"
                                        class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @endif

 
                                    @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())
                                        <form action="{{ route('activity.destroy', $activity->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this activity?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No activities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $activities->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
