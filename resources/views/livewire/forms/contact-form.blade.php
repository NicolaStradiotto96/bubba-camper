<div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest">
            Contattaci via email
        </h2>
    </div>

    {{-- ERRORS --}}
    @if (session()->has('success'))
        <div class="text-center mb-4 p-4 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="text-center mb-4 p-4 rounded bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM --}}
    <form wire:submit.prevent="sendEmail" class="space-y-4">
        @csrf
        {{-- Honeypot --}}
        <input type="text" wire:model="website" tabindex="-1" autocomplete="off" aria-hidden="true"
            style="position:absolute; left:-9999px; height:0; overflow:hidden;">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Name --}}
            <div>
                <x-input-label for="name" :value="__('Nome')" />
                <x-text-input id="name" wire:model="name" type="text" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" wire:model="email" type="email" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Start date --}}
            <div>
                <x-input-label for="start_date" :value="__('Data Inizio')" />
                <x-text-input id="start_date" wire:model="start_date" type="date" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
            </div>
            {{-- End date --}}
            <div>
                <x-input-label for="end_date" :value="__('Data Fine')" />
                <x-text-input id="end_date" wire:model="end_date" type="date" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
            </div>
        </div>

        {{-- Message --}}
        <div>
            <x-input-label for="message" :value="__('Il tuo messaggio')" />
            <textarea id="message" wire:model="message" rows="4"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm"
                placeholder="Parlaci del tuo itinerario..."></textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
        </div>

        {{-- Bottone --}}
        <div class="flex justify-center">
            <x-primary-button class="w-full justify-center">
                <span wire:loading.remove wire:target="sendEmail">{{ __('Invia richiesta') }}</span>
                <span wire:loading wire:target="sendEmail">{{ __('Invio in corso...') }}</span>
            </x-primary-button>
        </div>
    </form>
</div>
