<?php

namespace App\Livewire\User;

use App\Mail\BookingCancellationNotification;
use App\Mail\BookingCancellationRequest;
use App\Mail\PenaltyRecieptNotification;
use App\Mail\PenaltyRecieptRecieved;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\Log;
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
    public $searchId = '';

    // CHECK BOOKING ACCESS
    public function checkBookingAccess($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())->first();

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

    // OPEN DETAILS MODAL
    public function openBookingDetails($bookingId)
    {
        $booking = Booking::with('camper')->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'                 => $booking->id,
            'created_at'         => $booking->created_at->format('d/m/Y \a\l\l\e H:i'),
            'name'               => "{$booking->customer_first_name} {$booking->customer_last_name}",
            'email'              => $booking->customer_email,
            'phone'              => $booking->customer_phone,
            'camper'             => $booking->camper->name,
            'start'              => $booking->start_date->format('d/m/Y'),
            'end'                => $booking->end_date->format('d/m/Y'),
            'total'              => number_format($booking->total_price, 2, ',', '') . '€',
            'deposit'            => number_format($booking->down_payment, 2, ',', '') . '€',
            'down_payment'       => (float)($booking->down_payment ?? 0),
            'down_paid'          => (bool)$booking->down_paid,
            'balance_paid'       => (bool)$booking->balance_paid,
            'balance'            => number_format($booking->balance_payment, 2, ',', '') . '€',
            'remainingPenalty'   => number_format($booking->remaining_penalty, 2, ',', '') . '€',
            'originalBalance'    => number_format($booking->total_price - $booking->down_payment, 2, ',', '') . '€',
            'refund'             => number_format($booking->refund_amount, 2, ',', '') . '€',
            'refundRaw'          => (float)$booking->refund_amount,
            'penalty'            => number_format($booking->status === 'cancelled' ? $booking->penalty_amount : 0, 2, ',', '') . '€',
            'penaltyRaw'         => (float)($booking->status === 'cancelled' ? $booking->penalty_amount : 0),
            'damages'            => $booking->damages->map(function ($d) {
                return [
                    'id'          => $d->id,
                    'amount'      => $d->amount,
                    'status'      => $d->status,
                    'receipt_url' => $d->receipt_path ? asset('storage/' . $d->receipt_path) : null,
                ];
            })->toArray(),
            'status'             => $booking->status,
            'documents_status'   => $booking->documents_status,
            'payment_status'     => $booking->payment_status,
            'penalty_receipt'    => $booking->penalty_receipt_path ? asset('storage/' . $booking->penalty_receipt_path) : null,
            'refund_receipt'     => $booking->refund_receipt_path ? asset('storage/' . $booking->refund_receipt_path) : null,
        ]);
    }

    // REQUEST BOOKING CANCELLATION
    #[On('requestCancellation')]
    public function requestCancellation($bookingId)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($booking->status === 'pending' || $booking->status === 'confirmed') {

            $booking->status = 'cancellation_pending';
            $booking->documents_status = 'not_required';
            $booking->cancellation_requested_at = now();
            $booking->save();

            $this->logPenaltyEvent(
                'cancellation_requested',
                "Richiesta di annullamento inoltrata dal cliente per la prenotazione #{$booking->id}.",
                $booking
            );

            try {
                Mail::to($booking->customer_email)->send(new BookingCancellationRequest($booking));
                Mail::to(config('app.admin_email'))->send(new BookingCancellationNotification($booking));

                $this->dispatch('swal-success', "La richiesta di annullamento per la prenotazione <span class='id'>#{$booking->id}</span> è stata inoltrata con successo!");
            } catch (\Exception $e) {
                $this->dispatch('swal-error', "La richiesta è stata registrata, ma c'è stato un problema nell'invio delle notifiche. Ti preghiamo di contattarci.");
            }
        }
    }

    // PAY PENALTY / DAMAGE STRIPE
    #[On('processPenaltyPayment')]
    public function processPenaltyPayment($bookingId, $type = 'penalty', $damageId = null)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($type === 'damages') {
            $damage = Damage::where('id', $damageId)
                ->where('booking_id', $booking->id)
                ->firstOrFail();
            $amountToPay = $damage->amount;
            $description = "Pagamento Danno #{$damage->id} - Prenotazione #{$booking->id}";

            $this->logPenaltyEvent(
                'damage_stripe_checkout_initiated',
                "Avviata sessione di pagamento Stripe per il danno #{$damage->id}.",
                $booking,
                ['damage_id' => $damage->id, 'amount' => $amountToPay]
            );
        } else {
            $amountToPay = $booking->remaining_penalty;
            $description = "Pagamento Penale - Prenotazione #{$booking->id}";

            $this->logPenaltyEvent(
                'penalty_stripe_checkout_initiated',
                "Avviata sessione di pagamento Stripe per la penale di annullamento.",
                $booking,
                ['amount' => $amountToPay]
            );
        }

        if ($amountToPay <= 0) {
            return;
        }

        $cancelUrl = route('penalty.cancel', $booking) . '?type=' . $type;
        if ($type === 'damages' && $damageId) {
            $cancelUrl .= '&damage_id=' . $damageId;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => (string) $booking->id,
                'payment_type' => $type,
                'damage_id' => (string) $damageId,
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
            'cancel_url' => $cancelUrl,
        ]);

        return redirect($session->url);
    }

    // PAY PENALTY / DAMAGE MANUAL
    #[On('processPenaltyBankTransfer')]
    public function processPenaltyBankTransfer($bookingId, $type = 'penalty', $damageId = null)
    {
        $booking = auth()->user()->bookings()->findOrFail($bookingId);

        if ($type === 'damages') {
            $damage = Damage::where('id', $damageId)
                ->where('booking_id', $booking->id)
                ->firstOrFail();
            $damage->update(['status' => 'verification']);

            $this->logPenaltyEvent(
                'damage_bank_transfer_submitted',
                "Inoltrata contabile di bonifico per la verifica del danno #{$damage->id}.",
                $booking,
                ['damage_id' => $damage->id, 'amount' => $damage->amount]
            );

            Mail::to($booking->customer_email)->send(new PenaltyRecieptRecieved($booking));
            Mail::to(config('app.admin_email'))->send(new PenaltyRecieptNotification($booking, $damage->amount, 'Danno'));

            $this->dispatch('swal-success', "La contabile per il danno <span class='damage-id'>#{$damage->id}</span> è stata inoltrata.");
            return;
        } else {
            if ($booking->payment_status !== 'penalty_pending' || !$booking->penalty_receipt_path) {
                $this->dispatch('swal-error', "Errore: contabile non trovata.");
                return;
            }

            $booking->update(['payment_status' => 'penalty_verification']);

            $amount = $booking->remaining_penalty;

            $this->logPenaltyEvent(
                'penalty_bank_transfer_submitted',
                "Inoltrata contabile di bonifico per la verifica della penale di annullamento.",
                $booking,
                ['amount' => $amount]
            );

            Mail::to($booking->customer_email)->send(new PenaltyRecieptRecieved($booking));
            Mail::to(config('app.admin_email'))->send(new PenaltyRecieptNotification($booking, $amount, 'Penale'));

            $this->dispatch('swal-success', "La contabile per la penale della prenotazione <span class='id'>#{$booking->id}</span> è stata inoltrata con successo!");
        }
    }

    // RESET PAGE
    public function updatingSearchId()
    {
        $this->resetPage();
    }

    // RENDER
    public function render()
    {
        $query = auth()->user()->bookings()
            ->with('camper', 'damages')
            ->latest();

        $cleanSearch = trim(str_replace('#', '', $this->searchId));

        if (!empty($cleanSearch)) {
            $query->where('id', $cleanSearch);
        }

        return view('livewire.user.booking-history', [
            'bookings' => $query->paginate(10)
        ]);
    }

    // LOG
    private function logPenaltyEvent(string $type, string $message, Booking $booking, array $extraContext = [])
    {
        Log::create([
            'type'       => $type,
            'message'    => $message,
            'context'    => array_merge([
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'booking_id' => $booking->id,
                'camper_id'  => $booking->camper_id,
            ], $extraContext),
        ]);
    }
}
