<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function view($bookingId, $filename)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Accesso non autorizzato.');
        }

        $safeFilename = basename($filename);

        $path = "documents/{$bookingId}/{$safeFilename}";

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Documento non trovato.');
        }

        return Storage::disk('local')->response($path);
    }
}
