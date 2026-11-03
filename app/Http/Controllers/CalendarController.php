<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Activity\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    
    public function index()
    {
        if (request()->ajax()) {

            $start = request('start');
            $end = request('end');
           
            // Get calendar reminders
            $remindersQuery = Calendar::query();

            // Non-admin users can only view their own reminders
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user->isAdmin()) {
                $remindersQuery->where('user_id', $user->id);
            }

            // Get reminders within calendar date range
            $reminders = $remindersQuery
                ->where('start', '<', $end)
                ->where('end', '>', $start)
                ->get(['id', 'title', 'start', 'end'])  
                ->map(function ($reminder) {
                    return [
                        'id' => 'reminder_' . $reminder->id,
                        'title' => $reminder->title,
                        'start' => \Carbon\Carbon::parse($reminder->start)
                            ->format('Y-m-d'),
                        'end' => \Carbon\Carbon::parse($reminder->end)
                            ->addDay()
                            ->format('Y-m-d'),
                        'allDay' => true,
                        'type' => 'reminder',
                        'color' => '#3788d8',
                    ];
                });


            // Get PETAKOM activities
            $activities = Activity::whereDate('start_date', '<', $end)
                ->where(function ($query) use ($start) {
                    $query->whereDate('end_date', '>=', $start)
                        ->orWhere(function ($q) use ($start) {
                            $q->whereNull('end_date')
                                ->whereDate('start_date', '>=', $start);
                        });
                })
                ->get()
                ->map(function ($activity) {

                    $endDate = $activity->end_date
                        ?? $activity->start_date;

                    return [
                        'id' => 'activity_' . $activity->id,
                        'title' => $activity->title,
                        'start' => $activity->start_date->format('Y-m-d'),
                        'end' => Carbon::parse($endDate)
                            ->addDay()
                            ->format('Y-m-d'),
                        'allDay' => true,
                        'type' => 'activity',
                        'status' => $activity->status,
                        'color' => '#198754',
                    ];
                });

            // Combine reminders and activities
            $events = $reminders->concat($activities);

            return response()->json($events);
        }

        return view('manage-calendar.index');
    }
    
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
        ]);

        Calendar::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder created successfully.'
        ]);
    }

    public function update(Request $request)
    {
        // Validate reminder information
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:calendars,id'],
            'title' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Find reminder
        $reminder = Calendar::findOrFail($validated['id']);

        // Only reminder owner or Admin can update
        abort_unless(
            $user->isAdmin() || $reminder->user_id == $user->id,
            403,
            'You are not authorized to update this reminder.'
        );

        // Update reminder information
        $reminder->update([
            'title' => $validated['title'],
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder updated successfully.',
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:calendars,id'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Find selected reminder
        $reminder = Calendar::findOrFail($validated['id']);

        // Only owner or Admin can delete
        abort_unless(
            $user->isAdmin() || $reminder->user_id === $user->id,
            403,
            'You are not authorized to delete this reminder.'
        );

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully.',
        ]);
    }
        
}
