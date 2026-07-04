<?php

namespace App\Console\Commands;

use App\Mail\BookingReview;
use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-review-reminders')]
#[Description('Command description')]
class SendReviewReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->subHours(48)->format('Y-m-d');
        $bookings = Booking::whereDate('end_date', $targetDate)->get();

        foreach ($bookings as $booking) {
            $alreadySent = Log::where('booking_id', $booking->id)
                ->where('type', 'review_reminder')
                ->exists();

            if (!$alreadySent) {
                Mail::to($booking->customer_email)->send(new BookingReview($booking));

                Log::create([
                    'booking_id' => $booking->id,
                    'type'       => 'review_reminder',
                    'message'    => 'Inviata email di richiesta recensione per prenotazione #' . $booking->id
                ]);

                $this->info("Mail inviata per prenotazione #" . $booking->id);
            }
        }
    }
}
