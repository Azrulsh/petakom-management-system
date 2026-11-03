@extends('layouts.header-sidebar')

@section('title', 'Edit Activity')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Edit Activity</h3>
        <p class="text-muted mb-0">
            Update PETAKOM activity information.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('manage-activity.update', $activity->id) }}"method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Activity Name</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $activity->title) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <input type="text"
                               name="activity_category"
                               class="form-control"
                               value="{{ old('activity_category', $activity->activity_category) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Venue</label>
                        <input type="text"
                               name="venue"
                               class="form-control"
                               value="{{ old('venue', $activity->venue) }}"
                               required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ old('description', $activity->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ old('start_date', $activity->start_date->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ old('end_date', $activity->end_date?->format('Y-m-d')) }}">
                        
                        @error('end_date')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror 
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="time"
                               name="start_time"
                               class="form-control"
                               value="{{ old('start_time', $activity->start_time ? substr($activity->start_time, 0, 5) : '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="time"
                               name="end_time"
                               class="form-control"
                               value="{{ old('end_time', $activity->end_time ? substr($activity->end_time, 0, 5) : '') }}">

                        @error('end_time')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
         

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('manage-activity.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Activity
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
