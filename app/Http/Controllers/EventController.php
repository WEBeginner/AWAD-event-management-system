<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{   //paginate teh user and category
    //add your crud here
    //can refer to Admin EventController

    public function approve(Event $event)
    {
        $event->update(['is_approved' => true]);
        
        return back()->with('success', 'Event approved successfully!');
    }
}