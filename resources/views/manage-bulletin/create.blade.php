@extends('layouts.header-sidebar')

@section('title', 'Create Bulletin')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Create Bulletin</h3>
        <p class="text-muted mb-0">
            Create a PETAKOM or Faculty announcement.
        </p>
    </div>

    {{-- Validation Errors --}}
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

            <form method="POST" action="{{ route('manage-bulletin.store') }}" enctype="multipart/form-data">
                @csrf

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
                               value="{{ old('title') }}"
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

                            <option value="">-- Select Type --</option>

                            <option value="petakom"
                                @selected(old('type') === 'petakom')>
                                PETAKOM Bulletin
                            </option>

                            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())
                                <option value="faculty"
                                    @selected(old('type') === 'faculty')>
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
                                @selected(old('status', 'draft') === 'draft')>
                                Save as Draft
                            </option>

                            <option value="published"
                                @selected(old('status') === 'published')>
                                Publish Now
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
                                  required
                                  placeholder="Enter bulletin announcement details...">{{ old('content') }}</textarea>
                    </div>

                    {{-- Optional Attachment --}}
                    <div class="col-12">
                        <label for="attachment" class="form-label">
                            Attachment (Optional)
                        </label>

                        <input type="file"
                               name="attachment"
                               id="attachment"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png">

                        <div class="form-text">
                            Supported formats: PDF, JPG, JPEG, PNG.
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('manage-bulletin.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Bulletin
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

@endsection
