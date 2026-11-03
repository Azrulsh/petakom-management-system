@extends('layouts.header-sidebar')

@section('title', 'Create Election')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Create Election</h3>
        <p class="text-muted mb-0">
            Create a new PETAKOM committee election.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('manage-election.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <strong>Please check your election information.</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">

                    {{-- Election Title --}}
                    <div class="col-md-12">
                        <label class="form-label">
                            Election Title
                        </label>

                        <input type="text"
                               name="title"
                               class="form-control"
                               value="{{ old('title') }}"
                               required>
                    </div>

                    {{-- Academic Session --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Academic Session
                        </label>

                        <input type="text"
                               name="academic_session"
                               class="form-control"
                               placeholder="e.g. 2026/2027"
                               value="{{ old('academic_session') }}"
                               required>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="3">{{ old('description') }}</textarea>
                    </div>

                    {{-- Nomination Period --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Nomination Start
                        </label>

                        <input type="datetime-local"
                               name="nomination_start"
                               class="form-control"
                               value="{{ old('nomination_start') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Nomination End
                        </label>

                        <input type="datetime-local"
                               name="nomination_end"
                               class="form-control"
                               value="{{ old('nomination_end') }}"
                               required>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('manage-election.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Election
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
