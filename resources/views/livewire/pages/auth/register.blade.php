<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['string', 'min:8', 'max:20'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest">
            Registrati
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Inizia il tuo viaggio!
        </p>
    </div>

    <form wire:submit="register">

        {{-- First Name and Last Name --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('Nome')" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" required
                    autocomplete="given-name" autofocus />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Cognome')" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" required
                    autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4" x-data="{
            iti: null,
            init() {
                this.$nextTick(() => {
                    if (typeof window.intlTelInput !== 'function') return;
        
                    this.iti = window.intlTelInput($refs.phoneInput, {
                        initialCountry: 'it',
                        separateDialCode: true,
                        countrySearch: true,
                        i18n: window.itiI18nIt,
                    });
        
                    if (window.itiUtils) {
                        this.iti.utils = window.itiUtils;
                    }
        
                    const syncPhone = () => {
                        $refs.phoneInput.value = $refs.phoneInput.value.replace(/\D/g, '');
        
                        const dialCodeEl = this.$el.querySelector('.iti__selected-dial-code');
                        const dialCode = dialCodeEl ? dialCodeEl.innerText.trim() : '';
        
                        const rawNumber = $refs.phoneInput.value;
        
                        if (rawNumber === '') {
                            this.$wire.set('phone', '', false);
                            return;
                        }
        
                        const fullNumber = dialCode + rawNumber;
                        this.$wire.set('phone', fullNumber, false);
                    };
        
                    $refs.phoneInput.addEventListener('countrychange', syncPhone);
                    $refs.phoneInput.addEventListener('input', syncPhone);
                });
            }
        }">
            <x-input-label for="phone" :value="__('Telefono')" />

            <div wire:ignore class="mt-1">
                <x-text-input x-ref="phoneInput" type="tel" id="phone" name="phone" autocomplete="tel"
                    class="block w-full" />
            </div>

            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Conferma Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Sei già registrato?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrati') }}
            </x-primary-button>
        </div>
    </form>
</div>
