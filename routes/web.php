<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CalendarController;
// use App\Http\Controllers\Registration\StudentController;
// use App\Http\Controllers\Registration\LecturerController;
// use App\Http\Controllers\Registration\CommitteeController;
// use App\Http\Controllers\Proposal\ProposalController;
// use App\Http\Controllers\Proposal\ActivityReportController;
// use App\Http\Controllers\Proposal\ProposalReportController;

// //homepage
// Route::view('/', 'homepage')->name('homepage');
// Route::redirect('/homepage', '/');

// //manage calendar
// Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
// Route::post('/createcalendar', [CalendarController::class, 'create'])->name('calendar.create');
// Route::post('/deletecalendar', [CalendarController::class, 'destroy'])->name('calendar.destroy');

// Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])
// ->group(function () {
//     Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
// });

// // Route::middleware(['auth'])
// Route::prefix('registrations')
//     ->name('manage-registration.')
//     ->group(function () {

//         Route::resource('students', StudentController::class);
//         Route::resource('lecturers', LecturerController::class);
//         Route::resource('committees', CommitteeController::class);

//     });

// /*
// |--------------------------------------------------------------------------
// | Manage Proposal
// |--------------------------------------------------------------------------
// */

// // Route::middleware(['auth'])
// Route::name('manage-proposal.')
//     ->group(function () {

//         // Activity Proposal Management
//         Route::resource('proposals', ProposalController::class);

//         // Activity Report Management
//         Route::resource('activity-reports', ActivityReportController::class)
//             ->parameters([
//                 'activity-reports' => 'activityReport'
//             ]);

//         // Proposal and Activity Reports
//         Route::get('/proposal-reports', [
//             ProposalReportController::class,
//             'index'
//         ])->name('reports.index');

        
//         Route::post('/proposals/{proposal}/submit', [
//             ProposalController::class,
//             'submit'
//         ])->name('proposals.submit');


//     });

//     // STUDENT ACCESS
//     Route::middleware(['auth', 'role:student'])
//         ->prefix('student')
//         ->name('student.')
//         ->group(function () {

//             Route::get('/dashboard', function () {
//                 return view('dashboard');
//             })->name('dashboard');

//         });


//     // LECTURER ACCESS
//     Route::middleware(['auth', 'role:lecturer'])
//         ->prefix('lecturer')
//         ->name('lecturer.')
//         ->group(function () {

//             Route::get('/dashboard', function () {
//                 return view('dashboard');
//             })->name('dashboard');

//         });


//     // COMMITTEE SECRETARIAT ACCESS
//     Route::middleware(['auth', 'role:committee'])
//         ->prefix('committee')
//         ->name('committee.')
//         ->group(function () {

//             Route::get('/dashboard', function () {
//                 return view('dashboard');
//             })->name('dashboard');

//         });


//     // COORDINATOR ACCESS
//     Route::middleware(['auth', 'role:coordinator'])
//         ->prefix('coordinator')
//         ->name('coordinator.')
//         ->group(function () {

//             Route::get('/dashboard', function () {
//                 return view('dashboard');
//             })->name('dashboard');

//         });


//     // LECTURER AND COORDINATOR
//     Route::middleware(['auth', 'role:lecturer,coordinator'])
//         ->prefix('proposal-approvals')
//         ->name('proposal-approvals.')
//         ->group(function () {

//             // Approval routes will be added in Part 4.

//         });

//         Route::get('/test-coordinator', function () {
//     return 'Coordinator Access Granted';
// })->middleware(['auth', 'role:coordinator']);


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\Registration\StudentController;
use App\Http\Controllers\Registration\LecturerController;
use App\Http\Controllers\Registration\CommitteeController;

use App\Http\Controllers\Proposal\ProposalController;
use App\Http\Controllers\Proposal\ActivityReportController;
use App\Http\Controllers\Proposal\ProposalReportController;

use App\Http\Controllers\Activity\ActivityController;

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
        ->name('manage-activity.index');

    Route::get('/activities/create', [ActivityController::class, 'create'])
        ->name('manage-activity.create');

    Route::post('/activities', [ActivityController::class, 'store'])
        ->name('manage-activity.store');

    Route::get('/activities/{activity}', [ActivityController::class, 'show'])
        ->name('manage-activity.show');

    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])
        ->name('manage-activity.edit');

    Route::put('/activities/{activity}', [ActivityController::class, 'update'])
        ->name('manage-activity.update');

    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])
        ->name('manage-activity.destroy');
    
    Route::patch('/activities/{activity}/status', [ActivityController::class, 'updateStatus'])
        ->name('manage-activity.update-status');
        
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

            Route::resource('committees', CommitteeController::class);

        });


    // =================================================
    // MANAGE PROPOSAL
    // Authorized PETAKOM roles.
    // =================================================

    Route::middleware([
        'role:student,lecturer,committee,coordinator'
    ])
        ->name('manage-proposal.')
        ->group(function () {

            // Activity Proposal Management
            Route::resource('proposals', ProposalController::class);

            // Generate Reports
            Route::get('/proposal-reports', [
                ProposalReportController::class,
                'index'
            ])->name('reports.index');

            // Submit Proposal
            Route::post('/proposals/{proposal}/submit', [
                ProposalController::class,
                'submit'
            ])->name('proposals.submit');

            

        });


    
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

    

});
