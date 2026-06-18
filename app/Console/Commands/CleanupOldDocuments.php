<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:cleanup-old-documents')]
#[Description('Command description')]
class CleanupOldDocuments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldBookings = Booking::where('end_date', '<', now()->subMonths(6))->get();

        foreach ($oldBookings as $booking) {
            Storage::disk('local')->deleteDirectory("documents/{$booking->id}");

            $booking->update([
                'driver_license_front_path' => null,
                'driver_license_back_path' => null,
                'id_card_front_path' => null,
                'id_card_back_path' => null,
            ]);
        }
    }
}
