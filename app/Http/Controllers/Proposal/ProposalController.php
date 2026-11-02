<?php

namespace App\Http\Controllers\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal\Proposal;
use App\Models\Proposal\ProposalReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    
public function index(Request $request)
{
    $user = $request->user();

    $this->ensureCanManageProposals($user);

    $query = Proposal::query();

    // Admin can view all proposals.
    // Other users can only view their own proposals.
    if (!$user->isAdmin()) {
        $query->where('user_id', $user->id);
    }

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('title', 'like', "%{$search}%")
              ->orWhere('venue', 'like', "%{$search}%")
              ->orWhere('prepared_by', 'like', "%{$search}%");

        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('category')) {
        $query->where('activity_category', $request->category);
    }

    $proposals = $query
        ->latest()
        ->paginate(10)
        ->appends($request->query());

    return view('manage-proposal.index', compact('proposals'));
}


    
public function create(Request $request)
{
    $this->ensureCanManageProposals($request->user());

    return view('manage-proposal.create');
}


public function store(Request $request)
{
    $user = $request->user();

    $this->ensureCanManageProposals($user);

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'activity_category' => ['nullable', 'string', 'max:100'],
        'background' => ['nullable', 'string'],
        'objectives' => ['required', 'string'],
        'proposed_date' => ['required', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:proposed_date'],
        'venue' => ['required', 'string', 'max:255'],
        'target_participants' => ['nullable', 'integer', 'min:0'],
        'estimated_budget' => ['nullable', 'numeric', 'min:0'],
        'prepared_by' => ['required', 'string', 'max:255'],
    ]);

    $validated['user_id'] = $user->id;

    $validated['target_participants'] =
        $validated['target_participants'] ?? 0;

    $validated['estimated_budget'] =
        $validated['estimated_budget'] ?? 0;

    $validated['status'] = 'draft';

    Proposal::create($validated);

    return redirect()
        ->route('manage-proposal.proposals.index')
        ->with('success', 'Proposal created successfully.');
}


   
public function show(Request $request, Proposal $proposal)
{
    $this->ensureProposalOwner($request->user(), $proposal);

    $proposal->load('activityReports');

    return view('manage-proposal.show', compact('proposal'));
}


public function edit(Request $request, Proposal $proposal)
{
    $this->ensureProposalOwner($request->user(), $proposal);

    return view('manage-proposal.edit', compact('proposal'));
}


    
public function update(Request $request, Proposal $proposal)
{
    $this->ensureProposalOwner($request->user(), $proposal);

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'activity_category' => ['nullable', 'string', 'max:100'],
        'background' => ['nullable', 'string'],
        'objectives' => ['required', 'string'],
        'proposed_date' => ['required', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:proposed_date'],
        'venue' => ['required', 'string', 'max:255'],
        'target_participants' => ['nullable', 'integer', 'min:0'],
        'estimated_budget' => ['nullable', 'numeric', 'min:0'],
        'prepared_by' => ['required', 'string', 'max:255'],
    ]);

    // Do not allow changing proposal ownership.
    unset($validated['user_id']);

    $proposal->update($validated);

    return redirect()
        ->route('manage-proposal.proposals.index')
        ->with('success', 'Proposal updated successfully.');
}

   

public function submit(Request $request, Proposal $proposal)
{
    $this->ensureProposalOwner($request->user(), $proposal);

    $result = DB::transaction(function () use ($proposal) {

        $lockedProposal = Proposal::whereKey($proposal->id)
            ->lockForUpdate()
            ->firstOrFail();

        if (!in_array(
            $lockedProposal->status,
            ['draft', 'rejected'],
            true
        )) {
            return false;
        }

        $lockedProposal->update([
            'status' => 'pending',
            'review_remarks' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        // Previous review history remains unchanged.

        return true;
    });

    if (!$result) {
        return back()->with(
            'error',
            'This proposal cannot be submitted.'
        );
    }

    return redirect()
        ->route('manage-proposal.proposals.index')
        ->with(
            'success',
            'Proposal submitted successfully.'
        );
}



public function destroy(Request $request, Proposal $proposal)
{
    $this->ensureProposalOwner($request->user(), $proposal);

    // Preserve proposals that have related records.
    if (
        $proposal->reviews()->exists() ||
        $proposal->activityReports()->exists()
    ) {
        return back()->with(
            'error',
            'This proposal cannot be deleted because it has related records.'
        );
    }

    $proposal->delete();

    return redirect()
        ->route('manage-proposal.proposals.index')
        ->with('success', 'Proposal deleted successfully.');
}


    /**
     * Display pending proposals for reviewers.
     */
    public function reviewIndex(Request $request)
    {
        $this->ensureCanReviewProposals($request->user());
        $proposals = Proposal::query()
            ->where('status', 'pending')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('prepared_by', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view(
            'manage-proposal.approvals.index',
            compact('proposals')
        );
    }

    public function reviewShow(Request $request, Proposal $proposal)
{
    $this->ensureCanReviewProposals($request->user());

    if ($proposal->status !== 'pending') {
        return redirect()
            ->route('proposal-approvals.index')
            ->with('error', 'This proposal is no longer pending review.');
    }

    $proposal->load('activityReports', 'reviewer');

    return view(
        'manage-proposal.approvals.show',
        compact('proposal')
    );
}

    /**
    * Approve Proposal and Save Review History.
    */
    public function approve(Request $request, Proposal $proposal)
    {
        $user = $request->user();

       $this->ensureCanApproveProposals($user);

        // Validate remarks
        $validated = $request->validate([
            'review_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        $result = DB::transaction(function () use (
            $proposal,
            $user,
            $validated
        ) {

            // Lock proposal during review
            $lockedProposal = Proposal::whereKey($proposal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only pending proposals can be approved
            if ($lockedProposal->status !== 'pending') {
                return false;
            }

            $remarks = $validated['review_remarks'] ?? null;

            // Save approval history
            ProposalReview::create([
                'proposal_id' => $lockedProposal->id,
                'reviewer_id' => $user->id,
                'decision' => 'approved',
                'remarks' => $remarks,
                'reviewed_at' => now(),
            ]);

            // Update current proposal status
            $lockedProposal->update([
                'status' => 'approved',
                'review_remarks' => $remarks,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            return true;
        });

        if (!$result) {
            return redirect()
                ->back()
                ->with('error', 'This proposal is no longer pending review.');
        }

        return redirect()
            ->route('proposal-approvals.index')
            ->with('success', 'Proposal approved successfully.');
    }

    /**
    * Reject Proposal and Save Review History.
    */
    public function reject(Request $request, Proposal $proposal)
    {
        $user = $request->user();

        $this->ensureCanReviewProposals($user);

        // Rejection remarks are compulsory
        $validated = $request->validate([
            'review_remarks' => ['required', 'string', 'max:5000'],
        ]);

        $result = DB::transaction(function () use (
            $proposal,
            $user,
            $validated
        ) {

            // Lock proposal during review
            $lockedProposal = Proposal::whereKey($proposal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only pending proposals can be rejected
            if ($lockedProposal->status !== 'pending') {
                return false;
            }

            // Save rejection history
            ProposalReview::create([
                'proposal_id' => $lockedProposal->id,
                'reviewer_id' => $user->id,
                'decision' => 'rejected',
                'remarks' => $validated['review_remarks'],
                'reviewed_at' => now(),
            ]);

            // Update current proposal status
            $lockedProposal->update([
                'status' => 'rejected',
                'review_remarks' => $validated['review_remarks'],
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            return true;
        });

        if (!$result) {
            return redirect()
                ->back()
                ->with('error', 'This proposal is no longer pending review.');
        }

        return redirect()
            ->route('proposal-approvals.index')
            ->with('success', 'Proposal rejected successfully.');
    }

    /**
     * Display all proposals with approval history.
     */
    public function historyIndex(Request $request)
    {
        $proposals = Proposal::query()
            ->whereHas('reviews')
            ->withCount('reviews')
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('prepared_by', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
                });

            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('updated_at')
            ->paginate(10)
            ->appends($request->query());

        return view(
            'manage-proposal.approvals.history-index',
            compact('proposals')
        );
    }


    public function historyShow(Request $request, Proposal $proposal)
{
    $this->ensureCanReviewProposals($request->user());

    $proposal->load('reviews.reviewer');

    return view(
        'manage-proposal.approvals.history-show',
        compact('proposal')
    );
}

    /**
     * Check whether user can manage proposals.
     */
    private function ensureCanManageProposals($user): void
    {
        abort_unless(
            $user && ($user->isAdmin() || $user->isStudent()),
            403,
            'You are not authorized to manage proposals.'
        );
    }

    /**
     * Check proposal ownership.
     */
    private function ensureProposalOwner($user, Proposal $proposal): void
    {
        $this->ensureCanManageProposals($user);

        abort_unless(
            $user->isAdmin() ||
            (int) $proposal->user_id === (int) $user->id,
            403,
            'You are not authorized to access this proposal.'
        );
    }

    
/**
 * Check reviewer access.
 */
private function ensureCanReviewProposals($user): void
{
    abort_unless(
        $user && (
            $user->isAdmin() ||
            $user->isLecturer() ||
            $user->isCoordinator()
        ),
        403,
        'You are not authorized to review proposals.'
    );
}


/**
 * Check approval access.
 * Only Admin and Coordinator can approve.
 */
private function ensureCanApproveProposals($user): void
{
    abort_unless(
        $user && (
            $user->isAdmin() ||
            $user->isLecturer() ||
            $user->isCoordinator()
        ),
        403,
        'You are not authorized to approve proposals.'
    );
}


}
