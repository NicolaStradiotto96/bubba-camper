<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\Log;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    #[Title('Verifica Email')]
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Log::create([
            'type' => 'verification_email_resent',
            'message' => "Inviata una nuova email di verifica a: {$user->email}.",
            'context' => [
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'email' => $user->email,
            ],
        ]);

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $user = Auth::user();

        if ($user) {
            Log::create([
                'type' => 'user_logout',
                'message' => "L'utente {$user->email} ha effettuato il logout dalla schermata di verifica.",
                'context' => [
                    'user_id' => $user->id,
                    'ip_address' => request()->ip(),
                    'email' => $user->email,
                ],
            ]);
        }

        $logout();

        $this->redirect('/');
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col items-center justify-center gap-3">
        <x-primary-button wire:click="sendVerification">
            {{ __('Resend Verification Email') }}
        </x-primary-button>

        <button wire:click="logout" type="submit"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition">
            {{ __('Log Out') }}
        </button>
    </div>
</div>
