<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CamperController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Admin\BookingManager;
use App\Livewire\Admin\CamperManager;
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
Route::get('/prenotazione/{booking}', [CheckoutController::class, 'show'])
    ->middleware(['auth', 'verified', 'throttle:5,5'])
    ->name('checkout');

Route::get('/prenotazione/{booking}/confermata', [CheckoutController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/prenotazione/{booking}/non-confermata', [CheckoutController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.cancel');

// STRIPE WEBHOOK
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// PENALTY
Route::post('/prenotazione/carica-contabile', [PenaltyController::class, 'uploadPenaltyReceipt'])
    ->middleware(['auth', 'verified'])
    ->name('bookings.upload-receipt');

Route::get('/prenotazione/{booking}/pagamento-penale-confermato', [PenaltyController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('penalty.success');

Route::get('/prenotazione/{booking}/pagamento-penale-non-confermato', [PenaltyController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('penalty.cancel');

// PRICES
Route::get('/prezzi', [CamperController::class, "prices"])->name("prices");

// CONTACTS
Route::get('/contatti', [PublicController::class, "contacts"])->name("contacts");

// DASHBOARD
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// CREATE CAMPER
Route::get('/admin/camper/crea', CamperManager::class)
    ->middleware(['auth', 'admin'])
    ->name('camper.create');

// EDIT CAMPER
Route::get('/admin/camper/{camper}/modifica', CamperManager::class)
    ->middleware(['auth', 'admin'])
    ->name('camper.edit');

// CREATE BOOKING
Route::get('/admin/prenotazione/crea', BookingManager::class)
    ->middleware(['auth', 'admin'])
    ->name('booking.create');

// VIEW DOCUMENTS
Route::get('/documento/view/{bookingId}/{filename}', [DocumentController::class, 'view'])
    ->middleware(['auth', 'admin'])
    ->where(['bookingId' => '[0-9]+', 'filename' => '.*'])
    ->name('admin.view-doc');

// PROFILE
Route::view('profilo', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

require __DIR__ . '/auth.php';
