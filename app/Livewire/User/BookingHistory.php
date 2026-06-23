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

    protected $listeners = ['refresh-page' => '$refresh'];

    public $receiptUpload;

    public function checkBookingAccess($id)
    {
        $exists = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->exists();

        return $exists;
    }

    public function openBookingDetails($bookingId)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('app:cleanup-unpaid-bookings');
        } catch (\Exception $e) {
        }

        $booking = Booking::with('camper')->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'               => $booking->id,
            'created_at'       => $booking->created_at->timezone('Europe/Rome')->format('d/m/Y \a\l\l\e H:i'),
            'name'             => "{$booking->customer_first_name} {$booking->customer_last_name}",
            'email'            => $booking->customer_email,
            'phone'            => $booking->customer_phone,
            'camper'           => $booking->camper->name,
            'start'            => $booking->start_date->format('d/m/Y'),
            'end'              => $booking->end_date->format('d/m/Y'),
            'total'            => number_format($booking->total_price, 2, ',', '.') . '€',
            'deposit'          => number_format($booking->down_payment, 2, ',', '.') . '€',
            'down_payment'     => (float)($booking->down_payment ?? 0),
            'down_paid'        => (bool)$booking->down_paid,
            'balance_paid'     => (bool)$booking->balance_paid,
            'balance'          => number_format($booking->balance_payment, 2, ',', '.') . '€',
            'remainingPenalty' => number_format($booking->calculateExpectedRefund()['remaining_penalty'], 2, ',', '.') . '€', // AGGIUNTO
            'originalBalance'  => number_format($booking->total_price - $booking->down_payment, 2, ',', '.') . '€',
            'refund'           => number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '.') . '€',
            'refundRaw'        => (float)$booking->calculateExpectedRefund()['refund_amount'],
            'penalty'          => number_format($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0, 2, ',', '.') . '€',
            'penaltyRaw'       => (float)($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0),
            'status'           => $booking->status,
            'documents_status' => $booking->documents_status,
            'payment_status'   => $booking->payment_status,
            'penalty_receipt'  => $booking->penalty_receipt_path ? asset('storage/' . $booking->penalty_receipt_path) : null,
            'refund_receipt'  => $booking->refund_receipt_path ? asset('storage/' . $booking->refund_receipt_path) : null,
        ]);
    }

    #[On('requestCancellation')]
    public function requestCancellation($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status === 'pending' || $booking->status === 'confirmed') {

            $booking->status = 'cancellation_pending';
            $booking->cancellation_requested_at = now();
            $booking->save();

            //  Mail::to('info@bubbacamper.com')->send(new CancellationRequest($dataForEmail));

            session()->flash('success', "La richiesta di annullamento per la prenotazione #{$booking->id} è in fase di elaborazione.");
        }
    }

    #[On('processPenaltyPayment')]
    public function processPenaltyPayment($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status !== 'cancelled' || $booking->payment_status !== 'penalty_pending') {
            return;
        }

        $refundInfo = $booking->calculateExpectedRefund();
        $totalPenalty = $refundInfo['penalty_amount'] ?? 0;
        $depositPaid = $booking->down_payment ?? 0;

        $penaltyToPay = $totalPenalty - $depositPaid;

        if ($penaltyToPay <= 0) {
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
                        'name' => "Pagamento Penale di Annullamento - Prenotazione #{$booking->id}",
                        'description' => "Penale di " . number_format($totalPenalty, 2, ',', '.') . "€ meno acconto già versato di " . number_format($depositPaid, 2, ',', '.') . "€.",
                    ],
                    'unit_amount' => (int)($penaltyToPay * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('penalty.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('penalty.cancel', $booking),
        ]);

        return redirect($session->url);
    }

    #[On('processPenaltyBankTransfer')]
    public function processPenaltyBankTransfer($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status !== 'cancelled' || $booking->payment_status !== 'penalty_pending') {
            return;
        }

        $sessionKey = 'uploaded_penalty_receipt_' . $booking->id;
        $path = session($sessionKey);

        if ($path) {
            $booking->penalty_receipt_path = $path;

            $booking->payment_status = 'penalty_verification';
            $booking->save();

            session()->forget($sessionKey);

            session()->flash('success', "La contabile è stata inoltrata con successo. L'amministratore verificherà l'accredito del bonifico.");

            return $this->redirect(route('dashboard'), navigate: true);
        }
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
