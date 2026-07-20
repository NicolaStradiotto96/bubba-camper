<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReminder;
use App\Models\Booking;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // SHOW BOOKING INFO
    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'expired') {
            return redirect()->route('dashboard')->with('swal-error', 'Questa sessione è scaduta definitivamente. Riprova con una nuova ricerca.');
        }

        if ($booking->created_at->lt(now()->subMinutes(15))) {
            $booking->update(['status' => 'expired']);

            $this->logCheckout(
                'booking_expired',
                "Prenotazione #{$booking->id} scaduta per timeout pagamento.",
                $booking
            );

            return redirect()->route('dashboard')->with('swal-error', 'Il tempo per il pagamento è scaduto.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('swal-error', 'L\'acconto per questa prenotazione è già stato pagato.');
        }

        if ($booking->stripe_session_id) {
            try {
                $session = Session::retrieve($booking->stripe_session_id);
                if ($session->status === 'expired' || $session->payment_status === 'paid') {
                    throw new \Exception("Sessione non più utilizzabile");
                }
            } catch (\Exception $e) {
                $session = $this->createNewStripeSession($booking);
            }
        } else {
            $session = $this->createNewStripeSession($booking);
        }

        return redirect($session->url);
    }

    // STRIPE
    private function createNewStripeSession(Booking $booking)
    {
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
                        'description' => "Il restante 70% verrà pagato al ritiro.",
                    ],
                    'unit_amount' => (int) round($booking->down_payment * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('checkout.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $booking),
        ]);

        $booking->update(['stripe_session_id' => $session->id]);

        $this->logCheckout(
            'stripe_session_created',
            "Creata nuova sessione Stripe per la prenotazione #{$booking->id}.",
            $booking,
            ['stripe_session_id' => $session->id]
        );

        return $session;
    }

    // PAID
    public function success(Booking $booking)
    {
        return view('checkout.success', compact('booking'));
    }

    // NOT PAID
    public function cancel(Request $request, Booking $booking)
    {
        if (!$request->session()->has('reminder_sent_' . $booking->id)) {

            try {
                Mail::to($booking->customer_email)->send(new PaymentReminder($booking));

                $this->logCheckout(
                    'payment_reminder_sent',
                    "Inviato promemoria di pagamento via email per la prenotazione #{$booking->id}.",
                    $booking,
                    ['email' => $booking->customer_email]
                );
            } catch (\Exception $e) {
                \Log::error("Errore invio promemoria pagamento [Booking #{$booking->id}]:" . $e->getMessage());
            }

            $request->session()->put('reminder_sent_' . $booking->id, true);
        }

        return view('checkout.cancel', compact('booking'));
    }

    // LOG
    private function logCheckout(string $type, string $message, Booking $booking, array $extraContext = [])
    {
        Log::create([
            'type'    => $type,
            'message' => $message,
            'context' => array_merge([
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'booking_id' => $booking->id,
                'camper_id'  => $booking->camper_id,
                'total'      => $booking->total_price,
            ], $extraContext),
        ]);
    }
}
