<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

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

    public function success(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Azione non autorizzata.');
        }

        return redirect()->route('dashboard')
            ->with('success', "Penale corrisposta con successo. La prenotazione #{$booking->id} è stata annullata ufficialmente.");
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
