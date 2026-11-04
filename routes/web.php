<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Registration\StudentController;
use App\Http\Controllers\Registration\LecturerController;
use App\Http\Controllers\Election\CommitteeController;
use App\Http\Controllers\Election\ElectionController;
use App\Http\Controllers\Proposal\ProposalController;
use App\Http\Controllers\Proposal\ActivityReportController;
use App\Http\Controllers\Proposal\ProposalReportController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Election\CandidateController;
use App\Http\Controllers\BulletinController;

// =====================================================
// PUBLIC ROUTES
// =====================================================

Route::view('/', 'homepage')->name('homepage');

Route::redirect('/homepage', '/');

// =====================================================
// AUTHENTICATED DASHBOARD ROUTES
// =====================================================

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('homepage');
    })->name('dashboard');

});

// =====================================================
// AUTHENTICATED MODULES
// =====================================================

Route::middleware(['auth'])->group(function () {

    // =================================================
    // MANAGE CALENDAR
    // All authenticated users can access Calendar.
    // =================================================

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::post('/createcalendar', [CalendarController::class, 'create'])
        ->name('calendar.create');
    
    Route::put('/updatecalendar', [CalendarController::class, 'update'])
        ->name('calendar.update');

    Route::post('/deletecalendar', [CalendarController::class, 'destroy'])
        ->name('calendar.destroy');

    
    // =================================================
    // MANAGE ACTIVITIES
    // =================================================

    // All authenticated users can view activities
    Route::get('/activities', [ActivityController::class, 'index'])
        ->name('activity.index');

    Route::get('/activities/create', [ActivityController::class, 'create'])
        ->name('activity.create');

    Route::post('/activities', [ActivityController::class, 'store'])
        ->name('activity.store');

    Route::get('/activities/{activity}', [ActivityController::class, 'show'])
        ->name('activity.show');

    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])
        ->name('activity.edit');

    Route::put('/activities/{activity}', [ActivityController::class, 'update'])
        ->name('activity.update');

    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])
        ->name('activity.destroy');
    
    Route::patch('/activities/{activity}/status', [ActivityController::class, 'updateStatus'])
        ->name('activity.update-status');

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY REPORT MANAGEMENT
    |--------------------------------------------------------------------------
    | Admin, Committee, Lecturer and Coordinator
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:committee,lecturer,coordinator'])
        ->name('manage-proposal.')
        ->group(function () {

            // Activity Report Approval History
        Route::get(
            '/activity-reports/approval-history',
            [ActivityReportController::class, 'history']
        )->name('activity-reports.history');

        // Activity Report CRUD
        Route::resource(
            'activity-reports',
            ActivityReportController::class
        )->parameters([
            'activity-reports' => 'activityReport'
        ]);

        // Submit / Resubmit Activity Report
        Route::post(
            '/activity-reports/{activityReport}/submit',
            [ActivityReportController::class, 'submit']
        )->name('activity-reports.submit');

        // Approve Activity Report
        Route::post(
            '/activity-reports/{activityReport}/approve',
            [ActivityReportController::class, 'approve']
        )->name('activity-reports.approve');

        // Reject Activity Report
        Route::post(
            '/activity-reports/{activityReport}/reject',
            [ActivityReportController::class, 'reject']
        )->name('activity-reports.reject');

    });
        
    // =================================================
    // MANAGE REGISTRATION
    // Admin only.
    // =================================================

    Route::middleware(['role:admin'])
        ->prefix('registrations')
        ->name('manage-registration.')
        ->group(function () {

            Route::resource('students', StudentController::class);

            Route::resource('lecturers', LecturerController::class);

        });

    // ===================
    // MANAGE PROPOSAL
    // ADMIN & STUDENT
    // ===================

    Route::middleware(['auth', 'role:student'])
        ->name('manage-proposal.')
        ->group(function () {

            // Activity Proposal Management
            Route::resource('proposals', ProposalController::class);

            // Submit Proposal
            Route::post('/proposals/{proposal}/submit', [
                ProposalController::class,
                'submit'
            ])->name('proposals.submit');

        });

    // ==========================================
    // PROPOSAL REPORTS
    // ALL AUTHENTICATED USERS
    // ==========================================

    Route::middleware(['auth'])
        ->name('manage-proposal.')
        ->group(function () {

            Route::get('/proposal-reports', [
                ProposalReportController::class,
                'index'
            ])->name('reports.index');

        });

    // ==========================================
    // PROPOSAL APPROVAL ROUTES
    // LECTURER & COORDINATOR ONLY
    // ==========================================

    Route::middleware(['auth', 'role:lecturer,coordinator'])
        ->prefix('proposal-approvals')
        ->name('proposal-approvals.')
        ->group(function () {

            // Pending Proposal Listing
            Route::get('/', [
                ProposalController::class,
                'reviewIndex'
            ])->name('index');

            // Review Proposal Details
            Route::get('/proposals/{proposal}', [
                ProposalController::class,
                'reviewShow'
            ])->name('show');

            // Approve Proposal
            Route::post('/proposals/{proposal}/approve', [
                ProposalController::class,
                'approve'
            ])->name('proposals.approve');

            // Reject Proposal
            Route::post('/proposals/{proposal}/reject', [
                ProposalController::class,
                'reject'
            ])->name('proposals.reject');
    
            // Approval History Listing
            Route::get('/history', [
                ProposalController::class,
                'historyIndex'
            ])->name('history.index');

            // Individual Proposal Approval History
            Route::get('/proposals/{proposal}/history', [
                ProposalController::class,
                'historyShow'
            ])->name('history.show');

        }); 

    // ==========================================
    // MANAGE COMMITTEE ELECTION
    // ==========================================

    // All authenticated users
    Route::middleware(['auth'])
        ->prefix('committee-election')
        ->name('manage-election.')
        ->group(function () {

            // Election List
            Route::get('/', [ElectionController::class, 'index'])
                ->name('index');

            Route::post('/', [ElectionController::class, 'store'])
                ->name('store');

            Route::get('/{election}', [ElectionController::class, 'show'])
                ->whereNumber('election')
                ->name('show');

            Route::get('/create', [ElectionController::class, 'create'])
                ->name('create');

            Route::get('/{election}/edit', [ElectionController::class, 'edit'])
                ->whereNumber('election')
                ->name('edit');    
            
            Route::put('/{election}', [ElectionController::class, 'update'])
                ->whereNumber('election')
                ->name('update');

            Route::delete('/{election}', [ElectionController::class, 'destroy'])
                ->whereNumber('election')
                ->name('destroy');
                
            Route::patch('/{election}/status', [ElectionController::class, 'updateStatus'])
                ->whereNumber('election')
                ->name('update-status');
                
            // ==========================================
            // COMMITTEE REGISTRATION
            // ==========================================

            // View candidates for a specific election
            Route::get('/{election}/candidates',
                [CandidateController::class, 'index'])
                ->whereNumber('election')
                ->name('candidates.index');
            
            Route::post('/{election}/candidates',
                [CandidateController::class, 'store'])
                ->whereNumber('election')
                ->name('candidates.store');


            Route::get('/{election}/candidates/create',
                [CandidateController::class, 'create'])
                ->whereNumber('election')
                ->name('candidates.create');

            // Approve / Reject Candidate
            Route::patch('/{election}/candidates/{candidate}/review',
                [CandidateController::class, 'review'])
                ->whereNumber(['election', 'candidate'])
                ->middleware('role:admin,coordinator')
                ->name('candidates.review');
            
            Route::patch('/{election}/candidates/{candidate}/withdraw',
                [CandidateController::class, 'withdraw'])
                ->whereNumber(['election', 'candidate'])
                ->name('candidates.withdraw');
            
            // Appoint approved candidate as PETAKOM Committee
            Route::post('/{election}/candidates/{candidate}/appoint',
                [CandidateController::class, 'appoint'])
                ->whereNumber(['election', 'candidate'])
                ->middleware('role:admin')
                ->name('candidates.appoint');
                                
            // Admin Only - Committee Registration
            Route::middleware(['role:admin'])->group(function () {

                Route::resource('committees', CommitteeController::class);

            });

        });
        
    /*
    |--------------------------------------------------------------------------
    | MODULE 6 — MANAGE BULLETIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])
        ->prefix('bulletins')
        ->name('bulletin.')
        ->group(function () {

            // View Bulletin List
            Route::get('/', [BulletinController::class, 'index'])
                ->name('index');

            Route::get('/create', [BulletinController::class, 'create'])
                ->name('create');
            
            Route::post('/', [BulletinController::class, 'store'])
                ->name('store');

            Route::get('/{bulletin}', [BulletinController::class, 'show'])
                ->whereNumber('bulletin')
                ->name('show');

            Route::get('/{bulletin}/edit', [BulletinController::class, 'edit'])
                ->whereNumber('bulletin')
                ->name('edit');
            
            Route::put('/{bulletin}', [BulletinController::class, 'update'])
                ->whereNumber('bulletin')
                ->name('update');
            
            Route::delete('/{bulletin}', [BulletinController::class, 'destroy'])
                ->whereNumber('bulletin')
                ->name('destroy');

            Route::patch('/{bulletin}/status', [BulletinController::class, 'updateStatus'])
                ->whereNumber('bulletin')
                ->name('update-status');

        });

});
