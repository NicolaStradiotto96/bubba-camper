<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:cleanup-old-documents')]
#[Description('Delete documents from bookings concluded more than 6 months ago')]
class CleanupOldDocuments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookingsQuery = Booking::where('end_date', '<', now()->subMonths(6))
            ->where(function ($query) {
                $query->whereNotNull('driver_license_front_path')
                    ->orWhereNotNull('driver_license_back_path')
                    ->orWhereNotNull('id_card_front_path')
                    ->orWhereNotNull('id_card_back_path');
            });

        $count = $bookingsQuery->count();

        if ($count === 0) {
            $this->info("Nessun documento vecchio da eliminare.");
            return;
        }

        $this->info("Trovate {$count} prenotazioni. Inizio la pulizia...");

        $processed = 0;

        $bookingsQuery->chunkById(100, function ($bookings) use (&$processed) {
            foreach ($bookings as $booking) {
                try {
                    Storage::disk('local')->deleteDirectory("documents/{$booking->id}");

                    $booking->update([
                        'driver_license_front_path' => null,
                        'driver_license_back_path'  => null,
                        'id_card_front_path'        => null,
                        'id_card_back_path'         => null,
                    ]);

                    $processed++;
                } catch (\Exception $e) {
                    $this->error("Errore durante l'eliminazione della prenotazione ID {$booking->id}: " . $e->getMessage());
                    continue;
                }
            }
        });

        $this->info("Pulizia completata con successo! Eliminati i documenti di {$processed} prenotazioni.");
    }
}
