<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $events = $category->events()
            ->upcoming()
            ->approved()
            ->paginate(12);
            
        return view('categories.show', compact('category', 'events'));
    }
}