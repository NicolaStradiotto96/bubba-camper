<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 uppercase">
            {{ __('Elimina Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Una volta eliminato l'account, tutte le relative risorse e i dati verranno cancellati in modo permanente.") }}
            <br>
            {{ __("Prima di procedere con l'eliminazione, ti preghiamo di scaricare tutti i dati o le informazioni che desideri conservare.") }}
        </p>
    </header>

    <x-danger-button x-data="{ loading: false }"
        x-on:click.prevent="loading = true; $dispatch('open-modal', 'confirm-user-deletion'); setTimeout(() => loading = false, 500)"
        x-bind:disabled="loading" class="w-full justify-center disabled:opacity-50 disabled:cursor-wait">
        {{ __('Elimina Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 flex flex-col items-center" novalidate>

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Sei sicuro di voler eliminare il tuo account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Una volta eliminato l'account, tutti i dati e le risorse associate verranno rimossi definitivamente.") }}
                <br>
                {{ __("Inserisci la tua password per confermare l'operazione.") }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input wire:model="password" id="password" name="password" type="password"
                    class="mt-1 block max-w-sm w-full" placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->get('password')" class="mt-1 text-center" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row items-center gap-3 w-full sm:w-auto justify-center">
                <x-secondary-button x-on:click="$dispatch('close')" wire:click="$set('password', '')"
                    class="w-full sm:w-auto justify-center">
                    {{ __('Annulla') }}
                </x-secondary-button>

                <x-danger-button class="w-full sm:w-auto justify-center disabled:opacity-50 disabled:cursor-wait"
                    wire:loading.attr="disabled" wire:target="deleteUser">
                    {{ __('Elimina Account') }}
                </x-danger-button>
        </form>
    </x-modal>
</section>
