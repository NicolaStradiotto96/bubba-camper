<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function view($bookingId, $filename)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $path = "documents/{$bookingId}/{$filename}";

        if (!Storage::disk('local')->exists($path) || str_contains($filename, '..')) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }
}
