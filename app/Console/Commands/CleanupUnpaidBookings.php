<?php

namespace App\Console\Commands;

use App\Mail\BookingExpired;
use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:cleanup-unpaid-bookings')]
#[Description('Command description')]
class CleanupUnpaidBookings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiryTime = now()->subMinutes(15);

        $expiredBookings = Booking::where('payment_status', 'unpaid')
            ->where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->status = 'expired';
            $booking->documents_status = 'not_required';
            $booking->save();

            try {
                Mail::to($booking->customer_email)->send(new BookingExpired($booking));
                $this->info("Booking {$booking->id} scaduta. Email inviata a {$booking->customer_email}.");
            } catch (\Exception $e) {
                $this->error("Impossibile inviare mail per booking {$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("Operazione di cleanup completata.");
    }
}
