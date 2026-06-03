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
            $paymentType = $session->metadata->payment_type ?? null;

            if ($bookingId) {
                try {
                    $booking = Booking::find($bookingId);
                    if ($booking) {
                        $booking->stripe_payment_id = $session->payment_intent ?? $session->id;

                        if ($paymentType === 'penalty') {

                            $booking->payment_status = 'penalty_paid';
                            $booking->save();

                            Log::info("Stripe Webhook: Pagamento penale ricevuto", [
                                'booking_id' => $bookingId,
                                'stripe_session_id' => $session->id,
                                'amount' => $session->amount_total / 100 . '€'
                            ]);
                        } else {
                            if ($booking->payment_status !== 'paid') {
                                $booking->payment_status = 'paid';
                                $booking->save();

                                Log::info("Stripe Webhook: Pagamento acconto (30%) ricevuto", [
                                    'booking_id' => $bookingId,
                                    'stripe_session_id' => $session->id,
                                    'amount' => $session->amount_total / 100 . '€'
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Errore durante l'aggiornamento del booking {$bookingId}: " . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
