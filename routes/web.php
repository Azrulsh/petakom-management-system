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
//     Route::middleware(['auth', 'role:committee_secretariat'])
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

    Route::post('/deletecalendar', [CalendarController::class, 'destroy'])
        ->name('calendar.destroy');


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

            // Activity Report Management
            Route::resource(
                'activity-reports',
                ActivityReportController::class
            )->parameters([
                'activity-reports' => 'activityReport'
            ]);

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

});
