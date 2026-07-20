<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            Log::create([
                'type'       => 'email_verified',
                'message'    => "L'utente {$request->user()->email} ha verificato il proprio indirizzo email.",
                'context'    => [
                    'user_id'    => $request->user()->id,
                    'ip_address' => request()->ip(),
                    'email'      => $request->user()->email,
                ],
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
