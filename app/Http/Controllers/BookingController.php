<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Camper $camper)
    {
        if (!$camper->is_active) {
            return redirect()->route('index')->with('swal-error', 'Questo camper non è al momento disponibile per la prenotazione.');
        }

        return view('booking.show', ['camper' => $camper]);
    }
}
