@php
    $lecturer = $lecturer ?? null;
@endphp

<form action="{{ $lecturer
    ? route('registration.lecturers.update', $lecturer->id)
    : route('registration.lecturers.store') }}"
    method="POST">

    @csrf

    @if($lecturer)
        @method('PUT')
    @endif

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Staff ID *</label>

            <input type="text"
                   name="staff_number"
                   class="form-control @error('staff_number') is-invalid @enderror"
                   value="{{ old('staff_number', $lecturer->staff_number ?? '') }}"
                   required>

            @error('staff_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Full Name *</label>

            <input type="text"
                   name="full_name"
                   class="form-control @error('full_name') is-invalid @enderror"
                   value="{{ old('full_name', $lecturer->full_name ?? '') }}"
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
                   value="{{ old('email', $lecturer->email ?? '') }}"
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
                   value="{{ old('phone', $lecturer->phone ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Department *</label>

            <input type="text"
                   name="department"
                   class="form-control @error('department') is-invalid @enderror"
                   value="{{ old('department', $lecturer->department ?? '') }}"
                   placeholder="Enter department"
                   required>

            @error('department')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Status *</label>

            <select name="status" class="form-select" required>

                <option value="active"
                    {{ old('status', $lecturer->status ?? 'active') == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $lecturer->status ?? '') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>
        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-end gap-2">

        <a href="{{ route('registration.lecturers.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $lecturer ? 'Update Lecturer' : 'Register Lecturer' }}
        </button>

    </div>

</form>
