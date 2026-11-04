@extends('layouts.header-sidebar')

@section('title', 'Edit Bulletin')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Edit Bulletin</h3>
        <p class="text-muted mb-0">
            Update PETAKOM or Faculty announcement.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('bulletin.update', $bulletin->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Title --}}
                    <div class="col-12">
                        <label for="title" class="form-label">
                            Bulletin Title
                        </label>

                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control"
                               value="{{ old('title', $bulletin->title) }}"
                               maxlength="255"
                               required>
                    </div>

                    {{-- Bulletin Type --}}
                    <div class="col-md-6">
                        <label for="type" class="form-label">
                            Bulletin Type
                        </label>

                        <select name="type"
                                id="type"
                                class="form-select"
                                required>

                            <option value="petakom"
                                @selected(old('type', $bulletin->type) === 'petakom')>
                                PETAKOM Bulletin
                            </option>

                            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())
                                <option value="faculty"
                                    @selected(old('type', $bulletin->type) === 'faculty')>
                                    Faculty Bulletin
                                </option>
                            @endif

                        </select>
                    </div>

                    {{-- Publication Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Publication Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select"
                                required>

                            <option value="draft"
                                @selected(old('status', $bulletin->status) === 'draft')>
                                Draft
                            </option>

                            <option value="published"
                                @selected(old('status', $bulletin->status) === 'published')>
                                Published
                            </option>

                        </select>
                    </div>

                    {{-- Content --}}
                    <div class="col-12">
                        <label for="content" class="form-label">
                            Bulletin Content
                        </label>

                        <textarea name="content"
                                  id="content"
                                  class="form-control"
                                  rows="7"
                                  required>{{ old('content', $bulletin->content) }}</textarea>
                    </div>

                    {{-- Existing Attachment --}}
                    @if($bulletin->attachment)
                        <div class="col-12">
                            <label class="form-label">
                                Current Attachment
                            </label>

                            <div>
                                <a href="{{ asset('storage/' . $bulletin->attachment) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-paperclip me-1"></i>
                                    View Current Attachment
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- New Attachment --}}
                    <div class="col-12">
                        <label for="attachment" class="form-label">
                            Replace Attachment (Optional)
                        </label>

                        <input type="file"
                               name="attachment"
                               id="attachment"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png">

                        <div class="form-text">
                            Leave empty to keep the current attachment.
                            Maximum file size: 5 MB.
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('bulletin.show', $bulletin->id) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Bulletin
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

@endsection
