@extends('layouts.header-sidebar')

@section('title', 'Candidate Registration')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Candidate Registration</h3>
        <p class="text-muted mb-0">
            Apply as a candidate for {{ $election->title }}.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('election.candidates.store', $election->id) }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Student Name</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $student->full_name }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Academic Session</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $election->academic_session }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Position Applied</label>
                        <select name="position" class="form-select" required>
                            <option value="">-- Select Position --</option>
                            @foreach([
                                'President',
                                'Vice President',
                                'Secretary',
                                'Treasurer',
                                'Committee Member'
                            ] as $position)
                                <option value="{{ $position }}"
                                    @selected(old('position') === $position)>
                                    {{ $position }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Candidate Manifesto</label>
                        <textarea name="manifesto"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Describe your goals and plans if elected...">{{ old('manifesto') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('election.candidates.index', $election->id) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Submit Application
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
