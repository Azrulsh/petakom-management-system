
<div class="form-group">

    <label>Related Proposal</label>

    <select name="proposal_id" class="form-control">

        <option value="">Select Related Proposal (Optional)</option>

        @foreach($proposals as $proposal)

            <option value="{{ $proposal->id }}"
                {{ (string) old('proposal_id', $activityReport->proposal_id ?? '') === (string) $proposal->id ? 'selected' : '' }}>

                {{ $proposal->title }}

            </option>

        @endforeach

    </select>

</div>


<div class="form-group">

    <label>Activity Title <span class="text-danger">*</span></label>

    <input type="text"
           name="activity_title"
           class="form-control"
           value="{{ old('activity_title', $activityReport->activity_title ?? '') }}"
           placeholder="Enter activity title"
           required>

</div>


<div class="form-group">

    <label>Activity Date <span class="text-danger">*</span></label>

    <input type="date"
           name="activity_date"
           class="form-control"
           value="{{ old('activity_date', isset($activityReport) ? $activityReport->activity_date->format('Y-m-d') : '') }}"
           required>

</div>


<div class="form-group">

    <label>Venue</label>

    <input type="text"
           name="venue"
           class="form-control"
           value="{{ old('venue', $activityReport->venue ?? '') }}"
           placeholder="Enter activity venue">

</div>


<hr>

<h5 class="font-weight-bold mb-3">Activity Results</h5>


<div class="form-group">

    <label>Actual Attendance</label>

    <input type="number"
           name="actual_attendance"
           class="form-control"
           min="0"
           value="{{ old('actual_attendance', $activityReport->actual_attendance ?? 0) }}">

</div>


<div class="form-group">

    <label>Actual Expenditure (RM)</label>

    <input type="number"
           name="actual_expenditure"
           class="form-control"
           min="0"
           step="0.01"
           value="{{ old('actual_expenditure', $activityReport->actual_expenditure ?? 0) }}">

</div>


<hr>

<h5 class="font-weight-bold mb-3">Activity Evaluation</h5>


<div class="form-group">

    <label>Activity Summary <span class="text-danger">*</span></label>

    <textarea name="summary"
              class="form-control"
              rows="4"
              placeholder="Provide a summary of the activity"
              required>{{ old('summary', $activityReport->summary ?? '') }}</textarea>

</div>


<div class="form-group">

    <label>Achievements</label>

    <textarea name="achievements"
              class="form-control"
              rows="3"
              placeholder="Describe the achievements">{{ old('achievements', $activityReport->achievements ?? '') }}</textarea>

</div>


<div class="form-group">

    <label>Challenges</label>

    <textarea name="challenges"
              class="form-control"
              rows="3"
              placeholder="Describe challenges faced during the activity">{{ old('challenges', $activityReport->challenges ?? '') }}</textarea>

</div>


<div class="form-group">

    <label>Recommendations</label>

    <textarea name="recommendations"
              class="form-control"
              rows="3"
              placeholder="Suggest improvements for future activities">{{ old('recommendations', $activityReport->recommendations ?? '') }}</textarea>

</div>


<hr>

<h5 class="font-weight-bold mb-3">Report Preparation</h5>


<div class="form-group">

    <label>Prepared By <span class="text-danger">*</span></label>

    <input type="text"
           name="prepared_by"
           class="form-control"
           value="{{ old('prepared_by', $activityReport->prepared_by ?? optional(auth()->user())->name ?? '') }}"
           placeholder="Enter report preparer's name"
           required>

</div>
