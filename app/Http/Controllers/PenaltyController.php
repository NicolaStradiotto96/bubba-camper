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
            'receipt' => 'required|file|mimes:pdf,png,jpg,jpeg|max:8192',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Azione non autorizzata.'], 403);
        }

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('penalty_receipts', 'public');

            session(['uploaded_penalty_receipt_' . $booking->id => $path]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File non ricevuto.']);
    }
}
