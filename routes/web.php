<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/calendar', function () {
    return view('Manage Calendar/CalendarPage');
});

Route::get('/create', function () {
    return view('Manage Calendar/CreateCalendarPage');
});

Route::get('/update', function () {
    return view('Manage Calendar/UpdateCalendarPage');
});

Route::get('/remind', function () {
    return view('Manage Calendar/AddReminderPage');
});

Route::get('/reminder', function () {
    return view('Manage Calendar/UpdateReminderPage');
});

Route::get('/getevent', 'CalendarController@getEvent')->name('getevent');

Route::post('/createevent','CalendarController@createEvent')->name('createevent');

Route::post('/deleteevent','CalendarController@deleteEvent')->name('deleteevent');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
