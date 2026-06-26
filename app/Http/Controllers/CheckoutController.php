<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReminder;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'expired') {
            return redirect()->route('dashboard')->with('error', 'Questa sessione è scaduta definitivamente. Riprova con una nuova ricerca.');
        }

        if ($booking->created_at->lt(now()->subMinutes(15))) {
            if ($booking->status !== 'expired') {
                $booking->status = 'expired';
                $booking->save();
            }

            return redirect()->route('dashboard')->with('error', 'Il tempo per il pagamento è scaduto.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'L\'acconto per questa prenotazione è già stato pagato.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => (string) $booking->id,
                'payment_type' => 'deposit_30',
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => "Acconto (30%) - Camper: " . $booking->camper->name,
                        'description' => "Il restante 70% (€" . number_format($booking->balance_payment, 2, ',', '.') . ") verrà pagato al ritiro del mezzo.",
                    ],
                    'unit_amount' => (int) round($booking->down_payment * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('checkout.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $booking),
        ]);

        return redirect($session->url);
    }

    public function success(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        return view('checkout.success', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        if (!$request->session()->has('reminder_sent_' . $booking->id)) {

            Mail::to($booking->customer_email)->send(new PaymentReminder($booking));

            $request->session()->put('reminder_sent_' . $booking->id, true);
        }

        return view('checkout.cancel', compact('booking'));
    }
}
