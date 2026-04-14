<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequest;
use App\Models\Camper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function welcome()
    {
        $campers = Camper::all();

        return view('welcome', compact('campers'));
    }

    public function contacts()
    {
        return view('contacts');
    }
}
