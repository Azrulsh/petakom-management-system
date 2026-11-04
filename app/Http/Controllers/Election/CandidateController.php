<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election\Committee;
use App\Models\Election\Candidate;
use App\Models\Election\Election;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    public function index(Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Draft elections are only visible to Admin/Coordinator
        if (
            $election->status === 'draft' &&
            !$user->isAdmin() &&
            !$user->isCoordinator()
        ) {
            abort(403, 'Unauthorized access.');
        }

        // Admin and Coordinator can view all candidates.
        // Other users can view approved candidates only.
        $query = Candidate::with(['student', 'reviewer'])
            ->where('election_id', $election->id);
 
            if (!$user->isAdmin() && !$user->isCoordinator()) {
                $query->where(function ($q) use ($user) {
                    // Show approved candidates to everyone
                    $q->where('status', 'approved');

                    // Students can also see their own application
                    if ($user->isStudent() && $user->student) {
                        $q->orWhere('student_id', $user->student->id);
                    }
                });
            }

        $candidates = $query
            ->latest()
            ->paginate(10);

        return view('election.candidates.index', compact(
            'election',
            'candidates'
        ));
    }

    public function create(Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only students can register as candidates
        abort_unless(
            $user->isStudent(),
            403,
            'Only students can register as candidates.'
        );

        // Ensure student has a registered student profile
        $student = $user->student;

        abort_unless(
            $student !== null,
            403,
            'Student profile not found.'
        );

        // Election must be open
        abort_unless(
            $election->status === 'open',
            403,
            'Election is not open.'
        );

        // Check nomination period
        $now = now();

        abort_unless(
            $now->between(
                $election->nomination_start,
                $election->nomination_end
            ),
            403,
            'Candidate registration is currently closed.'
        );

        // Prevent duplicate candidacy
        abort_if(
            Candidate::where('election_id', $election->id)
                ->where('student_id', $student->id)
                ->exists(),
            403,
            'You have already registered for this election.'
        );

        return view('election.candidates.create', compact(
            'election',
            'student'
        ));
    }
 
    public function store(Request $request, Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->isStudent(), 403);

        $student = $user->student;

        abort_unless($student !== null, 403, 'Student profile not found.');

        abort_unless(
            $election->status === 'open'
            && now()->between(
                $election->nomination_start,
                $election->nomination_end
            ),
            403,
            'Candidate registration is closed.'
        );

        $validated = $request->validate([
            'position' => [
                'required',
                'in:President,Vice President,Secretary,Treasurer,Committee Member',
            ],
            'manifesto' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($election, $student, $validated) {
            // Lock election row to coordinate concurrent registration.
            $lockedElection = Election::whereKey($election->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $lockedElection->status === 'open'
                && now()->between(
                    $lockedElection->nomination_start,
                    $lockedElection->nomination_end
                ),
                403,
                'Candidate registration is closed.'
            );

            if (Candidate::where('election_id', $lockedElection->id)
                ->where('student_id', $student->id)
                ->exists()) {
                throw ValidationException::withMessages([
                    'position' => 'You have already registered for this election.',
                ]);
            }

            Candidate::create([
                'election_id' => $lockedElection->id,
                'student_id' => $student->id,
                'position' => $validated['position'],
                'manifesto' => $validated['manifesto'] ?? null,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('election.candidates.index', $election->id)
            ->with('success', 'Candidate registration submitted successfully.');
    }
  
    public function review(Request $request, Election $election, Candidate $candidate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403
        );

        abort_unless(
            $candidate->election_id === $election->id,
            404
        );

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        abort_unless(
            $candidate->status === 'pending',
            403,
            'Only pending candidates can be reviewed.'
        );

        // Candidate review is only allowed while election is open
        abort_unless(
            $election->status === 'open',
            403,
            'Candidate review is only allowed while the election is open.'
        );

        $candidate->update([
            'status' => $validated['status'],
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('election.candidates.index', $election->id)
            ->with('success', 'Candidate application reviewed successfully.');
    }

    public function withdraw(Election $election, Candidate $candidate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only students can withdraw
        abort_unless($user->isStudent(), 403);

        $student = $user->student;

        abort_unless($student !== null, 403);

        // Candidate must belong to the selected election
        abort_unless(
            $candidate->election_id === $election->id,
            404
        );

        // Student can only withdraw their own candidacy
        abort_unless(
            $candidate->student_id === $student->id,
            403
        );

        // Election must still be open for nomination
        abort_unless(
            $election->status === 'open'
            && now()->between(
                $election->nomination_start,
                $election->nomination_end
            ),
            403,
            'Nomination period is closed.'
        );

        // Only pending or approved candidates can withdraw
        abort_unless(
            in_array($candidate->status, ['pending', 'approved'], true),
            403,
            'This application cannot be withdrawn.'
        );

        $candidate->update([
            'status' => 'withdrawn',
        ]);

        return redirect()
            ->route('election.candidates.index', $election->id)
            ->with('success', 'Your candidacy has been withdrawn successfully.');
    }

    public function appoint(Election $election, Candidate $candidate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin can appoint committee members
        abort_unless($user->isAdmin(), 403);

        // Candidate must belong to this election
        abort_unless(
            $candidate->election_id === $election->id,
            404
        );

        // Only approved candidates can be appointed
        abort_unless(
            $candidate->status === 'approved',
            403,
            'Only approved candidates can be appointed.'
        );
    
        // Committee appointment is allowed only after election closes
        abort_unless(
            $election->status === 'closed',
            403,
            'Committee appointment is only allowed after the election is closed.'
        );

        // Avoid duplicate appointment for the same session
        $alreadyAppointed = Committee::where('student_id', $candidate->student_id)
            ->where('academic_session', $election->academic_session)
            ->exists();

        abort_if(
            $alreadyAppointed,
            403,
            'This student is already registered as a committee member for this session.'
        );

        Committee::create([
            'student_id' => $candidate->student_id,
            'position' => $candidate->position,
            'academic_session' => $election->academic_session,
            'appointed_at' => today(),
            'status' => 'active',
        ]);

        return redirect()
            ->route('election.candidates.index', $election->id)
            ->with('success', 'Candidate appointed as committee successfully.');
    }

}
