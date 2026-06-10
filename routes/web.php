<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CamperController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [PublicController::class, "welcome"])
    ->name("welcome");

// INDEX
Route::get('/noleggio', [CamperController::class, "index"])
    ->name("index");

// SHOW
Route::get('/noleggio/{camper:slug}', [CamperController::class, 'show'])
    ->name('show');

// BOOKING
Route::get('/prenota/{camper:slug}', [BookingController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('booking.show');

// CHECKOUT
Route::get('/checkout/{booking}', [CheckoutController::class, 'show'])
    ->middleware(['auth', 'verified', 'throttle:5,5'])
    ->name('checkout');

Route::get('/checkout/success/{booking}', [CheckoutController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/checkout/cancel/{booking}', [CheckoutController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.cancel');

// STRIPE WEBHOOK
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// PENALTY
Route::post('/bookings/upload-receipt', [PenaltyController::class, 'uploadPenaltyReceipt'])
    ->middleware(['auth', 'verified'])
    ->name('bookings.upload-receipt');

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

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

require __DIR__ . '/auth.php';
