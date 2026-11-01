<?php

namespace App\Http\Controllers;

use App\Models\CalendarRecord;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function getEvent(){
        if(request()->ajax()){
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $events = CalendarRecord::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)
                    ->get(['id','title','start', 'end']);
            return response()->json($events);
        }
        return view('Manage Calendar/CalendarPage');
         
    }
    public function createEvent(Request $request){
        $data = $request->except('_token');
        $events = CalendarRecord::insert($data);
        return response()->json($events);
    }
    
    public function deleteEvent(Request $request){
        $event = CalendarRecord::find($request->id);
        return $event->delete();
    }
        
}
