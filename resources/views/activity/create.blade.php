@extends('layouts.header-sidebar')

@section('title', 'Add Activity')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Add Activity</h3>
        <p class="text-muted mb-0">
            Create a new PETAKOM activity.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form id="activityForm" action="{{ route('activity.store') }}" method="POST">
                @csrf

                <div class="row g-3">
  
                    {{-- Activity Creation Type --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            Activity Creation Type
                        </label>

                        <select id="creation_type" class="form-select">
                            <option value="manual"
                                {{ old('proposal_id') ? '' : 'selected' }}>
                                Manual Activity
                            </option>

                            <option value="proposal"
                                {{ old('proposal_id') ? 'selected' : '' }}>
                                From Approved Proposal
                            </option>
                        </select>
                    </div>

                    {{-- Approved Proposal Selection --}}
                   <div class="col-md-12" id="proposal_section"
                    style="{{ old('proposal_id') ? '' : 'display: none;' }}">

                        <label for="proposal_id" class="form-label">
                            Select Approved Proposal
                        </label>

                       <select name="proposal_id"
                               id="proposal_id"
                               class="form-select"
                               {{ old('proposal_id') ? '' : 'disabled' }}>

                            <option value="">-- Select Proposal --</option>

                            @foreach($proposals as $proposal)
                                <option
                                    value="{{ $proposal->id }}"
                                    @selected(old('proposal_id') == $proposal->id)
                                    data-title="{{ $proposal->title }}"
                                    data-category="{{ $proposal->activity_category }}"
                                    data-description="{{ $proposal->background }}"
                                    data-start-date="{{ $proposal->proposed_date?->format('Y-m-d') }}"
                                    data-end-date="{{ $proposal->end_date?->format('Y-m-d') }}"
                                    data-venue="{{ $proposal->venue }}"
                                >
                                    {{ $proposal->title }}
                                </option>
                            @endforeach

                        </select>

                        @error('proposal_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($proposals->isEmpty())
                            <small class="text-muted">
                                No approved proposals available.
                            </small>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Activity Name</label>
                        <input type="text" name="title"
                               class="form-control"
                               value="{{ old('title') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <input type="text" name="activity_category"
                               class="form-control"
                               value="{{ old('activity_category') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue"
                               class="form-control"
                               value="{{ old('venue') }}"
                               required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}">

                        @error('end_date')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror 
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time"
                               class="form-control"
                               value="{{ old('start_time') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time"
                               class="form-control"
                               value="{{ old('end_time') }}">
                        
                        @error('end_time')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror                        
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('activity.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                   <button type="submit" class="btn btn-primary">
                        Save Activity
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const creationType = document.getElementById('creation_type');
    const proposalSection = document.getElementById('proposal_section');
    const proposalSelect = document.getElementById('proposal_id');

    creationType.addEventListener('change', function () {

        if (this.value === 'proposal') {
            proposalSection.style.display = 'block';
            proposalSelect.disabled = false;
            proposalSelect.required = true;
        } else {
            proposalSection.style.display = 'none';
            proposalSelect.disabled = true;
            proposalSelect.required = false;
            proposalSelect.value = '';
        }

    });

    // Initialize state after Laravel validation redirect
    function initializeCreationType() {
        const isProposal = creationType.value === 'proposal';

        proposalSection.style.display = isProposal ? 'block' : 'none';
        proposalSelect.disabled = !isProposal;
        proposalSelect.required = isProposal;

        if (!isProposal) {
            proposalSelect.value = '';
        }
    }

    initializeCreationType();


    // Activity form fields
    const fields = {
        title: document.querySelector('[name="title"]'),
        category: document.querySelector('[name="activity_category"]'),
        description: document.querySelector('[name="description"]'),
        startDate: document.querySelector('[name="start_date"]'),
        endDate: document.querySelector('[name="end_date"]'),
        venue: document.querySelector('[name="venue"]')
    };

    // Save manual form values
    let manualValues = {};

    function saveManualValues() {
        Object.keys(fields).forEach(key => {
            manualValues[key] = fields[key].value;
        });
    }

    function restoreManualValues() {
        Object.keys(fields).forEach(key => {
            fields[key].value = manualValues[key] ?? '';
        });
    }

    // When creation type changes
    creationType.addEventListener('change', function () {
        if (this.value === 'proposal') {
            saveManualValues();
        } else {
            restoreManualValues();
        }
    });

    // Auto-fill selected proposal
    proposalSelect.addEventListener('change', function () {

        const selected = this.options[this.selectedIndex];

        if (!this.value) {
            Object.values(fields).forEach(field => {
                field.value = '';
            });
            return;
        }

        fields.title.value = selected.dataset.title || '';
        fields.category.value = selected.dataset.category || '';
        fields.description.value = selected.dataset.description || '';
        fields.startDate.value = selected.dataset.startDate || '';
        fields.endDate.value = selected.dataset.endDate || '';
        fields.venue.value = selected.dataset.venue || '';
    });

});
</script>

@endsection
