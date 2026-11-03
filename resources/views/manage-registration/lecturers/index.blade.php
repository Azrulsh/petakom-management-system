@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Lecturer Registration</h3>
            <p class="text-muted mb-0">
                Manage faculty lecturer records.
            </p>
        </div>

        <a href="{{ route('manage-registration.lecturers.create') }}"
           class="btn btn-primary">
            + Add Lecturer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('manage-registration.lecturers.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search lecturer name, staff ID or email..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>

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
                            <th>Staff ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($lecturers as $lecturer)

                        <tr>

                            <td>
                                {{ $lecturers->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $lecturer->staff_number }}</td>

                            <td class="fw-semibold">
                                {{ $lecturer->full_name }}
                            </td>

                            <td>{{ $lecturer->email }}</td>

                            <td>{{ $lecturer->phone ?? '-' }}</td>

                            <td>{{ $lecturer->department }}</td>

                            <td>
                                @if($lecturer->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-1">

                                    <a href="{{ route('manage-registration.lecturers.show', $lecturer->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        View
                                    </a>

                                    <a href="{{ route('manage-registration.lecturers.edit', $lecturer->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('manage-registration.lecturers.destroy', $lecturer->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this lecturer?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="text-center text-muted py-4">
                                No lecturer records found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $lecturers->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
