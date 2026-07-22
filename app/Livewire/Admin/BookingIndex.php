<?php

namespace App\Livewire\Admin;

use App\Mail\BookingCancelled;
use App\Mail\BookingCancelledNotification;
use App\Mail\BookingCompleted;
use App\Mail\BookingCompletedNotification;
use App\Mail\BookingConfirmed;
use App\Mail\BookingConfirmedNotification;
use App\Mail\DocumentRejected;
use App\Mail\PenaltyDamagePaid;
use App\Mail\PenaltyDamagePaidNotification;
use App\Mail\PenaltyPaid;
use App\Mail\PenaltyPaidNotification;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Refund;
use Stripe\Stripe;

class BookingIndex extends Component
{
    use WithPagination;

    public $bookingId;
    public $searchId = '';

    protected $listeners = ['refresh-page' => '$refresh'];

    // IS ADMIN?
    public function mount()
    {
        if (!auth()->user()?->is_admin) {
            abort(403);
        }
    }

    // CHECK BOOKING ACCESS
    public function checkBookingAccess($id)
    {
        $booking = Booking::find($id);

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

    // CONFIRM BOOKING
    #[On('confirmBooking')]
    public function confirmBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'pending' && $booking->payment_status === 'paid') {
            $booking->status = 'confirmed';
            $booking->save();

            $this->logAdminEvent(
                'booking_confirmed_by_admin',
                "Prenotazione #{$booking->id} confermata dall'amministratore.",
                $booking
            );

            $this->dispatch('booking-updated');

            Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));
            Mail::to(config('app.admin_email'))->send(new BookingConfirmedNotification($booking));

            $this->dispatch('swal-success', "Prenotazione <span class='id'>#{$booking->id}</span> confermata con successo!");
        }
    }

    // CANCEL BOOKING
    #[On('cancelBooking')]
    public function cancelBooking($bookingId, $applyPenalty = true, $useStripe = false, $byAdmin = false)
    {
        $booking = Booking::findOrFail($bookingId);

        $totalPaid = 0;
        if ($booking->balance_paid || $booking->payment_status === 'fully_paid') {
            $totalPaid = $booking->total_price;
        } elseif ($booking->down_paid) {
            $totalPaid = $booking->down_payment ?? 0;
        }

        $refundInfo = $booking->calculateExpectedRefund();

        if ($applyPenalty) {
            $refundAmount = $refundInfo['refund_amount'] ?? 0;
            $remainingPenalty = $refundInfo['remaining_penalty'] ?? 0;
            $penaltyAmount = $refundInfo['penalty_amount'] ?? 0;
        } else {
            $refundAmount = $totalPaid;
            $remainingPenalty = 0;
            $penaltyAmount = 0;
        }

        $refundAmount = min($refundAmount, $totalPaid);
        $canUseStripe = $useStripe && !empty($booking->stripe_payment_id);

        DB::transaction(function () use ($booking, $refundAmount, $canUseStripe, $byAdmin, $totalPaid, $penaltyAmount, $remainingPenalty) {
            $booking->status = $byAdmin ? 'cancelled_by_admin' : 'cancelled';
            $booking->documents_status = 'not_required';

            $booking->penalty_amount = $penaltyAmount;
            $booking->remaining_penalty = $remainingPenalty;
            $booking->refund_amount = $refundAmount;

            if ($refundAmount > 0 && $canUseStripe) {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    Refund::create([
                        'payment_intent' => $booking->stripe_payment_id,
                        'amount' => (int)($refundAmount * 100),
                    ]);
                    $booking->payment_status = 'refunded_stripe';
                    $booking->refund_paid_at = now();
                } catch (\Exception $e) {
                    \Log::error("ERRORE STRIPE [Booking #{$booking->id}]: " . $e->getMessage());
                    throw $e;
                }
            } elseif ($refundAmount > 0) {
                $booking->payment_status = 'refunded_manual';
                $booking->refund_paid_at = now();
            } else {
                if ($totalPaid >= $penaltyAmount) {
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
        });

        $this->logAdminEvent(
            'booking_cancelled_by_admin',
            "Prenotazione #{$booking->id} annullata dall'amministratore.",
            $booking,
            ['apply_penalty' => $applyPenalty, 'refund_amount' => $refundAmount]
        );

        $this->dispatch('booking-updated');

        Mail::to($booking->customer_email)->send(new BookingCancelled($booking));
        Mail::to(config('app.admin_email'))->send(new BookingCancelledNotification($booking));

        $msg = $refundAmount > 0
            ? "Prenotazione <span class='id'>#{$booking->id}</span> annullata. Rimborso di " . number_format($refundAmount, 2, ',', '') . "€ registrato."
            : ($remainingPenalty > 0
                ? "Prenotazione <span class='id'>#{$booking->id}</span> annullata. Penale residua di " . number_format($remainingPenalty, 2, ',', '') . "€ in sospeso."
                : "Prenotazione <span class='id'>#{$booking->id}</span> annullata. Nessun rimborso dovuto.");

        $this->dispatch('swal-success', $msg);
    }

    // COMPLETE BOOKING
    #[On('markAsPaid')]
    public function markAsPaid($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'cancelled') {
            $booking->payment_status = 'penalty_paid';
            $booking->balance_paid = true;
            $booking->balance_paid_at = now();
            $booking->penalty_paid_at = now();
            $booking->remaining_penalty = 0.00;
            $booking->save();

            $this->logAdminEvent(
                'penalty_marked_as_paid',
                "Registrato saldo penale per la prenotazione annullata #{$booking->id}.",
                $booking,
                ['amount' => $booking->penalty_amount]
            );

            Mail::to($booking->customer_email)->send(new PenaltyPaid($booking));
            Mail::to(config('app.admin_email'))->send(new PenaltyPaidNotification($booking));

            $this->dispatch('swal-success', "Penale residua registrata con successo per la prenotazione <span class='id'>#{$booking->id}</span>.");
        } else {
            $booking->payment_status = 'fully_paid';
            $booking->balance_paid = true;
            $booking->balance_paid_at = now();
            $booking->save();

            $this->logAdminEvent(
                'booking_marked_as_paid',
                "Registrato saldo completo per la prenotazione #{$booking->id}.",
                $booking,
                ['total_price' => $booking->total_price, 'balance_paid' => $booking->balance_payment,]
            );

            Mail::to($booking->customer_email)->send(new BookingCompleted($booking));
            Mail::to(config('app.admin_email'))->send(new BookingCompletedNotification($booking));

            $this->dispatch('swal-success', "Saldo registrato con successo per la prenotazione <span class='id'>#{$booking->id}</span>.");
        }
    }

    // COMPLETE DAMAGE
    #[On('confirmDamageResolution')]
    public function confirmDamageResolution($damageId)
    {
        $damage = Damage::findOrFail($damageId);

        $damage->update(['status' => 'paid']);

        $this->logAdminEvent(
            'damage_resolved_by_admin',
            "Registrato saldo/risoluzione per il danno #{$damage->id}.",
            $damage->booking,
            ['damage_id' => $damage->id, 'amount' => $damage->amount]
        );

        Mail::to($damage->booking->customer_email)->send(new PenaltyDamagePaid($damage));
        Mail::to(config('app.admin_email'))->send(new PenaltyDamagePaidNotification($damage));

        $this->dispatch('swal-success', "Saldo registrato con successo per il danno <span class='damage-id'>#{$damage->id}</span>.");
    }

    // INVOICE BOOKING
    #[On('markAsInvoiced')]
    public function markAsInvoiced($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $booking->status = 'invoiced';
        $booking->save();

        $this->logAdminEvent(
            'booking_invoiced',
            "Prenotazione #{$booking->id} contrassegnata come fatturata.",
            $booking
        );

        $this->dispatch('swal-success', "Prenotazione <span class='id'>#{$booking->id}</span> fatturata con successo!");
    }

    // OPEN DETAILS MODAL
    public function openBookingDetails($bookingId)
    {
        $booking = Booking::with(['camper', 'damages'])->findOrFail($bookingId);

        $this->dispatch('open-booking-modal', [
            'id'                   => $booking->id,
            'ulid'                 => $booking->ulid,
            'created_at'           => $booking->created_at->format('d/m/Y \a\l\l\e H:i'),
            'damage_url'           => route('damage.add', $booking->id),
            'edit_url'             => route('booking.edit', $booking->id),
            'name'                 => "{$booking->customer_first_name} {$booking->customer_last_name}",
            'email'                => $booking->customer_email,
            'phone'                => $booking->customer_phone,
            'camper'               => $booking->camper->name,
            'start'                => $booking->start_date->format('d/m/Y'),
            'end'                  => $booking->end_date->format('d/m/Y'),
            'total'                => number_format($booking->total_price, 2, ',', '') . '€',
            'deposit'              => number_format($booking->down_payment, 2, ',', '') . '€',
            'down_payment'         => (float)($booking->down_payment ?? 0),
            'down_paid'            => (bool)$booking->down_paid,
            'balance_paid'         => (bool)$booking->balance_paid,
            'balance'              => number_format($booking->balance_payment, 2, ',', '') . '€',
            'remainingPenalty'     => number_format($booking->remaining_penalty, 2, ',', '') . '€',
            'originalBalance'      => number_format($booking->total_price - $booking->down_payment, 2, ',', '') . '€',
            'refund'               => number_format($booking->refund_amount, 2, ',', '') . '€',
            'refundRaw'            => (float)$booking->refund_amount,
            'penalty'              => number_format($booking->status === 'cancelled' ? $booking->penalty_amount : 0, 2, ',', '') . '€',
            'penaltyRaw'           => (float)($booking->status === 'cancelled' ? $booking->penalty_amount : 0),
            'damages' => $booking->damages->map(function ($d) {
                return [
                    'id'           => $d->id,
                    'amount'       => $d->amount,
                    'status'       => $d->status,
                    'receipt_url'  => $d->receipt_path ? asset('storage/' . $d->receipt_path) : null,
                ];
            })->toArray(),
            'status'               => $booking->status,
            'documents_status'     => $booking->documents_status,
            'payment_status'       => $booking->payment_status,
            'penalty_receipt'      => $booking->penalty_receipt_path ? asset('storage/' . $booking->penalty_receipt_path) : null,
            'refund_receipt'       => $booking->refund_receipt_path ? asset('storage/' . $booking->refund_receipt_path) : null,
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

    // REJECT DOCUMENTS
    public function rejectDocuments($bookingId, array $fields)
    {
        $booking = Booking::findOrFail($bookingId);

        $rejectedFields = [];

        DB::transaction(function () use ($booking, $fields, &$rejectedFields) {
            foreach ($fields as $field) {
                $pathColumn = $field . '_path';

                if ($booking->$pathColumn) {
                    Storage::delete($booking->$pathColumn);
                    $booking->$pathColumn = null;
                    $rejectedFields[] = $field;
                }
            }

            $booking->documents_status = 'pending';
            $booking->save();
        });

        $this->logAdminEvent(
            'documents_rejected_by_admin',
            "Documenti rifiutati dall'amministratore per la prenotazione #{$booking->id}.",
            $booking,
            ['rejected_fields' => $rejectedFields]
        );

        Mail::to($booking->customer_email)->send(new DocumentRejected($booking, $rejectedFields));

        $this->dispatch('swal-success', "Documenti cancellati e cliente avvisato con successo!");
    }

    // RESET PAGE
    public function updatingSearchId()
    {
        $this->resetPage();
    }

    // RENDER
    public function render()
    {
        $cleanSearch = trim(str_replace('#', '', $this->searchId));

        $bookings = Booking::with('camper')
            ->when(!empty($cleanSearch), fn($q) => $q->where('id', $cleanSearch))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.booking-index', [
            'bookings' => $bookings
        ]);
    }

    // LOG
    private function logAdminEvent(string $type, string $message, Booking $booking, array $extraContext = [])
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
