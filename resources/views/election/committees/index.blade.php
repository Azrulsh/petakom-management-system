@extends('layouts.header-sidebar')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Committee Registration</h3>
            <p class="text-muted mb-0">
                Manage PETAKOM committee members.
            </p>
        </div>

        <a href="{{ route('election.committees.create') }}"
           class="btn btn-primary">
            + Add Committee Member
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
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('election.committees.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search student name or ID..."
                           value="{{ request('search') }}">

                </div>

                <div class="col-md-3">

                    <input type="text"
                           name="session"
                           class="form-control"
                           placeholder="Academic session (e.g. 2025/2026)"
                           value="{{ request('session') }}">

                </div>

                <div class="col-md-2">

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
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Position</th>
                            <th>Academic Session</th>
                            <th>Appointment Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($members as $member)

                        <tr>

                            <td>
                                {{ $members->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $member->student->student_number ?? '-' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $member->student->full_name ?? 'Student not found' }}
                            </td>

                            <td>{{ $member->position }}</td>

                            <td>{{ $member->academic_session }}</td>

                            <td>
                                {{ $member->appointed_at
                                    ? \Carbon\Carbon::parse($member->appointed_at)->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>

                                @if($member->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route('election.committees.show', $member->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        View
                                    </a>

                                    <a href="{{ route('election.committees.edit', $member->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('election.committees.destroy', $member->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this committee record?')">

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
                                No committee records found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $members->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
