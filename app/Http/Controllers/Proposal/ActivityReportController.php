<?php

namespace App\Http\Controllers\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal\ActivityReport;
use App\Models\Proposal\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ROLE CHECKING
    |--------------------------------------------------------------------------
    */

    private function isCommitteeUser($user): bool
    {
        return $user && (
            $user->isCommittee() ||
            $user->isActiveCommitteeMember()
        );
    }

    private function isReviewer($user): bool
    {
        return $user && (
            $user->isAdmin() ||
            $user->isLecturer() ||
            $user->isCoordinator()
        );
    }

    private function ensureCanView($user): void
    {
        abort_unless(
            $this->isReviewer($user) ||
            $this->isCommitteeUser($user),
            403,
            'You are not authorized to view activity reports.'
        );
    }

    private function ensureCanManage($user): void
    {
        abort_unless(
            $user && (
                $user->isAdmin() ||
                $this->isCommitteeUser($user)
            ),
            403,
            'You are not authorized to manage activity reports.'
        );
    }

    private function ensureCanReview($user): void
    {
        abort_unless(
            $this->isReviewer($user),
            403,
            'You are not authorized to review activity reports.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT OWNERSHIP
    |--------------------------------------------------------------------------
    */

    private function ensureReportOwner($user, ActivityReport $activityReport): void
    {
        $this->ensureCanManage($user);

        abort_unless(
            $user->isAdmin() ||
            (
                $this->isCommitteeUser($user) &&
                (int) $activityReport->created_by === (int) $user->id
            ),
            403,
            'You are not authorized to manage this report.'
        );
    }

    private function ensureEditable($user, ActivityReport $activityReport): void
    {
        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            in_array($activityReport->status, ['draft', 'rejected'], true),
            403,
            'Only draft or rejected reports can be edited.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = $request->user();

        $this->ensureCanView($user);

        $query = ActivityReport::with([
            'proposal',
            'creator',
            'reviewer'
        ]);

        // Committee can only view own reports.
        // Admin, Lecturer and Coordinator can view all.

        if (!$this->isReviewer($user)) {
            $query->where('created_by', $user->id);
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('activity_title', 'like', "%{$search}%")
                  ->orWhere('prepared_by', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");

            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activityReports = $query
            ->latest('activity_date')
            ->paginate(10)
            ->appends($request->query());

        return view(
            'manage-proposal.activity-reports.index',
            compact('activityReports')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $this->ensureCanManage($request->user());

        // Only approved proposals can be linked.
        $proposals = Proposal::where('status', 'approved')
            ->orderBy('title')
            ->get();

        return view(
            'manage-proposal.activity-reports.create',
            compact('proposals')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = $request->user();

        $this->ensureCanManage($user);

        $validated = $request->validate([
            'proposal_id' => [
                'nullable',
                'exists:proposals,id'
            ],

            'activity_title' => [
                'required',
                'string',
                'max:255'
            ],

            'activity_date' => [
                'required',
                'date'
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255'
            ],

            'actual_attendance' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'actual_expenditure' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'summary' => [
                'required',
                'string'
            ],

            'achievements' => [
                'nullable',
                'string'
            ],

            'challenges' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'prepared_by' => [
                'required',
                'string',
                'max:255'
            ],
        ]);

        // Verify proposal approval status.
        if (!empty($validated['proposal_id'])) {

            $proposal = Proposal::findOrFail(
                $validated['proposal_id']
            );

            if ($proposal->status !== 'approved') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'proposal_id' =>
                        'Activity reports can only be linked to approved proposals.'
                    ]);
            }
        }

        $validated['created_by'] = $user->id;

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

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, ActivityReport $activityReport)
    {
        $user = $request->user();

        $this->ensureCanView($user);

        // Non-reviewers can only view their own reports.
        if (!$this->isReviewer($user)) {

            abort_unless(
                (int) $activityReport->created_by === (int) $user->id,
                403,
                'You are not authorized to view this report.'
            );
        }

        $activityReport->load([
            'proposal',
            'creator',
            'reviewer'
        ]);

        return view(
            'manage-proposal.activity-reports.show',
            compact('activityReport')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, ActivityReport $activityReport)
    {
        $user = $request->user();

        $this->ensureReportOwner($user, $activityReport);

        $this->ensureEditable($user, $activityReport);

        $proposals = Proposal::where('status', 'approved')
            ->orderBy('title')
            ->get();

        return view(
            'manage-proposal.activity-reports.edit',
            compact('activityReport', 'proposals')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ActivityReport $activityReport
    ) {
        $user = $request->user();

        $this->ensureReportOwner($user, $activityReport);

        $this->ensureEditable($user, $activityReport);

        $validated = $request->validate([
            'proposal_id' => [
                'nullable',
                'exists:proposals,id'
            ],

            'activity_title' => [
                'required',
                'string',
                'max:255'
            ],

            'activity_date' => [
                'required',
                'date'
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255'
            ],

            'actual_attendance' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'actual_expenditure' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'summary' => [
                'required',
                'string'
            ],

            'achievements' => [
                'nullable',
                'string'
            ],

            'challenges' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'prepared_by' => [
                'required',
                'string',
                'max:255'
            ],
        ]);

        if (!empty($validated['proposal_id'])) {

            $proposal = Proposal::findOrFail(
                $validated['proposal_id']
            );

            if ($proposal->status !== 'approved') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'proposal_id' =>
                        'Only approved proposals can be linked.'
                    ]);
            }
        }

        // Protect ownership and approval fields.
        unset(
            $validated['created_by'],
            $validated['reviewed_by'],
            $validated['review_remarks'],
            $validated['reviewed_at'],
            $validated['submitted_at'],
            $validated['status']
        );

        $activityReport->update($validated);

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        ActivityReport $activityReport
    ) {
        $user = $request->user();

        $this->ensureReportOwner($user, $activityReport);

        $this->ensureEditable($user, $activityReport);

        $activityReport->delete();

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT / RESUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Request $request,
        ActivityReport $activityReport
    ) {
        $user = $request->user();

        $this->ensureReportOwner($user, $activityReport);

        if (!in_array(
            $activityReport->status,
            ['draft', 'rejected'],
            true
        )) {
            return back()->with(
                'error',
                'This report cannot be submitted.'
            );
        }

        $activityReport->update([
            'status' => 'pending',
            'submitted_at' => now(),
            'review_remarks' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report submitted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        ActivityReport $activityReport
    ) {
        $user = $request->user();

        $this->ensureCanReview($user);

        $validated = $request->validate([
            'review_remarks' => [
                'nullable',
                'string'
            ],
        ]);

        $approved = DB::transaction(function () use (
            $activityReport,
            $user,
            $validated
        ) {

            $report = ActivityReport::whereKey($activityReport->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($report->status !== 'pending') {
                return false;
            }

            $report->update([
                'status' => 'approved',
                'review_remarks' =>
                    $validated['review_remarks'] ?? null,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            return true;
        });

        if (!$approved) {
            return back()->with(
                'error',
                'Only pending reports can be approved.'
            );
        }

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report approved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        ActivityReport $activityReport
    ) {
        $user = $request->user();

        $this->ensureCanReview($user);

        $validated = $request->validate([
            'review_remarks' => [
                'required',
                'string',
                'max:5000'
            ],
        ]);

        $rejected = DB::transaction(function () use (
            $activityReport,
            $user,
            $validated
        ) {

            $report = ActivityReport::whereKey($activityReport->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($report->status !== 'pending') {
                return false;
            }

            $report->update([
                'status' => 'rejected',
                'review_remarks' => $validated['review_remarks'],
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            return true;
        });

        if (!$rejected) {
            return back()->with(
                'error',
                'Only pending reports can be rejected.'
            );
        }

        return redirect()
            ->route('manage-proposal.activity-reports.index')
            ->with('success', 'Activity report rejected successfully.');
    }

    
/*
|--------------------------------------------------------------------------
| ACTIVITY REPORT APPROVAL HISTORY
|--------------------------------------------------------------------------
*/

public function history(Request $request)
{
    $user = $request->user();

    // Only Admin, Lecturer and Coordinator
    // can access approval history.
    $this->ensureCanReview($user);

    $query = ActivityReport::with([
        'proposal',
        'creator',
        'reviewer'
    ])
    ->whereIn('status', ['approved', 'rejected'])
    ->whereNotNull('reviewed_at');

    // Search
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('activity_title', 'like', "%{$search}%")
              ->orWhere('prepared_by', 'like', "%{$search}%")
              ->orWhereHas('reviewer', function ($reviewer) use ($search) {
                  $reviewer->where('name', 'like', "%{$search}%");
              });

        });
    }

    // Filter by decision
    if ($request->filled('status')) {

        $query->where('status', $request->status);

    }

    $activityReports = $query
        ->latest('reviewed_at')
        ->paginate(10)
        ->appends($request->query());

    return view(
        'manage-proposal.activity-reports.history',
        compact('activityReports')
    );
}

}
