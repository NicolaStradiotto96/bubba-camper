<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CamperController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Admin\BookingEdit;
use App\Livewire\Admin\BookingManager;
use App\Livewire\Admin\CamperManager;
use App\Livewire\Admin\MaintenanceManager;
use App\Livewire\Admin\DamageIndex;
use App\Livewire\Admin\DamageManager;
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
    ->middleware(['auth', 'verified', 'throttle:10,1'])
    ->name('checkout');

Route::get('/prenotazione/{booking}/confermata', [CheckoutController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/prenotazione/{booking}/non-confermata', [CheckoutController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.cancel');

// STRIPE WEBHOOK
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->middleware('throttle:60,1');

// PENALTY
Route::post('/prenotazione/carica-contabile', [PenaltyController::class, 'uploadPenaltyReceipt'])
    ->middleware(['auth', 'verified', 'throttle:10,1'])
    ->name('bookings.upload-receipt');

Route::post('/prenotazione/rifiuta-contabile', [PenaltyController::class, 'rejectReceipt'])
    ->middleware(['auth', 'admin'])
    ->name('bookings.reject-receipt');

Route::get('/prenotazione/{booking}/pagamento-penale-confermato', [PenaltyController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('penalty.success');

Route::get('/prenotazione/{booking}/pagamento-penale-non-confermato', [PenaltyController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('penalty.cancel');

Route::get('/prenotazione/{bookingId}/pagamento-penale', [PenaltyController::class, 'getPenaltyAmount'])
    ->middleware(['auth', 'verified'])
    ->name('pay.penalty');

// PRICES
Route::get('/prezzi', [CamperController::class, "prices"])->name("prices");

// CONTACTS
Route::get('/contatti', [PublicController::class, "contacts"])->name("contacts");

// DASHBOARD
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// CAMPER
Route::get('/admin/camper/crea', CamperManager::class)
    ->middleware(['auth', 'admin'])
    ->name('camper.create');

Route::get('/admin/camper/{camper}/modifica', CamperManager::class)
    ->middleware(['auth', 'admin'])
    ->name('camper.edit');

// BOOKING
Route::get('/admin/prenotazione/crea', BookingManager::class)
    ->middleware(['auth', 'admin'])
    ->name('booking.create');

Route::get('/admin/prenotazione/{booking}/modifica', BookingEdit::class)
    ->middleware(['auth', 'admin'])
    ->name('booking.edit');

// MAINTENANCE
Route::get('/admin/manutenzione', MaintenanceManager::class)
    ->middleware(['auth', 'admin'])
    ->name('maintenance');

// DAMAGE
Route::get('/admin/danni', DamageIndex::class)
    ->middleware(['auth', 'admin'])
    ->name('damage.index');

Route::get('/admin/prenotazione/{booking}/danni', DamageManager::class)
    ->middleware(['auth', 'admin'])
    ->name('damage.add');

Route::get('/admin/prenotazione/{booking}/danni/{damage_id?}/modifica', DamageManager::class)
    ->middleware(['auth', 'admin'])
    ->name('damage.edit');

// VIEW DOCUMENTS
Route::get('/documento/view/{bookingId}/{filename}', [DocumentController::class, 'view'])
    ->middleware(['auth', 'admin', 'throttle:30,1'])
    ->where(['bookingId' => '[0-9]+', 'filename' => '.*'])
    ->name('admin.view-doc');

// PROFILE
Route::view('profilo', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

require __DIR__ . '/auth.php';
