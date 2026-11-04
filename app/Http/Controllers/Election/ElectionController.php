<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin and Coordinator can view all elections.
        // Other users can only view published elections.
        $query = Election::with('creator');

        if (!$user->isAdmin() && !$user->isCoordinator()) {
            $query->where('status', '!=', 'draft');
        }

        $elections = $query
            ->latest()
            ->paginate(10);

        return view('election.elections.index', compact('elections'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        return view('election.elections.create');
    }
  
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin and Coordinator
        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'academic_session' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],

            'nomination_start' => ['required', 'date'],
            'nomination_end' => [
                'required',
                'date',
                'after:nomination_start',
            ],
        ], [
            'nomination_end.after' =>
                'Nomination end must be later than nomination start.',
        ]);

        Election::create([
            ...$validated,
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        return redirect()
            ->route('election.index')
            ->with('success', 'Election created successfully.');
    }

    public function show(Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Draft elections are only visible to Admin and Coordinator
        if (
            $election->status === 'draft' &&
            !$user->isAdmin() &&
            !$user->isCoordinator()
        ) {
            abort(403, 'Unauthorized access.');
        }

        $election->load('creator');

        return view('election.elections.show', compact('election'));
    }

    public function edit(Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        // Only draft elections can be edited
        abort_unless(
            $election->status === 'draft',
            403,
            'Only draft elections can be edited.'
        );

        return view('election.elections.edit', compact('election'));
    }

    
    public function update(Request $request, Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin and Coordinator
        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        // Only draft elections can be updated
        abort_unless(
            $election->status === 'draft',
            403,
            'Only draft elections can be updated.'
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'academic_session' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],

            'nomination_start' => ['required', 'date'],
            'nomination_end' => [
                'required',
                'date',
                'after:nomination_start',
            ],
        ], [
            'nomination_end.after' =>
                'Nomination end must be later than nomination start.',
        ]);

        $election->update($validated);

        return redirect()
            ->route('election.index')
            ->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin can delete elections
        abort_unless(
            $user->isAdmin(),
            403,
            'Unauthorized access.'
        );

        // Only draft elections can be deleted
        abort_unless(
            $election->status === 'draft',
            403,
            'Only draft elections can be deleted.'
        );

        $election->delete();

        return redirect()
            ->route('election.index')
            ->with('success', 'Election deleted successfully.');
    }

    public function updateStatus(Request $request, Election $election)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() || $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        $validated = $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $allowedTransitions = [
            'draft' => ['open'],
            'open' => ['closed'],
        ];

        $allowed = $allowedTransitions[$election->status] ?? [];

        if (!in_array($validated['status'], $allowed, true)) {
            return back()->withErrors([
                'status' => 'Invalid election status transition.',
            ]);
        }

        $election->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('election.index')
            ->with('success', 'Election status updated successfully.');
    }


}
