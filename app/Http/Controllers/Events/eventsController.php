<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;

class eventsController extends Controller
{
    public function getEvents(){

       $events=Events::select('*')->get();
       return $events;
    }

    public function postEvents(Request $request){

       $events=Events::create([
        'name'=>$request['name'],
        'details'=>$request['details'],
        'start'=>$request['start'],
        'end'=>$request['end'],
        'color'=>$request['color'],
       ]);
       return $events;
    }
    
    public function putEvents(Request $request){

      if ($request['start'] != null || $request['end'] != null){
        $events=Events::where('id','=',$request['id'])->update([
            'name'=>$request['name'],
            'details'=>$request['details'],
            'start'=>$request['start'],
            'end'=>$request['end'],
            'color'=>$request['color'],
        ]);
        return $events;
      } 
      else{
        $events=Events::where('id','=',$request['id'])->update([
            'name'=>$request['name'],
            'details'=>$request['details'],
            'color'=>$request['color'],
        ]);
        return $events;
      }
    }
    public function deleteEvents(Request $request){

        $events=Events::where('id','=',$request['id'])->delete();

        return $events;
    }
}
