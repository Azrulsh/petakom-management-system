@php
    $student = $student ?? null;
@endphp

<form action="{{ $student
    ? route('manage-registration.students.update', $student->id)
    : route('manage-registration.students.store') }}"
    method="POST">

    @csrf

    @if($student)
        @method('PUT')
    @endif

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Student ID *</label>

            <input type="text"
                   name="student_number"
                   class="form-control @error('student_number') is-invalid @enderror"
                   value="{{ old('student_number', $student->student_number ?? '') }}"
                   required>

            @error('student_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Full Name *</label>

            <input type="text"
                   name="full_name"
                   class="form-control @error('full_name') is-invalid @enderror"
                   value="{{ old('full_name', $student->full_name ?? '') }}"
                   required>

            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Email Address *</label>

            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $student->email ?? '') }}"
                   required>

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone Number</label>

            <input type="text"
                   name="phone"
                   class="form-control"
                   value="{{ old('phone', $student->phone ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Programme *</label>

            <input type="text"
                   name="programme"
                   class="form-control @error('programme') is-invalid @enderror"
                   value="{{ old('programme', $student->programme ?? '') }}"
                   placeholder="e.g. Bachelor of Computer Science"
                   required>

            @error('programme')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Year of Study</label>

            <select name="year_of_study" class="form-select">
                <option value="">Select Year</option>

                @foreach(range(1, 6) as $year)
                    <option value="{{ $year }}"
                        {{ old('year_of_study', $student->year_of_study ?? '') == $year ? 'selected' : '' }}>
                        Year {{ $year }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Registration Status *</label>

            <select name="status" class="form-select" required>
                <option value="pending"
                    {{ old('status', $student->status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="active"
                    {{ old('status', $student->status ?? '') == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $student->status ?? '') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-end gap-2">

        <a href="{{ route('manage-registration.students.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $student ? 'Update Student' : 'Register Student' }}
        </button>

    </div>

</form>
