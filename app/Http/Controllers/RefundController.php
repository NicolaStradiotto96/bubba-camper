<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Refund;
use Stripe\Stripe;

class RefundController extends Controller
{
    public function process(Booking $booking, Request $request)
    {
        try {
            $booking->refund_requested_at = now();
            $booking->save();

            $refundInfo = $booking->calculateExpectedRefund();
            $amountToRefund = $refundInfo['refund_amount'];

            if ($amountToRefund <= 0) {
                $paymentStatus = 'no_refund';
                $amountToRefund = 0;
            } elseif ($request->input('use_stripe') === "1" && $booking->stripe_payment_id) {
                Stripe::setApiKey(config('services.stripe.secret'));
                Refund::create([
                    'payment_intent' => $booking->stripe_payment_id,
                    'amount' => (int)($amountToRefund * 100),
                ]);
                $paymentStatus = 'refunded_stripe';
            } else {
                $paymentStatus = 'refunded_manual';
            }

            $booking->status = 'cancelled';
            $booking->payment_status = $paymentStatus;
            $booking->refund_amount = $amountToRefund;
            $booking->refund_confirmed_at = now();
            $booking->save();

            $msg = $amountToRefund <= 0
                ? "Prenotazione Annullata (Penale 100%)."
                : "Prenotazione Annullata e rimborsata in data " . $booking->refund_confirmed_at->format('d/m/Y H:i') . ". " . ($request->input('use_stripe') === "1" ? "Rimborso Stripe di {$amountToRefund}€ inviato." : "Rimborso manuale dovuto: {$amountToRefund}€.");

            return back()->with('cancelled', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Errore: ' . $e->getMessage());
        }
    }
}
