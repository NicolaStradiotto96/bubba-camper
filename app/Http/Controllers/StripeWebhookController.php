<?php

namespace App\Http\Controllers;

use App\Livewire\DamageManager;
use App\Mail\BookingPaid;
use App\Mail\BookingPaidNotification;
use App\Mail\PenaltyPaid;
use App\Mail\PenaltyPaidNotification;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                            $booking->balance_paid = true;
                            $booking->balance_paid_at = now();
                            $booking->penalty_paid_at = now();
                            $booking->save();

                            Mail::to($booking->customer_email)->send(new PenaltyPaid($booking));
                            Mail::to(config('app.admin_email'))->send(new PenaltyPaidNotification($booking));

                            Log::info("Stripe Webhook: Pagamento penale ricevuto", [
                                'booking_id' => $bookingId,
                                'stripe_session_id' => $session->id,
                                'amount' => $session->amount_total / 100 . '€'
                            ]);
                        } elseif ($paymentType === 'damages') {
                            DamageManager::markDamagesAsPaid($bookingId);
                            $booking->save();

                            Log::info("Stripe Webhook: Pagamento danni ricevuto", ['booking_id' => $bookingId]);

                            // MAIL
                        } else {
                            if ($booking->payment_status !== 'paid') {
                                $booking->payment_status = 'paid';
                                $booking->down_paid = true;
                                $booking->down_paid_at = now();
                                $booking->save();

                                Mail::to($booking->customer_email)->send(new BookingPaid($booking));
                                Mail::to(config('app.admin_email'))->send(new BookingPaidNotification($booking));

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
