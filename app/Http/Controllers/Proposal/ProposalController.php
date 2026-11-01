<?php

namespace App\Http\Controllers\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    /**
     * Display all proposals.
     */
    public function index(Request $request)
    {
        $query = Proposal::query();

        // Search by proposal title or venue
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('prepared_by', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('activity_category', $request->category);
        }

        $proposals = $query
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('manage-proposal.index', compact('proposals'));
    }


    /**
     * Show create proposal form.
     */
    public function create()
    {
        return view('manage-proposal.create');
    }

    /**
     * Store new proposal.
     */
    public function store(Request $request)
    {
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

    /**
     * Display proposal details.
     */
    public function show(Proposal $proposal)
    {
        $proposal->load('activityReports');

        return view('manage-proposal.show', compact('proposal'));
    }

    /**
     * Show edit proposal form.
     */
    public function edit(Proposal $proposal)
    {
        return view('manage-proposal.edit', compact('proposal'));
    }

    /**
     * Update proposal.
     */
    public function update(Request $request, Proposal $proposal)
    {
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

        $proposal->update($validated);

        return redirect()
            ->route('manage-proposal.proposals.index')
            ->with('success', 'Proposal updated successfully.');
    }

    /**
     * Submit proposal for approval.
     */
    public function submit(Proposal $proposal)
    {
        if (!in_array($proposal->status, ['draft', 'rejected'], true)) {
            return redirect()
                ->back()
                ->with('error', 'This proposal cannot be submitted.');
        }

        $proposal->update([
            'status' => 'pending',
            'review_remarks' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('manage-proposal.proposals.index')
            ->with('success', 'Proposal submitted successfully for approval.');
    }


    /**
     * Delete proposal.
     */
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();

        return redirect()
            ->route('manage-proposal.proposals.index')
            ->with('success', 'Proposal deleted successfully.');
    }
}
