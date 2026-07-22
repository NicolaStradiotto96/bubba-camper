<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\Log;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    #[Title('Password Dimenticata')]
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            Log::create([
                'type' => 'password_reset_request_failed',
                'message' => "Tentativo fallito di reset password per l'email: {$this->email}.",
                'context' => [
                    'ip_address' => request()->ip(),
                    'email' => $this->email,
                    'status' => __($status),
                ],
            ]);

            $this->addError('email', __($status));

            return;
        }
        Log::create([
            'type' => 'password_reset_requested',
            'message' => "Inviato link di reset password all'email: {$this->email}.",
            'context' => [
                'ip_address' => request()->ip(),
                'email' => $this->email,
            ],
        ]);

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('Hai dimenticato la password? Nessun problema.') }}
        <br>
        {{ __('Inserisci il tuo indirizzo email e ti invieremo un link per sceglierne una nuova.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" novalidate>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autofocus />
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex items-center justify-center mt-2">
            <x-primary-button>
                {{ __('Invia link via email') }}
            </x-primary-button>
        </div>
    </form>
</div>
