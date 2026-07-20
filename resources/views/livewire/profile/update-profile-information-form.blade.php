<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->first_name = Auth::user()->first_name;
        $this->last_name = Auth::user()->last_name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone ?? '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::user()->id)],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ]);
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', first_name: $user->first_name, last_name: $user->last_name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 uppercase">
            {{ __('Informazioni Profilo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Aggiorna le informazioni del tuo profilo e il tuo indirizzo email.') }}
        </p>
    </header>

    <form class="mt-6 space-y-2" novalidate>

        {{-- First Name and Last Name --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('Nome')" />
                <x-text-input wire:model.blur="first_name" id="first_name" name="first_name" type="text"
                    class="mt-1 block w-full" required autocomplete="given-name" />
                <div class="min-h-5 text-center mt-1">
                    <x-input-error :messages="$errors->get('first_name')" />
                </div>
            </div>

            <div>
                <x-input-label for="name" :value="__('Cognome')" />
                <x-text-input wire:model.blur="last_name" id="last_name" name="last_name" type="text"
                    class="mt-1 block w-full" required autocomplete="family-name" />
                <div class="min-h-5 text-center mt-1">
                    <x-input-error :messages="$errors->get('last_name')" />
                </div>
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model.blur="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm my-2 text-gray-800 dark:text-amber-500 animate-pulse">
                        {{ __('Il tuo indirizzo email non è verificato.') }}
                    </p>
                    <x-secondary-button wire:click.prevent="sendVerification" class="w-full justify-center">
                        {{ __('Invia nuovamente email di verifica') }}
                    </x-secondary-button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Un nuovo link di verifica è stato inviato al tuo indirizzo email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div class="mt-2" x-data="{
            init() {
                this.$nextTick(() => {
                    if (typeof window.intlTelInput !== 'function') return;
        
                    const input = $refs.phoneInput;
                    const iti = window.intlTelInput(input, {
                        initialCountry: 'it',
                        separateDialCode: true,
                        countrySearch: true,
                        i18n: window.itiI18nIt,
                    });
        
                    if (window.itiUtils) {
                        iti.utils = window.itiUtils;
                    }
        
                    if (this.$wire.phone) {
                        iti.setNumber(this.$wire.phone);
                    }
        
                    const syncPhone = () => {
                        input.value = input.value.replace(/\D/g, '');
        
                        const dialCodeEl = this.$el.querySelector('.iti__selected-dial-code');
                        const dialCode = dialCodeEl ? dialCodeEl.innerText.trim() : '';
                        const rawNumber = input.value;
        
                        if (rawNumber === '') {
                            this.$wire.set('phone', '', false);
                            return;
                        }
        
                        this.$wire.set('phone', dialCode + rawNumber, false);
                    };
        
                    input.addEventListener('countrychange', syncPhone);
                    input.addEventListener('input', syncPhone);
                });
            }
        }">
            <x-input-label for="phone" :value="__('Telefono')" />

            <div wire:ignore class="mt-1">
                <x-text-input x-ref="phoneInput" type="tel" id="phone" name="phone" autocomplete="tel"
                    class="block w-full text-start" />
            </div>

            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('phone')" />
            </div>
        </div>

        <div>
            <x-primary-button type="button" wire:click="updateProfileInformation" wire:loading.attr="disabled"
                wire:target="updateProfileInformation"
                class="w-full justify-center disabled:opacity-50 disabled:cursor-wait">

                <span wire:loading.remove wire:target="updateProfileInformation">
                    {{ __('Salva') }}
                </span>

                <span wire:loading wire:target="updateProfileInformation">
                    {{ __('Salvataggio...') }}
                </span>
            </x-primary-button>

            <x-action-message class="mt-1 text-center" on="profile-updated" role="status" aria-live="polite">
                {{ __('Modifiche salvate.') }}
            </x-action-message>
        </div>
    </form>
</section>
