<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PenaltyController extends Controller
{
    public function uploadPenaltyReceipt(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'receipt' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'type' => 'required|in:refund,penalty'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if (!auth()->user()->is_admin && $booking->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Azione non autorizzata.'], 403);
        }

        if ($request->hasFile('receipt')) {
            $folder = ($request->type === 'refund') ? 'refund_receipts' : 'penalty_receipts';
            $path = $request->file('receipt')->store($folder, 'public');

            if ($request->type === 'refund') {
                $booking->refund_receipt_path = $path;
            } else {
                $booking->penalty_receipt_path = $path;
            }

            $booking->save();

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

    public function success(Request $request,Booking $booking)
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
