<?php

namespace App\Livewire\Actions;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $user = Auth::user();

        if ($user) {
            Log::create([
                'type' => 'logout',
                'message' => "Logout effettuato: {$user->name}",
                'context' => [
                    'user_id'    => $user->id,
                    'ip_address' => request()->ip(),
                ],
            ]);
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
