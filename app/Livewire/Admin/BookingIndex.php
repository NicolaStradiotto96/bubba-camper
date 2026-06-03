<?php

namespace App\Livewire\Admin;

use App\Mail\BookingCancelled;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BookingIndex extends Component
{
    use WithPagination;

    #[On('confirmBooking')]
    public function confirmBooking($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'pending' && $booking->payment_status === 'paid') {
            $booking->status = 'confirmed';
            $booking->save();

            Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));

            session()->flash('success', "Prenotazione #{$booking->id} confermata.");
        }
    }

    #[On('cancelBooking')]
    public function cancelBooking($bookingId, $useStripe = false)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        $refundInfo = $booking->calculateExpectedRefund();
        $refundAmount = $refundInfo['refund_amount'] ?? 0;
        $penaltyPercent = $refundInfo['penalty_percent'] ?? 0;

        $booking->status = 'cancelled';

        if ($booking->payment_status === 'fully_paid' && $penaltyPercent >= 100) {

            $booking->payment_status = 'penalty_paid';
            $booking->save();

            Mail::to($booking->customer_email)->send(new BookingCancelled($booking));

            session()->flash('success', "Prenotazione #{$booking->id} annullata. Importo interamente trattenuto come penale.");
        } else {

            if ($refundAmount > 0) {
                if ($useStripe && $booking->stripe_payment_id) {
                    try {
                        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                        \Stripe\Refund::create([
                            'charge' => $booking->stripe_payment_id,
                            'amount' => (int)($refundAmount * 100),
                        ]);
                        $booking->payment_status = 'refunded_stripe';
                    } catch (\Exception $e) {
                        session()->flash('error', "Errore nel rimborso automatico Stripe: " . $e->getMessage());
                        return;
                    }
                } else {
                    $booking->payment_status = 'refunded_manual';
                }
            } else {
                $booking->payment_status = 'penalty_pending';
            }

            $booking->refund_requested_at = now();
            $booking->refund_confirmed_at = now();
            $booking->save();

            Mail::to($booking->customer_email)->send(new BookingCancelled($booking));

            $flashMessage = $refundAmount > 0
                ? "Prenotazione #{$booking->id} annullata. Rimborso di " . number_format($refundAmount, 2, ',', '.') . "€ registrato a sistema."
                : "Prenotazione #{$booking->id} annullata.";

            session()->flash('cancelled', $flashMessage);
        }
    }

    #[On('markAsPaid')]
    public function markAsPaid($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'cancelled') {
            $booking->payment_status = 'penalty_paid';
            $booking->save();

            session()->flash('success', "Penale residua registrata con successo per la prenotazione #{$booking->id}. Pratica completata.");
        } else {
            $booking->payment_status = 'fully_paid';
            $booking->save();

            session()->flash('success', "Saldo registrato per #{$booking->id}. Pratica completata.");
        }
    }

    public function getStatsProperty()
    {
        return [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancellation_pending' => Booking::where('status', 'cancellation_pending')->count(),
            'penalty_pending' => Booking::where('payment_status', 'penalty_pending')->count(),
            'penalty_verification' => Booking::where('payment_status', 'penalty_verification')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'earnings' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];
    }

    public function openBookingDetails($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('app:cleanup-unpaid-bookings');
        } catch (\Exception $e) {
        }

        $booking = Booking::with('camper')->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'             => $booking->id,
            'ulid'           => $booking->ulid,
            'created_at'     => $booking->created_at->timezone('Europe/Rome')->format('d/m/Y \a\l\l\e H:i'),
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
                ? (
                    (($booking->calculateExpectedRefund()['penalty_amount'] + $booking->calculateExpectedRefund()['refund_amount']) >= $booking->total_price)
                    ? number_format($booking->total_price - $booking->deposit_amount, 2, ',', '.') . '€'
                    : ($booking->calculateExpectedRefund()['penalty_amount'] > $booking->deposit_amount && $booking->payment_status !== 'penalty_paid'
                        ? number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') . '€'
                        : '0,00€')
                )
                : ($booking->payment_status === 'fully_paid' ? '0,00€' : number_format($booking->balance_amount, 2, ',', '.') . '€'),
            'originalBalance' => number_format($booking->total_price - $booking->deposit_amount, 2, ',', '.') . '€',
            'refund'         => number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '.') . '€',
            'refundRaw'      => (float)$booking->calculateExpectedRefund()['refund_amount'],
            'penalty'        => number_format($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0, 2, ',', '.') . '€',
            'penaltyRaw'     => (float)($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0),
            'status'         => $booking->status,
            'payment_status' => $booking->payment_status,
            'penalty_receipt' => $booking->penalty_receipt_path ? asset('storage/' . $booking->penalty_receipt_path) : null,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.booking-index', [
            'bookings' => Booking::with('camper')
                ->latest()
                ->paginate(10)
        ]);
    }
}
