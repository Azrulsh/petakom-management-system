@extends('layouts.header-sidebar')

@section('title', 'Manage Bulletin')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Manage Bulletin</h3>
            <p class="text-muted mb-0">
                PETAKOM and Faculty Announcements
            </p>
        </div>
    
        @if(
            auth()->user()->isAdmin()
            || auth()->user()->isCoordinator()
            || auth()->user()->isCommittee()
        )
            <a href="{{ route('bulletin.create') }}"
            class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Add Bulletin
            </a>
        @endif

    </div>

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

    {{-- Search and Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('bulletin.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label for="search" class="form-label">
                            Search Bulletin
                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               placeholder="Search by title..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="type" class="form-label">
                            Bulletin Type
                        </label>

                        <select name="type"
                                id="type"
                                class="form-select">

                            <option value="">All Bulletins</option>

                            <option value="petakom"
                                @selected(request('type') === 'petakom')>
                                PETAKOM Bulletin
                            </option>

                            <option value="faculty"
                                @selected(request('type') === 'faculty')>
                                Faculty Bulletin
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('bulletin.index') }}"
                               class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Bulletin List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Bulletin List</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($bulletins as $bulletin)

                            <tr>
                                <td>
                                    {{ $bulletins->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $bulletin->title }}
                                </td>

                                <td>
                                    @if($bulletin->type === 'petakom')
                                        <span class="badge bg-primary">
                                            PETAKOM
                                        </span>
                                    @else
                                        <span class="badge bg-info text-dark">
                                            Faculty
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $bulletin->user?->name ?? '-' }}
                                </td>

                                <td>
                                    @if($bulletin->status === 'published')
                                        <span class="badge bg-success">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $bulletin->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    @php
                                        $user = auth()->user();

                                        $canManage = $user->isAdmin()
                                            || (
                                                $user->isCoordinator()
                                                && $bulletin->user_id === $user->id
                                            )
                                            || (
                                                $user->isCommittee()
                                                && $bulletin->user_id === $user->id
                                                && $bulletin->type === 'petakom'
                                            );
                                    @endphp

                                    <div class="d-flex gap-2">

                                        {{-- View --}}
                                        <a href="{{ route('bulletin.show', $bulletin->id) }}"
                                        class="btn btn-info btn-sm text-white">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>

                                        {{-- Publish / Unpublish --}}
                                        @if($canManage)

                                            <form method="POST"
                                                action="{{ route('bulletin.update-status', $bulletin->id) }}">
                                                @csrf
                                                @method('PATCH')

                                                @if($bulletin->status === 'draft')
                                                    <input type="hidden" name="status" value="published">

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('Publish this bulletin?')">
                                                        Publish
                                                    </button>
                                                @else
                                                    <input type="hidden" name="status" value="draft">

                                                    <button type="submit"
                                                            class="btn btn-warning btn-sm"
                                                            onclick="return confirm('Unpublish this bulletin?')">
                                                        Unpublish
                                                    </button>
                                                @endif

                                            </form>

                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No bulletins found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $bulletins->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
