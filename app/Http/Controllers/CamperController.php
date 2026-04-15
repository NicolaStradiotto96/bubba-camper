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
        return view('prices');
    }

    public function show(Camper $camper)
    {
        return view('show', compact('camper'));
    }
}
