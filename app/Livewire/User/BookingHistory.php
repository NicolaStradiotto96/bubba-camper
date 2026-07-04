<?php

namespace App\Livewire\User;

use App\Mail\BookingCancellationNotification;
use App\Mail\BookingCancellationRequest;
use App\Mail\PenaltyRecieptNotification;
use App\Mail\PenaltyRecieptRecieved;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
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
        $booking = Booking::where('id', $id)
            ->where(function ($q) {
                if (!auth()->user()->isAdmin()) $q->where('user_id', auth()->id());
            })->first();

        if (!$booking) return ['authorized' => false];

        $hasMissingDocs = !$booking->driver_license_front_path ||
            !$booking->driver_license_back_path ||
            !$booking->id_card_front_path ||
            !$booking->id_card_back_path;

        return [
            'authorized' => true,
            'needsDocs' => ($booking->payment_status === 'paid' && $hasMissingDocs)
        ];
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
            'created_at'       => $booking->created_at->format('d/m/Y \a\l\l\e H:i'),
            'name'             => "{$booking->customer_first_name} {$booking->customer_last_name}",
            'email'            => $booking->customer_email,
            'phone'            => $booking->customer_phone,
            'camper'           => $booking->camper->name,
            'start'            => $booking->start_date->format('d/m/Y'),
            'end'              => $booking->end_date->format('d/m/Y'),
            'total'            => number_format($booking->total_price, 2, ',', '') . '€',
            'deposit'          => number_format($booking->down_payment, 2, ',', '') . '€',
            'down_payment'     => (float)($booking->down_payment ?? 0),
            'down_paid'        => (bool)$booking->down_paid,
            'balance_paid'     => (bool)$booking->balance_paid,
            'balance'          => number_format($booking->balance_payment, 2, ',', '') . '€',
            'remainingPenalty' => number_format($booking->calculateExpectedRefund()['remaining_penalty'], 2, ',', '') . '€', // AGGIUNTO
            'originalBalance'  => number_format($booking->total_price - $booking->down_payment, 2, ',', '') . '€',
            'refund'           => number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '') . '€',
            'refundRaw'        => (float)$booking->calculateExpectedRefund()['refund_amount'],
            'penalty'          => number_format($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0, 2, ',', '') . '€',
            'penaltyRaw'       => (float)($booking->status === 'cancelled' ? $booking->calculateExpectedRefund()['penalty_amount'] : 0),
            'damages'          => $booking->damages->toArray(),
            'status'           => $booking->status,
            'documents_status' => $booking->documents_status,
            'payment_status'   => $booking->payment_status,
            'penalty_receipt'  => $booking->penalty_receipt_path ? asset('storage/' . $booking->penalty_receipt_path) : null,
            'refund_receipt'   => $booking->refund_receipt_path ? asset('storage/' . $booking->refund_receipt_path) : null,
        ]);
    }

    #[On('requestCancellation')]
    public function requestCancellation($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status === 'pending' || $booking->status === 'confirmed') {

            $booking->status = 'cancellation_pending';
            $booking->documents_status = 'not_required';
            $booking->cancellation_requested_at = now();
            $booking->save();

            try {
                Mail::to($booking->customer_email)->send(new BookingCancellationRequest($booking));
                Mail::to(config('app.admin_email'))->send(new BookingCancellationNotification($booking));

                session()->flash('success', "La richiesta di annullamento per la prenotazione #{$booking->id} è in fase di elaborazione.");
            } catch (\Exception $e) {
                session()->flash('error', "La richiesta è stata registrata, ma c'è stato un problema nell'invio delle notifiche. Ti preghiamo di contattarci.");
            }
        }
    }

    #[On('processPenaltyPayment')]
    public function processPenaltyPayment($bookingId, $type = 'penalty')
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($type === 'damages') {
            $amountToPay = ($booking->damages ?? collect())->where('status', 'pending')->sum('amount');
            $description = "Pagamento Danni - Prenotazione #{$booking->id}";
        } else {
            $amountToPay = ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0);
            $description = "Pagamento Penale - Prenotazione #{$booking->id}";
        }

        if ($amountToPay <= 0) {
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => (string) $booking->id,
                'payment_type' => $type,
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $description],
                    'unit_amount' => (int)($amountToPay * 100),
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

        if ($booking->payment_status !== 'penalty_pending') {
            return;
        }

        $sessionKey = 'uploaded_penalty_receipt_' . $booking->id;
        $path = session($sessionKey);

        if ($booking->penalty_receipt_path) {
            $booking->payment_status = 'penalty_verification';
            $booking->save();

            Mail::to($booking->customer_email)->send(new PenaltyRecieptRecieved($booking));
            Mail::to(config('app.admin_email'))->send(new PenaltyRecieptNotification($booking));

            session()->forget($sessionKey);

            session()->flash('success', "La contabile è stata inoltrata con successo. L'amministratore verificherà l'accredito del bonifico.");

            return $this->redirect(route('dashboard'), navigate: true);
        } else {
            session()->flash('cancelled', "Errore: contabile non trovata nel sistema.");
        }
    }

    public function render()
    {
        $bookings = auth()->user()->bookings()
            ->with('camper', 'damages')
            ->latest()
            ->paginate(10);

        return view('livewire.user.booking-history', [
            'bookings' => $bookings
        ]);
    }
}
