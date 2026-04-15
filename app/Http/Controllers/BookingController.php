<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Camper $camper)
    {
        return view('booking.show', ['camper' => $camper]);
    }
}
