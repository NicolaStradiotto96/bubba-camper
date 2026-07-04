<?php

namespace App\Livewire\Admin;

use App\Mail\BookingCancelled;
use App\Mail\BookingCancelledNotification;
use App\Mail\BookingCompleted;
use App\Mail\BookingCompletedNotification;
use App\Mail\BookingConfirmed;
use App\Mail\BookingConfirmedNotification;
use App\Mail\DocumentRejected;
use App\Mail\PenaltyPaid;
use App\Mail\PenaltyPaidNotification;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BookingIndex extends Component
{
    use WithPagination;

    public $bookingId;

    protected $listeners = ['refresh-page' => '$refresh'];

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

            $this->dispatch('booking-updated');

            Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));
            Mail::to(config('app.admin_email'))->send(new BookingConfirmedNotification($booking));

            session()->flash('success', "Prenotazione #{$booking->id} confermata.");
        }
    }

    #[On('cancelBooking')]
    public function cancelBooking($bookingId, $applyPenalty = true, $useStripe = false, $byAdmin = false)
    {
        if (!auth()->user()->is_admin) abort(403);

        $booking = Booking::findOrFail($bookingId);


        $totalPaid = ($booking->payment_status === 'fully_paid')
            ? $booking->total_price
            : ($booking->down_payment ?? 0);

        if ($applyPenalty) {
            $refundInfo = $booking->calculateExpectedRefund();
            $refundAmount = $refundInfo['refund_amount'] ?? 0;
            $remainingPenalty = $refundInfo['remaining_penalty'] ?? 0;
        } else {
            $refundAmount = $totalPaid;
            $remainingPenalty = 0;
        }

        $refundAmount = min($refundAmount, $totalPaid);

        $booking->status = $byAdmin ? 'cancelled_by_admin' : 'cancelled';
        $booking->documents_status = 'not_required';

        if ($refundAmount > 0) {
            if ($useStripe && $booking->stripe_payment_id) {
                try {
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    \Stripe\Refund::create([
                        'payment_intent' => $booking->stripe_payment_id,
                        'amount' => (int)($refundAmount * 100),
                    ]);
                    $booking->payment_status = 'refunded_stripe';
                    $booking->refund_paid_at = now();
                } catch (\Exception $e) {
                    session()->flash('error', "Errore Stripe: " . $e->getMessage());
                    return;
                }
            } else {
                $booking->payment_status = 'refunded_manual';
                $booking->refund_paid_at = now();
            }
        } else {
            if ($totalPaid >= $booking->calculateExpectedRefund()['penalty_amount']) {
                $booking->payment_status = 'penalty_paid';
                $booking->balance_paid = true;
                $booking->balance_paid_at = now();
                $booking->penalty_paid_at = now();
            } else {
                $booking->payment_status = 'penalty_pending';
            }
        }

        $booking->cancellation_confirmed_at = now();
        $booking->save();

        $this->dispatch('booking-updated');

        Mail::to($booking->customer_email)->send(new BookingCancelled($booking));
        Mail::to(config('app.admin_email'))->send(new BookingCancelledNotification($booking));

        $msg = $refundAmount > 0
            ? "Prenotazione annullata. Rimborso di " . number_format($refundAmount, 2, ',', '') . "€ registrato."
            : ($remainingPenalty > 0
                ? "Prenotazione annullata. Penale residua di " . number_format($remainingPenalty, 2, ',', '') . "€ in sospeso."
                : "Prenotazione annullata. Nessun rimborso dovuto.");

        session()->flash('cancelled', $msg);
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
            $booking->balance_paid = true;
            $booking->balance_paid_at = now();
            $booking->penalty_paid_at = now();
            $booking->save();

            Mail::to($booking->customer_email)->send(new PenaltyPaid($booking));
            Mail::to(config('app.admin_email'))->send(new PenaltyPaidNotification($booking));

            $booking->logs()->create([
                'type'    => 'penalty_paid',
                'message' => 'Penale di annullamento saldata dal cliente.',
                'context' => [
                    'amount' => $booking->calculateExpectedRefund()['penalty_amount']
                ]
            ]);

            session()->flash('success', "Penale residua registrata con successo per la prenotazione #{$booking->id}.");
        } else {
            $booking->payment_status = 'fully_paid';
            $booking->balance_paid = true;
            $booking->balance_paid_at = now();
            $booking->save();

            Mail::to($booking->customer_email)->send(new BookingCompleted($booking));
            Mail::to(config('app.admin_email'))->send(new BookingCompletedNotification($booking));

            $booking->logs()->create([
                'type'    => 'booking_completed',
                'message' => 'Saldo ricevuto e mail di conferma finale inviata al cliente.',
                'context' => [
                    'total_price'     => $booking->total_price,
                    'balance_paid'    => $booking->balance_payment,
                ]
            ]);

            session()->flash('success', "Saldo registrato per #{$booking->id}.");
        }
    }

    #[On('markAsInvoiced')]
    public function markAsInvoiced($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        $booking->status = 'invoiced';
        $booking->save();

        session()->flash('success', "La prenotazione #{$bookingId} è stata contrassegnata come Fatturata.");

        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function getStatsProperty()
    {
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancellation_pending' => Booking::where('status', 'cancellation_pending')->count(),
            'penalty_pending' => Booking::where('payment_status', 'penalty_pending')->count(),
            'penalty_verification' => Booking::where('payment_status', 'penalty_verification')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'earnings' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        $totalPending = $stats['pending'] + $stats['cancellation_pending'] + $stats['penalty_verification'];

        return [
            'counts' => $stats,
            'totalPending' => $totalPending,
            'style' => [
                'border' => $totalPending > 0 ? 'border-amber-500' : 'border-green-500',
                'bg'     => $totalPending > 0 ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-green-50 dark:bg-green-900/20',
                'text'   => $totalPending > 0 ? 'text-amber-500' : 'text-green-500',
            ]
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

        $this->dispatch('booking-updated');


        $booking = Booking::with('camper')->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'               => $booking->id,
            'damage_url'       => route('damage.add', $booking->id),
            'edit_url'         => route('booking.edit', $booking->id),
            'ulid'             => $booking->ulid,
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
            'dl_front' => $booking->driver_license_front_path
                ? route('admin.view-doc', [
                    'bookingId' => $booking->id,
                    'filename' => basename($booking->driver_license_front_path)
                ])
                : null,
            'dl_back' => $booking->driver_license_back_path
                ? route('admin.view-doc', [
                    'bookingId' => $booking->id,
                    'filename' => basename($booking->driver_license_back_path)
                ])
                : null,
            'id_front' => $booking->id_card_front_path
                ? route('admin.view-doc', [
                    'bookingId' => $booking->id,
                    'filename' => basename($booking->id_card_front_path)
                ])
                : null,
            'id_back' => $booking->id_card_back_path
                ? route('admin.view-doc', [
                    'bookingId' => $booking->id,
                    'filename' => basename($booking->id_card_back_path)
                ])
                : null,
        ]);
    }

    public function rejectDocuments($bookingId, array $fields)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $this->bookingId = $bookingId;

        $booking = Booking::findOrFail($this->bookingId);

        $rejectedFields = [];

        DB::transaction(function () use ($booking, $fields, &$rejectedFields) {
            foreach ($fields as $field) {
                $pathColumn = $field . '_path';

                if ($booking->$pathColumn) {
                    \Storage::disk('local')->delete($booking->$pathColumn);
                    $booking->$pathColumn = null;
                    $rejectedFields[] = $field;
                }
            }

            $booking->documents_status = 'pending';
            $booking->save();
        });

        Mail::to($booking->customer_email)->send(new DocumentRejected($booking, $rejectedFields));

        $this->dispatch('notify', message: 'Documenti cancellati e cliente avvisato.');
        $this->dispatch('refresh-page');
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
