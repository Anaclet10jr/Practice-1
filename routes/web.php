<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────
Route::get('/', function () {
    $featured = \App\Models\Property::approved()->featured()->limit(6)->get();
    return view('home', compact('featured'));
})->name('home');

Route::get('/properties',          [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// ── Auth ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Agent ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {

    Route::get('/pending', function () {
        return view('agent.pending');
    })->name('pending');

    // Routes below require agent approval
    Route::middleware('agent.approved')->group(function () {
        Route::get('/dashboard', function () {
            return view('agent.dashboard', ['user' => auth()->user()]);
        })->name('dashboard');

        Route::get('/properties',              [PropertyController::class, 'agentIndex'])->name('properties.index');
        Route::get('/properties/create',        [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties',              [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{property}',    [PropertyController::class, 'update'])->name('properties.update');
        Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    });
});

// ── Admin ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Agents
    Route::get('/agents',                [AdminController::class, 'agents'])->name('agents');
    Route::post('/agents/{user}/approve', [AdminController::class, 'approveAgent'])->name('agents.approve');
    Route::post('/agents/{user}/reject',  [AdminController::class, 'rejectAgent'])->name('agents.reject');
    Route::delete('/agents/{user}',       [AdminController::class, 'destroyAgent'])->name('agents.destroy');

    // Properties
    Route::get('/properties',                      [AdminController::class, 'properties'])->name('properties');
    Route::get('/properties/{property}',            [AdminController::class, 'showProperty'])->name('properties.show');
    Route::post('/properties/{property}/approve',   [AdminController::class, 'approveProperty'])->name('properties.approve');
    Route::post('/properties/{property}/reject',    [AdminController::class, 'rejectProperty'])->name('properties.reject');
    Route::post('/properties/{property}/feature',   [AdminController::class, 'featureProperty'])->name('properties.feature');
    Route::delete('/properties/{property}',         [AdminController::class, 'destroyProperty'])->name('properties.destroy');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});


use App\Http\Controllers\PropertyController;

Route::get('/', [PropertyController::class, 'index']);

Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/create', [PropertyController::class, 'create']);
Route::post('/properties', [PropertyController::class, 'store']);