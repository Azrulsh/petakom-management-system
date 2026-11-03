@extends('layouts.header-sidebar')

@section('title', 'Activity Details')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Activity Details</h3>
            <p class="text-muted mb-0">
                View PETAKOM activity information.
            </p>
        </div>

        <a href="{{ route('manage-activity.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    {{ $activity->title }}
                </h4>

                @php
                    $statusClass = match($activity->status) {
                        'upcoming' => 'bg-primary',
                        'ongoing' => 'bg-warning text-dark',
                        'completed' => 'bg-success',
                        default => 'bg-secondary',
                    };
                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ ucfirst($activity->status) }}
                </span>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <strong>Category</strong>
                    <p>{{ $activity->activity_category ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Venue</strong>
                    <p>{{ $activity->venue }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Start Date</strong>
                    <p>{{ $activity->start_date->format('d M Y') }}</p>
                </div>

                <div class="col-md-6">
                    <strong>End Date</strong>
                    <p>
                        {{ $activity->end_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <strong>Start Time</strong>
                    <p>{{ $activity->start_time ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>End Time</strong>
                    <p>{{ $activity->end_time ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Created By</strong>
                    <p>{{ $activity->user?->name ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Related Proposal</strong>
                    <p>{{ $activity->proposal?->title ?? 'Manual Activity' }}</p>
                </div>

                <div class="col-12">
                    <strong>Description</strong>
                    <p class="mt-2">
                        {{ $activity->description ?? 'No description available.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
