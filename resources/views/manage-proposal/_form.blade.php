
<div class="form-group">
    <label>Proposal Title <span class="text-danger">*</span></label>

    <input type="text"
           name="title"
           class="form-control"
           value="{{ old('title', $proposal->title ?? '') }}"
           placeholder="Enter proposal title"
           required>
</div>


<div class="form-group">
    <label>Activity Category</label>

    <select name="activity_category" class="form-control">

        <option value="">Select Category</option>

        @foreach([
            'Seminar',
            'Workshop',
            'Competition',
            'Community Service',
            'Sports',
            'Meeting',
            'Others'
        ] as $category)

            <option value="{{ $category }}"
                {{ old('activity_category', $proposal->activity_category ?? '') === $category ? 'selected' : '' }}>
                {{ $category }}
            </option>

        @endforeach

    </select>
</div>


<div class="form-group">
    <label>Background of Activity</label>

    <textarea name="background"
              class="form-control"
              rows="4"
              placeholder="Describe the background of the proposed activity">{{ old('background', $proposal->background ?? '') }}</textarea>
</div>


<div class="form-group">
    <label>Objectives <span class="text-danger">*</span></label>

    <textarea name="objectives"
              class="form-control"
              rows="4"
              placeholder="State the objectives of the activity"
              required>{{ old('objectives', $proposal->objectives ?? '') }}</textarea>
</div>


<hr>

<h5 class="font-weight-bold mb-3">Activity Details</h5>


<div class="form-group">
    <label>Proposed Start Date <span class="text-danger">*</span></label>

    <input type="date"
           name="proposed_date"
           class="form-control"
           value="{{ old('proposed_date', isset($proposal) ? $proposal->proposed_date->format('Y-m-d') : '') }}"
           required>
</div>


<div class="form-group">
    <label>End Date</label>

    <input type="date"
           name="end_date"
           class="form-control"
           value="{{ old('end_date', isset($proposal) && $proposal->end_date ? $proposal->end_date->format('Y-m-d') : '') }}">
</div>


<div class="form-group">
    <label>Venue <span class="text-danger">*</span></label>

    <input type="text"
           name="venue"
           class="form-control"
           value="{{ old('venue', $proposal->venue ?? '') }}"
           placeholder="Enter activity venue"
           required>
</div>


<hr>

<h5 class="font-weight-bold mb-3">Target Participants & Budget</h5>


<div class="form-group">
    <label>Target Participants</label>

    <input type="number"
           name="target_participants"
           class="form-control"
           min="0"
           value="{{ old('target_participants', $proposal->target_participants ?? 0) }}">
</div>


<div class="form-group">
    <label>Estimated Budget (RM)</label>

    <input type="number"
           name="estimated_budget"
           class="form-control"
           min="0"
           step="0.01"
           value="{{ old('estimated_budget', $proposal->estimated_budget ?? 0) }}">
</div>


<hr>

<h5 class="font-weight-bold mb-3">Prepared By</h5>


<div class="form-group">
    <label>Prepared By <span class="text-danger">*</span></label>

    <input type="text"
           name="prepared_by"
           class="form-control"
           value="{{ old('prepared_by', $proposal->prepared_by ?? optional(auth()->user())->name ?? '') }}"
           placeholder="Enter your name"
           required>
</div>
