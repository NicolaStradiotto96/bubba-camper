<?php

namespace App\Http\Controllers;

use App\Models\Camper;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // WELCOME
    public function welcome()
    {
        $campers = Camper::where('is_active', true)
            ->latest()
            ->get();

        return view('welcome', compact('campers'));
    }

    // CONTACTS
    public function contacts()
    {
        return view('contacts');
    }
}
