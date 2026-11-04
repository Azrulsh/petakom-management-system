@extends('layouts.header-sidebar')

@section('title', 'Bulletin Details')

@section('content')

<div class="container-fluid py-4">

   {{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    {{-- Page Title --}}
    <div>
        <h3 class="fw-bold mb-1">Bulletin Details</h3>
        <p class="text-muted mb-0">
            PETAKOM and Faculty Announcement
        </p>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex align-items-center gap-2">
            @php
                $user = auth()->user();

                $canEdit = $user->isAdmin()
                    || ($user->isCoordinator() && $bulletin->user_id === $user->id)
                    || (
                        $user->isCommittee()
                        && $bulletin->user_id === $user->id
                        && $bulletin->type === 'petakom'
                    );
            @endphp

            @if($canEdit)

                <a href="{{ route('bulletin.edit', $bulletin->id) }}"
                class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>
                    Edit Bulletin
                </a>

                <form method="POST"
                    action="{{ route('bulletin.destroy', $bulletin->id) }}"
                    class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this bulletin?')">
                        <i class="fas fa-trash me-1"></i>
                        Delete Bulletin
                    </button>
                </form>

            @endif

            <a href="{{ route('bulletin.index') }}"
            class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            {{-- Bulletin Title --}}
            <h4 class="fw-bold mb-3">
                {{ $bulletin->title }}
            </h4>

            {{-- Type and Status --}}
            <div class="d-flex gap-2 mb-3">

                @if($bulletin->type === 'petakom')
                    <span class="badge bg-primary">
                        PETAKOM
                    </span>
                @else
                    <span class="badge bg-info text-dark">
                        Faculty
                    </span>
                @endif

                @if($bulletin->status === 'published')
                    <span class="badge bg-success">
                        Published
                    </span>
                @else
                    <span class="badge bg-warning text-dark">
                        Draft
                    </span>
                @endif

            </div>

            {{-- Bulletin Information --}}
            <div class="text-muted small mb-4">

                <div>
                    <strong>Created By:</strong>
                    {{ $bulletin->user?->name ?? '-' }}
                </div>

                <div>
                    <strong>Created At:</strong>
                    {{ $bulletin->created_at->format('d M Y, h:i A') }}
                </div>

                @if($bulletin->published_at)
                    <div>
                        <strong>Published At:</strong>
                        {{ $bulletin->published_at->format('d M Y, h:i A') }}
                    </div>
                @endif

            </div>

            <hr>

            {{-- Bulletin Content --}}
            <h5 class="fw-bold mb-3">Announcement</h5>

            <div class="mb-4" style="white-space: pre-line;">{{ $bulletin->content }}</div>

            {{-- Attachment --}}
            @if(
                $bulletin->attachment
                && \Illuminate\Support\Facades\Storage::disk('public')
                    ->exists($bulletin->attachment)
                )
                <hr>

                <h5 class="fw-bold mb-3">Attachment</h5>

                <a href="{{ asset('storage/' . $bulletin->attachment) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-outline-primary">
                    <i class="fas fa-paperclip me-1"></i>
                    View Attachment
                </a>
            @endif

        </div>
    </div>

</div>

@endsection
