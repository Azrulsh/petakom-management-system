@extends('layouts.header-sidebar')

@section('title', 'Election Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Election Details</h3>
            <p class="text-muted mb-0">
                View PETAKOM committee election information.
            </p>
        </div>

        <a href="{{ route('manage-election.candidates.index', $election->id) }}"
            class="btn btn-primary">
                <i class="fas fa-users me-1"></i>
                View Candidates
        </a>
        
        <a href="{{ route('manage-election.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    {{-- Election Information --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    {{ $election->title }}
                </h4>

                @php
                    $statusClass = match($election->status) {
                        'draft' => 'bg-secondary',
                        'open' => 'bg-success',
                        'closed' => 'bg-warning text-dark',
                        'completed' => 'bg-primary',
                        default => 'bg-secondary',
                    };
                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ ucfirst($election->status) }}
                </span>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <strong>Academic Session</strong>
                    <p>{{ $election->academic_session }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Created By</strong>
                    <p>{{ $election->creator?->name ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Nomination Start</strong>
                    <p>
                        {{ $election->nomination_start->format('d M Y, h:i A') }}
                    </p>
                </div>

                <div class="col-md-6">
                    <strong>Nomination End</strong>
                    <p>
                        {{ $election->nomination_end->format('d M Y, h:i A') }}
                    </p>
                </div>

                <div class="col-12">
                    <strong>Description</strong>
                    <p class="mt-2">
                        {{ $election->description ?? 'No description available.' }}
                    </p>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection
