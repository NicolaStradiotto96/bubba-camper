<?php

namespace App\Http\Controllers;

use App\Mail\BookingPaid;
use App\Mail\BookingPaidNotification;
use App\Mail\PenaltyDamagePaid;
use App\Mail\PenaltyDamagePaidNotification;
use App\Mail\PenaltyPaid;
use App\Mail\PenaltyPaidNotification;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\Log;
use Illuminate\Http\Request;
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

        if ($event->type !== 'checkout.session.completed') {
            return response()->json(['status' => 'ignored'], 200);
        }

        $session = $event->data->object;
        $bookingId = $session->metadata->booking_id ?? null;
        $paymentType = $session->metadata->payment_type ?? null;

        if (!$bookingId) return response()->json(['error' => 'No booking_id'], 400);

        $booking = Booking::find($bookingId);
        if (!$booking) return response()->json(['error' => 'Booking not found'], 404);

        try {
            $booking->stripe_payment_id = $session->payment_intent ?? $session->id;
            $amount = $session->amount_total / 100;

            // PENALTY
            if ($paymentType === 'penalty') {
                if ($booking->payment_status === 'penalty_paid') return response()->json(['status' => 'already_processed']);

                $booking->update([
                    'payment_status' => 'penalty_paid',
                    'balance_paid' => true,
                    'balance_paid_at' => now(),
                    'penalty_paid_at' => now()
                ]);

                Mail::to($booking->customer_email)->send(new PenaltyPaid($booking));
                Mail::to(config('app.admin_email'))->send(new PenaltyPaidNotification($booking));

                $this->logStripeEvent('payment_penalty', 'Pagamento penale ricevuto', $booking, [
                    'amount' => $amount,
                    'stripe_session_id' => $session->id
                ]);
            }

            // DAMAGES
            elseif ($paymentType === 'damages') {
                $damage = Damage::where('id', $session->metadata->damage_id ?? null)
                    ->where('booking_id', $booking->id)
                    ->first();

                if ($damage && $damage->status !== 'paid') {
                    $damage->update(['status' => 'paid']);

                    Mail::to($damage->booking->customer_email)->send(new PenaltyDamagePaid($damage));
                    Mail::to(config('app.admin_email'))->send(new PenaltyDamagePaidNotification($damage));

                    $this->logStripeEvent('payment_damage', 'Pagamento danno ricevuto', $booking, [
                        'damage_id' => $damage->id,
                        'amount' => $amount
                    ]);
                }
            }

            // BOOKING
            else {
                if ($booking->payment_status === 'paid') return response()->json(['status' => 'already_processed']);

                $booking->update([
                    'payment_status' => 'paid',
                    'down_paid' => true,
                    'down_paid_at' => now()
                ]);

                Mail::to($booking->customer_email)->send(new BookingPaid($booking));
                Mail::to(config('app.admin_email'))->send(new BookingPaidNotification($booking));

                $this->logStripeEvent('payment_booking', 'Pagamento acconto ricevuto', $booking, [
                    'amount' => $amount,
                    'stripe_session_id' => $session->id
                ]);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            \Log::error("Stripe Webhook Error: " . $e->getMessage(), ['booking_id' => $bookingId]);
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    // LOG
    private function logStripeEvent(string $type, string $message, Booking $booking, array $extraContext = [])
    {
        Log::create([
            'type'    => $type,
            'message' => $message,
            'context' => array_merge([
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'booking_id' => $booking->id,
                'camper_id'  => $booking->camper_id,
            ], $extraContext),
        ]);
    }
}
