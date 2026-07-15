<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class CamperController extends Controller
{
    // INDEX
    public function index()
    {
        return view('index', ['campers' => Camper::paginate(9)]);
    }

    // PRICES
    public function prices()
    {
        $campers = Camper::all();

        $minLow  = $campers->where('prices.low', '>', 0)->min('prices.low') ?? 0;
        $minMid  = $campers->where('prices.mid', '>', 0)->min('prices.mid') ?? 0;
        $minHigh = $campers->where('prices.high', '>', 0)->min('prices.high') ?? 0;

        return view('prices', compact('campers', 'minLow', 'minMid', 'minHigh'));
    }

    // SHOW
    public function show(Camper $camper)
    {
        return view('show', compact('camper'));
    }
}
