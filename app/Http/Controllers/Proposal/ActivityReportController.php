<?php

namespace App\Http\Controllers\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal\ActivityReport;
use App\Models\Proposal\Proposal;
use Illuminate\Http\Request;

class ActivityReportController extends Controller
{
    /**
     * Display all activity reports.
     */
    public function index()
    {
        $activityReports = ActivityReport::with('proposal')
            ->latest('activity_date')
            ->paginate(10);

        return view(
            'manage-proposal.activity-reports.index',
            compact('activityReports')
        );
    }

    /**
     * Show create activity report form.
     */
    public function create()
    {
        $proposals = Proposal::orderBy('title')->get();

        return view(
            'manage-proposal.activity-reports.create',
            compact('proposals')
        );
    }

    /**
     * Store activity report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'activity_title' => ['required', 'string', 'max:255'],
            'activity_date' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'actual_attendance' => ['nullable', 'integer', 'min:0'],
            'actual_expenditure' => ['nullable', 'numeric', 'min:0'],
            'summary' => ['required', 'string'],
            'achievements' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'prepared_by' => ['required', 'string', 'max:255'],
        ]);

        $validated['actual_attendance'] =
            $validated['actual_attendance'] ?? 0;

        $validated['actual_expenditure'] =
            $validated['actual_expenditure'] ?? 0;

        $validated['status'] = 'draft';

        ActivityReport::create($validated);

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report created successfully.');
    }

    /**
     * Display activity report.
     */
    public function show(ActivityReport $activityReport)
    {
        $activityReport->load('proposal');

        return view(
            'manage-proposal.activity-reports.show',
            compact('activityReport')
        );
    }

    /**
     * Show edit activity report form.
     */
    public function edit(ActivityReport $activityReport)
    {
        $proposals = Proposal::orderBy('title')->get();

        return view(
            'manage-proposal.activity-reports.edit',
            compact('activityReport', 'proposals')
        );
    }

    /**
     * Update activity report.
     */
    public function update(Request $request, ActivityReport $activityReport)
    {
        $validated = $request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'activity_title' => ['required', 'string', 'max:255'],
            'activity_date' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'actual_attendance' => ['nullable', 'integer', 'min:0'],
            'actual_expenditure' => ['nullable', 'numeric', 'min:0'],
            'summary' => ['required', 'string'],
            'achievements' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'prepared_by' => ['required', 'string', 'max:255'],
        ]);

        $activityReport->update($validated);

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report updated successfully.');
    }

    /**
     * Delete activity report.
     */
    public function destroy(ActivityReport $activityReport)
    {
        $activityReport->delete();

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report deleted successfully.');
    }
}
