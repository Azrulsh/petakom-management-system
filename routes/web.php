<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registration\StudentController;
use App\Http\Controllers\Registration\LecturerController;
use App\Http\Controllers\Registration\CommitteeController;
use App\Http\Controllers\CalendarController;

//homepage
Route::view('/', 'homepage')->name('homepage');
Route::redirect('/homepage', '/');

//manage calendar
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/createcalendar', [CalendarController::class, 'create'])->name('calendar.create');
Route::post('/deletecalendar', [CalendarController::class, 'destroy'])->name('calendar.destroy');

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])
->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
});

// Route::middleware(['auth'])
Route::prefix('registrations')
    ->name('manage-registration.')
    ->group(function () {

        Route::resource('students', StudentController::class);
        Route::resource('lecturers', LecturerController::class);
        Route::resource('committees', CommitteeController::class);

    });
