<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;

            if ($bookingId) {
                try {
                    $booking = Booking::find($bookingId);
                    if ($booking && $booking->payment_status !== 'paid') {
                        $booking->payment_status = 'paid';
                        $booking->save();

                        Log::info("Stripe Webhook: Pagamento ricevuto", [
                            'booking_id' => $bookingId,
                            'stripe_session_id' => $session->id,
                            'amount' => $session->amount_total / 100 . '€'
                        ]);
                    } else {
                        Log::error("Ricevuto pagamento Stripe per booking ID {$bookingId} ma non trovato nel DB.");
                    }
                } catch (\Exception $e) {
                    Log::error("Errore durante l'aggiornamento della booking {$bookingId}: " . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
