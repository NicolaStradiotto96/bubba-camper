<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->created_at->lt(now()->subMinutes(15))) {
            if ($booking->status !== 'expired') {
                $booking->status = 'expired';
                $booking->save();
            }

            return redirect()->route('dashboard')->with('error', 'Il tempo per il pagamento è scaduto. Effettua una nuova prenotazione.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'Questa prenotazione è già stata pagata.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => (string) $booking->id,
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => "Prenotazione Camper: " . $booking->camper->name,
                    ],
                    'unit_amount' => (int)($booking->total_price * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('checkout.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $booking),
        ]);

        return redirect($session->url);
    }

    public function success(Booking $booking, Request $request)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        return view('checkout.success', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        return view('checkout.cancel', compact('booking'));
    }
}
