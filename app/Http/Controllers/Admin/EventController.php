<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class EventController extends Controller
{
    
    public function index()
    {
        $events = Event::with('categories', 'user')
            ->upcoming()
            ->approved()
            ->latest()
            ->paginate(12);
            
        $categories = Category::all();
        
        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        if (!$event->is_approved && !Gate::allows('admin')) {
            abort(404);
        }
        
        $isAttending = auth()->check() ? auth()->user()->isAttending($event->id) : false;
        
        return view('events.show', compact('event', 'isAttending'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);
        
        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'],
            'price' => $validated['price'],
            'is_approved' => auth()->user()->can('admin'),
        ]);
        
        if (isset($validated['categories'])) {
            $event->categories()->attach($validated['categories']);
        }
        
        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        $categories = Category::all();
        $selectedCategories = $event->categories->pluck('id')->toArray();
        
        return view('events.edit', compact('event', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        
        $event->update($validated);
        
        if (isset($validated['categories'])) {
            $event->categories()->sync($validated['categories']);
        } else {
            $event->categories()->detach();
        }
        
        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        $event->delete();
        
        return redirect()->route('dashboard')
            ->with('success', 'Event deleted successfully!');
    }
}