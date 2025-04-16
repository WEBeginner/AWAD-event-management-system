<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->isPast()) {
            return back()->with('error', 'This event has already ended.');
        }
        
        if ($event->isFull()) {
            return back()->with('error', 'This event has reached its capacity.');
        }
        
        if (auth()->user()->isAttending($event->id)) {
            return back()->with('error', 'You are already registered for this event.');
        }
        
        Attendee::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'status' => 'registered',
            'ticket_count' => 1,
        ]);
        
        return back()->with('success', 'You have successfully registered for this event!');
    }

    public function destroy(Event $event)
    {
        $attendee = Attendee::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();
            
        if (!$attendee) {
            return back()->with('error', 'You are not registered for this event.');
        }
        
        $attendee->delete();
        
        return back()->with('success', 'Your registration has been cancelled.');
    }
}