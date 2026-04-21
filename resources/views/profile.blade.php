<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profilo') }}
        </h1>
    </x-slot>

    <div>
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-center items-center border border-gray-300 dark:border-gray-700">
                <div class="max-w-sm w-full text-center">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-center items-center border border-gray-300 dark:border-gray-700">
                <div class="max-w-sm w-full text-center">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-center items-center border border-gray-300 dark:border-gray-700">
                <div class="max-w-sm w-full text-center">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
