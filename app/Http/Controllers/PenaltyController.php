<?php

namespace App\Http\Controllers;

use App\Mail\ReceiptRejected;
use App\Models\Booking;
use App\Models\Damage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PenaltyController extends Controller
{
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
                $damage = Damage::findOrFail($request->damage_id);
                $path = $request->file('receipt')->store('damage_receipts', 'public');
                $damage->update(['receipt_path' => $path]);
            } else {
                $folder = ($request->type === 'refund') ? 'refund_receipts' : 'penalty_receipts';
                $path = $request->file('receipt')->store($folder, 'public');

                if ($request->type === 'refund') {
                    $booking->refund_receipt_path = $path;
                } else {
                    $booking->penalty_receipt_path = $path;
                }

                $booking->save();
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File non ricevuto.']);
    }

    public function getPenaltyAmount($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return response()->json([
            'amount' => max(0, ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0)),
            'status' => $booking->payment_status
        ]);
    }

    public function rejectReceipt(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type'       => 'required|in:penalty,damages',
            'damage_id'  => 'required_if:type,damages|exists:damages,id',
            'reason'     => 'required|string|max:500'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($request->type === 'damages') {
            $model = Damage::findOrFail($request->damage_id);
            $model->update(['status' => 'pending', 'receipt_path' => null]);
            $subject = 'Danno';
        } else {
            $booking->update(['payment_status' => 'penalty_pending', 'penalty_receipt_path' => null]);
            $model = $booking;
            $subject = 'Penale';
        }

        Mail::to($booking->customer_email)->send(new ReceiptRejected($booking, $request->reason, $subject));

        return response()->json(['success' => true]);
    }

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
                $message = "Pagamento danni effettuato con successo per la prenotazione #{$booking->id}.";
            } else {
                $message = "Penale corrisposta con successo. La prenotazione #{$booking->id} è stata annullata ufficialmente.";
            }
        }

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        return redirect()->route('dashboard')
            ->with('cancelled', "Il pagamento della penale per la prenotazione #{$booking->id} è stato annullato. La pratica rimane in sospeso.");
    }
}
