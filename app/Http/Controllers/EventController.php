<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['user', 'categories'])
            ->latest()
            ->paginate(15);
            
        return view('admin.events.index', compact('events'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);
        
        $event->update($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function approve(Event $event)
    {
        $event->update(['is_approved' => true]);
        
        return back()->with('success', 'Event approved successfully!');
    }
}