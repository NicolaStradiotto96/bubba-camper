<?php

namespace App\Livewire\User;

use App\Models\Booking;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class BookingHistory extends Component
{
    use WithPagination;

    public function openBookingDetails($bookingId)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('app:cleanup-unpaid-bookings');
        } catch (\Exception $e) {
        }

        $booking = Booking::with('camper')->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'             => $booking->id,
            'created_at'     => $booking->created_at->format('d/m/Y \a\l\l\e H:i'),
            'name'           => "{$booking->customer_first_name} {$booking->customer_last_name}",
            'email'          => $booking->customer_email,
            'phone'          => $booking->customer_phone,
            'camper'         => $booking->camper->name,
            'start'          => $booking->start_date->format('d/m/Y'),
            'end'            => $booking->end_date->format('d/m/Y'),
            'total'          => number_format($booking->total_price, 2, ',', '.') . '€',
            'deposit'        => number_format($booking->deposit_amount, 2, ',', '.') . '€',
            'deposit_amount' => (float)($booking->deposit_amount ?? 0),
            'balance' => $booking->status === 'cancelled'
                ? ($booking->calculateExpectedRefund()['penalty_amount'] > $booking->deposit_amount && $booking->payment_status !== 'fully_paid' && $booking->payment_status !== 'penalty_paid'
                    ? number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') . '€'
                    : (($booking->payment_status === 'fully_paid' || $booking->payment_status === 'penalty_paid')
                        ? number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') . '€'
                        : '0,00€'))
                : number_format($booking->balance_amount, 2, ',', '.') . '€',
            'refund'         => number_format($booking->refund_amount, 2, ',', '.') . '€',
            'refundRaw'      => (float)($booking->refund_amount ?? 0),
            'penalty'        => number_format($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0, 2, ',', '.') . '€',
            'penaltyRaw'     => (float)($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0),
            'status'         => $booking->status,
            'payment_status' => $booking->payment_status
        ]);
    }

    #[On('requestCancellation')]
    public function requestCancellation($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status === 'pending' || $booking->status === 'confirmed') {

            $booking->status = 'cancellation_pending';
            $booking->save();

            //  Mail::to('info@bubbacamper.com')->send(new CancellationRequest($dataForEmail));

            session()->flash('success', "La richiesta di annullamento per la prenotazione #{$booking->id} è in fase di elaborazione.");
        }
    }

    #[On('processPenaltyPayment')]
    public function processPenaltyPayment($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status !== 'cancelled' || $booking->payment_status !== 'no_refund') {
            return;
        }

        $penaltyAmount = $booking->calculateExpectedRefund()['penalty_amount'] ?? 0;

        if ($penaltyAmount <= 0) {
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => (string) $booking->id,
                'payment_type' => 'penalty',
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => "Saldatura Penale di Annullamento - Prenotazione #{$booking->id}",
                        'description' => "Penale contrattuale calcolata per i termini di cancellazione del viaggio.",
                    ],
                    'unit_amount' => (int)($penaltyAmount * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('checkout.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $booking),
        ]);

        return redirect($session->url);
    }

    public function render()
    {
        $bookings = auth()->user()->bookings()
            ->with('camper')
            ->latest()
            ->paginate(10);

        return view('livewire.user.booking-history', [
            'bookings' => $bookings
        ]);
    }
}
