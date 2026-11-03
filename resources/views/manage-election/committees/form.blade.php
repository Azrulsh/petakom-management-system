@php
    $committee = $committee ?? null;
@endphp

<form action="{{ $committee
    ? route('manage-election.committees.update', $committee->id)
    : route('manage-election.committees.store') }}"
    method="POST">

    @csrf

    @if($committee)
        @method('PUT')
    @endif

    <div class="row g-3">

        <div class="col-md-6">

            <label class="form-label">Select Student *</label>

            <select name="student_id"
                    class="form-select @error('student_id') is-invalid @enderror"
                    required>

                <option value="">Choose Student</option>

                @foreach($students as $student)

                    <option value="{{ $student->id }}"
                        {{ old('student_id', $committee->student_id ?? '') == $student->id ? 'selected' : '' }}>

                        {{ $student->student_number }} -
                        {{ $student->full_name }}

                    </option>

                @endforeach

            </select>

            @error('student_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <div class="col-md-6">

            <label class="form-label">Committee Position *</label>

            <select name="position"
                    class="form-select @error('position') is-invalid @enderror"
                    required>

                <option value="">Select Position</option>

                @php
                    $positions = [
                        'President',
                        'Vice President',
                        'Secretary',
                        'Assistant Secretary',
                        'Treasurer',
                        'Assistant Treasurer',
                        'Exco',
                        'Committee Member'
                    ];
                @endphp

                @foreach($positions as $position)

                    <option value="{{ $position }}"
                        {{ old('position', $committee->position ?? '') == $position ? 'selected' : '' }}>

                        {{ $position }}

                    </option>

                @endforeach

            </select>

            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <div class="col-md-6">

            <label class="form-label">Academic Session *</label>

            <input type="text"
                   name="academic_session"
                   class="form-control @error('academic_session') is-invalid @enderror"
                   placeholder="e.g. 2025/2026"
                   value="{{ old('academic_session', $committee->academic_session ?? '') }}"
                   required>

            @error('academic_session')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <div class="col-md-6">

            <label class="form-label">Appointment Date</label>

            <input type="date"
                   name="appointed_at"
                   class="form-control"
                   value="{{ old('appointed_at', isset($committee->appointed_at) ? \Carbon\Carbon::parse($committee->appointed_at)->format('Y-m-d') : '') }}">

        </div>

        <div class="col-md-6">

            <label class="form-label">Membership Status *</label>

            <select name="status" class="form-select" required>

                <option value="active"
                    {{ old('status', $committee->status ?? 'active') == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $committee->status ?? '') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>

        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-end gap-2">

        <a href="{{ route('manage-election.committees.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">

            {{ $committee
                ? 'Update Committee Member'
                : 'Register Committee Member' }}

        </button>

    </div>

</form>
