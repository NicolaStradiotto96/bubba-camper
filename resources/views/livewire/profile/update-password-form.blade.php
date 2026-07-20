<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 uppercase">
            {{ __('Aggiorna Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Assicurati che il tuo account utilizzi una password lunga e casuale per rimanere al sicuro.') }}
        </p>
    </header>

    <form class="mt-6 space-y-2" novalidate>
        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Attuale')" />
            <x-text-input wire:model.blur="current_password" id="update_password_current_password" name="current_password"
                type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('current_password')" />
            </div>
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nuova Password')" />
            <x-text-input wire:model="password" id="update_password_password" name="password" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('password')" />
            </div>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Conferma Password')" />
            <x-text-input wire:model.blur="password_confirmation" id="update_password_password_confirmation"
                name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <div class="flex justify-center items-center gap-4">
            <x-primary-button type="button" wire:click="updatePassword" wire:loading.attr="disabled"
                wire:target="updatePassword" class="w-full justify-center disabled:opacity-50 disabled:cursor-wait">

                <span wire:loading.remove wire:target="updatePassword">
                    {{ __('Salva') }}
                </span>

                <span wire:loading wire:target="updatePassword">
                    {{ __('Salvataggio...') }}
                </span>
            </x-primary-button>

            <x-action-message class="me-3" on="password-updated" role="status" aria-live="polite">
                {{ __('Modifiche salvate.') }}
            </x-action-message>
        </div>
    </form>
</section>
