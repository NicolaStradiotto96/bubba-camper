<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('registrati', 'pages.auth.register')
        ->name('register');

    Volt::route('accedi', 'pages.auth.login')
        ->name('login');

    Volt::route('password-dimenticata', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reimposta-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verifica-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verifica-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('conferma-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
