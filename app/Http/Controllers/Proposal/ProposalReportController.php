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
        // Proposal Statistics
        $totalProposals = Proposal::count();

        $draftProposals = Proposal::where('status', 'draft')->count();

        $pendingProposals = Proposal::where('status', 'pending')->count();

        $approvedProposals = Proposal::where('status', 'approved')->count();

        $rejectedProposals = Proposal::where('status', 'rejected')->count();

        // Activity Report Statistics
        $totalActivityReports = ActivityReport::count();

        $totalAttendance = ActivityReport::sum('actual_attendance');

        $totalExpenditure = ActivityReport::sum('actual_expenditure');

        // Latest Proposals
        $proposals = Proposal::latest()->paginate(10, ['*'], 'proposals_page');

        // Latest Activity Reports
        $activityReports = ActivityReport::with('proposal')
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
