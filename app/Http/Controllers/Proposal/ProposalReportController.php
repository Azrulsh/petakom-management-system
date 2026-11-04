<?php

namespace App\Http\Controllers\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal\Proposal;
use App\Models\Proposal\ActivityReport;

class ProposalReportController extends Controller
{
    /**
     * Display proposal and activity reports.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user, 403);

        $canViewAll = $user->isAdmin()
            || $user->isLecturer()
            || $user->isCoordinator();

        // Proposal query
        $proposalQuery = Proposal::query();

        // Activity Report query
        $activityReportQuery = ActivityReport::query();

        // Students can only view reports related to their own proposals
        if (!$canViewAll) {
            $proposalQuery->where('user_id', $user->id);

            $activityReportQuery->whereHas('proposal', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Proposal Statistics
        $totalProposals = (clone $proposalQuery)->count();

        $draftProposals = (clone $proposalQuery)
            ->where('status', 'draft')->count();

        $pendingProposals = (clone $proposalQuery)
            ->where('status', 'pending')->count();

        $approvedProposals = (clone $proposalQuery)
            ->where('status', 'approved')->count();

        $rejectedProposals = (clone $proposalQuery)
            ->where('status', 'rejected')->count();

        // Activity Report Statistics
        $totalActivityReports = (clone $activityReportQuery)->count();

        $totalAttendance = (clone $activityReportQuery)
            ->sum('actual_attendance');

        $totalExpenditure = (clone $activityReportQuery)
            ->sum('actual_expenditure');

        // Latest Proposals
        $proposals = (clone $proposalQuery)
            ->latest()
            ->paginate(10, ['*'], 'proposals_page');

        // Latest Activity Reports
        $activityReports = (clone $activityReportQuery)
            ->with('proposal')
            ->latest('activity_date')
            ->paginate(10, ['*'], 'activity_reports_page');

            return view('manage-proposal.reports.index', compact(
            'totalProposals',
            'draftProposals',
            'pendingProposals',
            'approvedProposals',
            'rejectedProposals',
            'totalActivityReports',
            'totalAttendance',
            'totalExpenditure',
            'proposals',
            'activityReports'
        ));
    }
    
}
