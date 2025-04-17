<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminEventController ; // Ensure this class exists in the specified namespace

// Ensure the Admin\EventController class exists in the specified namespace.
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', [EventController::class, 'index'])->name('home');
Route::resource('events', EventController::class)->only(['index', 'show']);
Route::resource('categories', CategoryController::class)->only(['index', 'show']);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Event attendance
    Route::middleware(['role:attendee'])->group(function () {
        Route::post('/events/{event}/attend', [AttendeeController::class, 'store'])
            ->name('events.attend');
        Route::delete('/events/{event}/cancel', [AttendeeController::class, 'destroy'])
            ->name('events.cancel');
    });
    
    // Event management (admin/organizer only)
    Route::middleware(['role:admin,organizer'])->group(function () {
        Route::resource('events', EventController::class)->except(['index', 'show']);
    });
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::patch('/events/{event}/approve', [AdminEventController::class, 'approve'])
        ->name('events.approve');
});