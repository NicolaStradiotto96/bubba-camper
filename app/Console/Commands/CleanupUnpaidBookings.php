<?php

namespace App\Console\Commands;

use App\Mail\BookingExpired;
use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:cleanup-unpaid-bookings')]
#[Description('Mark unpaid pending bookings as expired and notify customers')]
class CleanupUnpaidBookings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiryTime = now()->subMinutes(15);

        $query = Booking::where('payment_status', 'unpaid')
            ->where('status', 'pending')
            ->where('created_at', '<', $expiryTime);

        $count = $query->count();

        if ($count === 0) {
            $this->info("Nessuna prenotazione scaduta da processare.");
            return;
        }

        $this->info("Trovate {$count} prenotazioni scadute. Elaborazione in corso...");

        $processed = 0;

        $query->chunkById(100, function ($bookings) use (&$processed) {
            foreach ($bookings as $booking) {
                try {
                    $booking->update([
                        'status' => 'expired',
                        'documents_status' => 'not_required',
                    ]);

                    Mail::to($booking->customer_email)->queue(new BookingExpired($booking));

                    $processed++;
                    $this->info("Booking {$booking->id} scaduta. Email in coda per {$booking->customer_email}.");
                } catch (\Exception $e) {
                    $this->error("Errore processando booking {$booking->id}: " . $e->getMessage());
                }
            }
        });

        $this->info("Operazione completata. Processate {$processed} prenotazioni.");
    }
}
