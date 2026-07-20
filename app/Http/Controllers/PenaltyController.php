<?php

namespace App\Http\Controllers;

use App\Mail\ReceiptRejected;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PenaltyController extends Controller
{
    // UPLOAD RECEIPT
    public function uploadPenaltyReceipt(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'damage_id'  => 'required_if:type,damages|exists:damages,id',
            'receipt' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'type' => 'required|in:refund,penalty,damages'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if (!auth()->user()->is_admin && $booking->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Azione non autorizzata.'], 403);
        }

        if ($request->hasFile('receipt')) {
            if ($request->type === 'damages') {
                $damage = Damage::where('id', $request->damage_id)
                    ->where('booking_id', $booking->id)
                    ->firstOrFail();
                $path = $request->file('receipt')->store('damage_receipts', 'public');
                $damage->update(['receipt_path' => $path]);

                $this->logPenaltyEvent(
                    'damage_receipt_uploaded',
                    "Caricata ricevuta di pagamento per il danno #{$damage->id}.",
                    $booking,
                    ['damage_id' => $damage->id, 'path' => $path]
                );
            } else {
                $folder = ($request->type === 'refund') ? 'refund_receipts' : 'penalty_receipts';
                $path = $request->file('receipt')->store($folder, 'public');

                if ($request->type === 'refund') {
                    $booking->refund_receipt_path = $path;
                    $logType = 'refund_receipt_uploaded';
                    $logMsg = "Caricata ricevuta di rimborso per la prenotazione #{$booking->id}.";
                } else {
                    $booking->penalty_receipt_path = $path;
                    $logType = 'penalty_receipt_uploaded';
                    $logMsg = "Caricata ricevuta di penale per la prenotazione #{$booking->id}.";
                }

                $booking->save();

                $this->logPenaltyEvent($logType, $logMsg, $booking, ['path' => $path]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File non ricevuto.']);
    }

    // GET AMOUNT
    public function getPenaltyAmount($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accesso non autorizzato.');
        }

        return response()->json([
            'amount' => max(0, ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0)),
            'status' => $booking->payment_status
        ]);
    }

    // REJECT RECIEPT 
    public function rejectReceipt(Request $request)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accesso non autorizzato.');
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type'       => 'required|in:penalty,damages',
            'damage_id'  => 'required_if:type,damages|exists:damages,id',
            'reason'     => 'required|string|max:500'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        $rejectedItem = [
            'type' => ($request->type === 'damages') ? 'danno' : 'penale',
            'id'   => ($request->type === 'damages') ? $request->damage_id : $booking->id,
            'label' => ($request->type === 'damages') ? 'danno' : 'penale'
        ];

        if ($request->type === 'damages') {
            $model = Damage::where('id', $request->damage_id)
                ->where('booking_id', $request->booking_id)
                ->firstOrFail();
            $model->update(['status' => 'pending', 'receipt_path' => null]);

            $this->logPenaltyEvent(
                'damage_receipt_rejected',
                "Ricevuta rifiutata per il danno #{$model->id}. Motivo: {$request->reason}",
                $booking,
                ['damage_id' => $model->id, 'reason' => $request->reason]
            );
        } else {
            $booking->update(['payment_status' => 'penalty_pending', 'penalty_receipt_path' => null]);

            $this->logPenaltyEvent(
                'penalty_receipt_rejected',
                "Ricevuta penale rifiutata per la prenotazione #{$booking->id}. Motivo: {$request->reason}",
                $booking,
                ['reason' => $request->reason]
            );
        }

        Mail::to($booking->customer_email)->send(new ReceiptRejected($booking, $request->reason, $rejectedItem));

        return response()->json(['success' => true]);
    }

    // PAY PENALTY / DAMAGE STRIPE SUCCESS
    public function success(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        $sessionId = $request->query('session_id');
        $message = "Operazione completata con successo.";

        if ($sessionId) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);
            $paymentType = $session->metadata->payment_type ?? 'penalty';

            if ($paymentType === 'damages') {
                $damageId = $session->metadata->damage_id ?? 'N/A';
                $message = "Pagamento danno <span class='damage-id'>#{$damageId}</span> effettuato con successo!";

                $this->logPenaltyEvent(
                    'damage_paid_stripe',
                    "Pagamento online completato tramite Stripe per il danno #{$damageId}.",
                    $booking,
                    ['damage_id' => $damageId, 'session_id' => $sessionId]
                );
            } else {
                $message = "Pagamento penale effettuato con successo per la prenotazione <span class='id'>#{$booking->id}</span>.";

                $this->logPenaltyEvent(
                    'penalty_paid_stripe',
                    "Pagamento online completato tramite Stripe per la penale di annullamento.",
                    $booking,
                    ['session_id' => $sessionId]
                );
            }
        }

        return redirect()->route('dashboard')->with('swal-success', $message);
    }

    // PAY PENALTY / DAMAGE STRIPE CANCELLED
    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        $paymentType = $request->query('type', 'penalty');
        $damageId = $request->query('damage_id');

        if ($paymentType === 'damages') {
            $damageText = $damageId ? "per il danno <span class='damage-id'>#{$damageId}</span>" : "del danno";
            $message = "Il pagamento {$damageText} è stato annullato. La pratica rimane in sospeso.";
        } else {
            $message = "Il pagamento della penale per la prenotazione <span class='id'>#{$booking->id}</span> è stato annullato. La pratica rimane in sospeso.";
        }

        return redirect()->route('dashboard')->with('swal-error', $message);
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
