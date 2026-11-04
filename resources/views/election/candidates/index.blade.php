@extends('layouts.header-sidebar')

@section('title', 'Election Candidates')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        {{-- Page Title --}}
        <div>
            <h3 class="fw-bold mb-1">Election Candidates</h3>
            <p class="text-muted mb-0">
                {{ $election->title }}
            </p>
        </div>

        @php
            $user = auth()->user();

            $canRegister = $user->isStudent()
                && $user->student !== null
                && $election->status === 'open'
                && now()->between(
                    $election->nomination_start,
                    $election->nomination_end
                )
                && !$election->candidates()
                    ->where('student_id', $user->student->id)
                    ->exists();
        @endphp

        {{-- Action Buttons --}}
        <div class="d-flex align-items-center gap-2">

            @if($canRegister)
                <a href="{{ route('election.candidates.create', $election->id) }}"
                class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>
                    Register as Candidate
                </a>
            @endif

            <a href="{{ route('election.show', $election->id) }}"
            class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Candidate List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Candidate List</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Position</th>
                            <th>Manifesto</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator() || auth()->user()->isStudent())
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($candidates as $candidate)
                            <tr>
                                <td>
                                    {{ $candidates->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $candidate->student?->full_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $candidate->position }}
                                </td>

                                <td>
                                    {{ Str::limit($candidate->manifesto ?? '-', 80) }}
                                </td>

                                <td>
                                    @switch($candidate->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                            @break

                                        @case('approved')
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                            @break

                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                            @break

                                        @case('withdrawn')
                                            <span class="badge bg-secondary">
                                                Withdrawn
                                            </span>
                                            @break
                                    @endswitch
                                </td>

                                <td>
                                    {{ $candidate->created_at->format('d M Y') }}
                                </td>
 
                                @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator() || auth()->user()->isStudent())
                                    <td>
                                        {{-- Admin / Coordinator Actions --}}
                                        @if(auth()->user()->isAdmin() || auth()->user()->isCoordinator())

                                            @if($candidate->status === 'pending'&& $election->status === 'open')
                                                <div class="d-flex gap-2">

                                                    {{-- Approve --}}
                                                    <form method="POST"
                                                        action="{{ route('election.candidates.review', [
                                                            $election->id,
                                                            $candidate->id
                                                        ]) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="hidden"
                                                            name="status"
                                                            value="approved">

                                                        <button type="submit"
                                                                class="btn btn-success btn-sm">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    {{-- Reject --}}
                                                    <form method="POST"
                                                        action="{{ route('election.candidates.review', [
                                                            $election->id,
                                                            $candidate->id
                                                        ]) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="hidden"
                                                            name="status"
                                                            value="rejected">

                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Reject this candidate?')">
                                                            Reject
                                                        </button>
                                                    </form>

                                                </div>
                                            
                                            @elseif($candidate->status === 'approved')

                                                @php
                                                    $alreadyAppointed = \App\Models\Election\Committee::where(
                                                        'student_id',
                                                        $candidate->student_id
                                                    )
                                                    ->where('academic_session', $election->academic_session)
                                                    ->exists();
                                                @endphp

                                                @if($alreadyAppointed)
                                                    <span class="badge bg-success">
                                                        Appointed
                                                    </span>

                                                @elseif(auth()->user()->isAdmin() && $election->status === 'closed')
                                                    <form method="POST"
                                                        action="{{ route('election.candidates.appoint', [
                                                            $election->id,
                                                            $candidate->id
                                                        ]) }}">
                                                        @csrf

                                                        <button type="submit"
                                                                class="btn btn-primary btn-sm"
                                                                onclick="return confirm('Appoint this candidate as PETAKOM Committee?')">
                                                            Appoint as Committee
                                                        </button>
                                                    </form>

                                                @else
                                                    <span class="text-muted">Approved</span>
                                                @endif

                                                @else
                                                    <span class="text-muted">
                                                        {{ ucfirst($candidate->status) }}
                                                    </span>
                                                @endif

                                        {{-- Student Actions --}}
                                        @elseif(
                                            auth()->user()->isStudent()
                                            && auth()->user()->student?->id === $candidate->student_id
                                            && in_array($candidate->status, ['pending', 'approved'], true)
                                            && $election->status === 'open'
                                            && now()->between(
                                                $election->nomination_start,
                                                $election->nomination_end
                                            )
                                        )
                                            <form method="POST"
                                                action="{{ route('election.candidates.withdraw', [
                                                    $election->id,
                                                    $candidate->id
                                                ]) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Withdraw your candidacy?')">
                                                    Withdraw
                                                </button>
                                            </form>

                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endif

                            </tr>

                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isCoordinator() || auth()->user()->isStudent() ? 7 : 6 }}"
                                    class="text-center text-muted py-4">
                                    No candidates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $candidates->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
