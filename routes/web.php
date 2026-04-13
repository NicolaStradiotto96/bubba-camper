<?php

use App\Http\Controllers\CamperController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [PublicController::class, "welcome"])->name("welcome");

// INDEX
Route::get('/noleggio', [CamperController::class, "index"])->name("index");

// PRICES
Route::get('/prezzi', [CamperController::class, "prices"])->name("prices");

// CONTACTS
Route::get('/contatti', [PublicController::class, "contacts"])->name("contacts");

// DASHBOARD
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// PROFILE
Route::view('profilo', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
