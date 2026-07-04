<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:cleanup-unpaid-bookings')->everyMinute();

Schedule::command('documents:cleanup')->daily();

Schedule::command('livewire:cleanup')->daily();

Schedule::command('emails:send-review-reminders')->dailyAt('09:00');