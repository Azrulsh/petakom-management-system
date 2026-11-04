<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Proposal\Proposal;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['user', 'proposal'])
            ->latest()
            ->paginate(10);

        return view('manage-activity.index', compact('activities'));
    }

    public function create()
    {
       /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() ||
            $user->isCoordinator() ||
            $user->isCommittee(),
            403,
            'Unauthorized access.'
        );

        // Get approved proposals without an activity
        $proposals = Proposal::where('status', 'approved')
            ->whereDoesntHave('activity')
            ->latest()
            ->get();

        return view('manage-activity.create', compact('proposals'));
    }

    public function store(Request $request)
    {
        // Role-based authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() ||
            $user->isCoordinator() ||
            $user->isCommittee(),
            403,
            'Unauthorized access.'
        );

        // Validate activity information
        $validated = $request->validate([
            
            'proposal_id' => ['nullable', 'integer', 'exists:proposals,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_category' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'venue' => ['required', 'string', 'max:255'],
        ]);
 
        if (
            !empty($validated['start_time']) &&
            !empty($validated['end_time'])
        ) {
            $startDate = $validated['start_date'];

            $endDate = $validated['end_date'] ?? $startDate;

            // Validate time when activity is on the same day
            if (
                $startDate === $endDate &&
                $validated['end_time'] <= $validated['start_time']
            ) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'end_time' => 'End time must be later than start time.'
                ]);
            }
        }

        // Validate selected proposal
        if (!empty($validated['proposal_id'])) {

            $proposal = Proposal::findOrFail($validated['proposal_id']);

            if ($proposal->status !== 'approved') {
                throw ValidationException::withMessages([
                    'proposal_id' => 'Only approved proposals can be used.'
                ]);
            }

            if ($proposal->activity()->exists()) {
                throw ValidationException::withMessages([
                    'proposal_id' => 'This proposal already has an activity.'
                ]);
            }
        }

        // Save Activity
        Activity::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'upcoming',
        ]);

        return redirect()
            ->route('manage-activity.index')
            ->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        $activity->load(['user', 'proposal']);

        return view('manage-activity.show', compact('activity'));
    }
    
    public function edit(Activity $activity)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin and Coordinator can edit all activities.
        // Active Committee can only edit their own activities.
        $canEdit = $user->isAdmin()
            || $user->isCoordinator()
            || (
                $user->isCommittee()
                && $activity->user_id === $user->id
            );

        abort_unless(
            $canEdit,
            403,
            'You are not authorized to edit this activity.'
        );

        return view('manage-activity.edit', compact('activity'));
    }


    public function update(Request $request, Activity $activity)
    {
        
        // Role-based authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $canUpdate = $user->isAdmin()
            || $user->isCoordinator()
            || (
                $user->isCommittee()
                && $activity->user_id === $user->id
            );

        abort_unless(
            $canUpdate,
            403,
            'You are not authorized to update this activity.'
        );

        // Validate activity information
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_category' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'venue' => ['required', 'string', 'max:255'],
        ]);

        // Validate activity time
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'] ?? $startDate;

        if (
            !empty($validated['start_time']) &&
            !empty($validated['end_time']) &&
            $startDate === $endDate &&
            $validated['end_time'] <= $validated['start_time']
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' => 'End time must be later than start time.',
            ]);
        }

        // Update activity
        $activity->update($validated);

        return redirect()
            ->route('manage-activity.index')
            ->with('success', 'Activity updated successfully.');
    }
    
    public function destroy(Activity $activity)
    {

       /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(
            $user->isAdmin() || 
            $user->isCoordinator(),
            403,
            'Unauthorized access.'
        );

        $activity->delete();

        return redirect()
            ->route('manage-activity.index')
            ->with('success', 'Activity deleted successfully.');
    }
 
    public function updateStatus(Request $request, Activity $activity)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Role and ownership authorization
        $canUpdateStatus = $user->isAdmin()
            || $user->isCoordinator()
            || (
                $user->isCommittee()
                && $activity->user_id === $user->id
            );

        abort_unless(
            $canUpdateStatus,
            403,
            'You are not authorized to update this activity status.'
        );

        // Validate status
        $validated = $request->validate([
            'status' => ['required', 'in:upcoming,ongoing,completed'],
        ]);

        // Update activity status
        $activity->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('manage-activity.index')
            ->with('success', 'Activity status updated successfully.');
    }

}