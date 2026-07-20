<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Camper $camper)
    {
        if (!$camper->is_active) {
            session()->flash('swal-error', 'Questo camper non è al momento disponibile per la prenotazione.');
            return redirect()->route('index');
        }

        return view('booking.show', ['camper' => $camper]);
    }
}
