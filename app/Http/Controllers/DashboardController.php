<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $organizedEvents = auth()->user()->organizedEvents()
            ->with('categories')
            ->latest()
            ->paginate(5, ['*'], 'organized_page');
            
        $attendingEvents = auth()->user()->attendingEvents()
            ->with('categories')
            ->upcoming()
            ->paginate(5, ['*'], 'attending_page');
            
        return view('dashboard', compact('organizedEvents', 'attendingEvents'));
    }
}