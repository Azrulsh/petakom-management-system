<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        if(request()->ajax()){
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $events = Calendar::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)
                    ->get(['id','title','start', 'end']);
            return response()->json($events);
        }
        return view('manage-calendar.index');
         
    }
    
    public function create(Request $request){
         $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'start' => ['required', 'date'],
        'end' => ['required', 'date'],
    ]);

    $event = Calendar::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Reminder added successfully.',
        'event' => $event,
    ], 201);
    }
    
    public function destroy(Request $request){
       $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $event = Calendar::find($request->id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found.'
            ], 404);
        }

        $event->delete();

        return response()->json(1);
    }
        
}
