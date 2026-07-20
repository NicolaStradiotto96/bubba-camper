<?php

namespace App\Console\Commands;

use App\Mail\BookingReview;
use App\Models\Booking;
use App\Models\Log;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-review-reminders')]
#[Description('Send review request emails to customers 48 hours after end_date')]
class SendReviewReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->subHours(48)->toDateString();

        $bookingsQuery = Booking::whereDate('end_date', $targetDate)
            ->whereDoesntHave('logs', function ($query) {
                $query->where('type', 'review_reminder');
            });

        $count = $bookingsQuery->count();

        if ($count === 0) {
            $this->info("Nessuna prenotazione trovata per l'invio delle recensioni.");
            return;
        }

        $this->info("Trovate {$count} prenotazioni. Inizio invio...");

        $bookingsQuery->chunkById(100, function ($bookings) {
            foreach ($bookings as $booking) {
                try {
                    Mail::to($booking->customer_email)->send(new BookingReview($booking));

                    Log::create([
                        'booking_id' => $booking->id,
                        'type'       => 'review_reminder',
                        'message'    => "Inviata email di richiesta recensione per prenotazione #{$booking->id}"
                    ]);

                    $this->info("Email in coda per prenotazione #{$booking->id}");
                } catch (\Exception $e) {
                    $this->error("Errore invio per prenotazione #{$booking->id}: " . $e->getMessage());
                }
            }
        });

        $this->info("Operazione completata.");
    }
}
