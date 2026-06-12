<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class CamperController extends Controller
{
    public function index()
    {
        $campers = Camper::all();

        return view('index', compact('campers'));
    }

    public function prices()
    {
        $campers = Camper::all();

        $minLow = $campers->pluck('prices.low')
            ->filter(fn($price) => is_numeric($price) && $price > 0)
            ->min() ?? 0;

        $minMid = $campers->pluck('prices.mid')
            ->filter(fn($price) => is_numeric($price) && $price > 0)
            ->min() ?? 0;

        $minHigh = $campers->pluck('prices.high')
            ->filter(fn($price) => is_numeric($price) && $price > 0)
            ->min() ?? 0;

        return view('prices', [
            'campers' => $campers,
            'minLow'  => $minLow,
            'minMid'  => $minMid,
            'minHigh' => $minHigh,
        ]);
    }

    public function show(Camper $camper)
    {
        return view('show', compact('camper'));
    }
}
