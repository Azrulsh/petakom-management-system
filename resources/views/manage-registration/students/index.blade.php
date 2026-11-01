
@extends('layouts.registration')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Student Registration</h3>
            <p class="text-muted mb-0">
                Manage PETAKOM student registration records.
            </p>
        </div>

        <a href="{{ route('manage-registration.students.create') }}"
           class="btn btn-primary">
            + Add Student
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('manage-registration.students.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search name, student ID or email..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending"
                            {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive"
                            {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100">
                        Search / Filter
                    </button>
                </div>

            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Programme</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($students as $student)

                        <tr>
                            <td>
                                {{ $students->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $student->student_number }}</td>

                            <td class="fw-semibold">
                                {{ $student->full_name }}
                            </td>

                            <td>{{ $student->email }}</td>

                            <td>{{ $student->programme }}</td>

                            <td>{{ $student->year_of_study ?? '-' }}</td>

                            <td>
                                @if($student->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($student->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-1">

                                    <a href="{{ route('manage-registration.students.show', $student->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        View
                                    </a>

                                    <a href="{{ route('manage-registration.students.edit', $student->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('manage-registration.students.destroy', $student->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this student?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No student records found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $students->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
