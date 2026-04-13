<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }"
    class="sticky top-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-700 shadow z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center w-48">
                    <a href="{{ route('welcome') }}" wire:navigate>
                        <x-application-logo size="small" />
                    </a>
                </div>


            </div>
            <!-- Navigation Links -->
            <div class="hidden md:flex md:items-center md:justify-center flex-1 space-x-8 -my-px">
                <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" wire:navigate>
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link :href="route('index')" :active="request()->routeIs('index')" wire:navigate>
                    {{ __('Noleggio') }}
                </x-nav-link>
                <x-nav-link :href="route('prices')" :active="request()->routeIs('prices')" wire:navigate>
                    {{ __('Prezzi') }}
                </x-nav-link>
                <x-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')" wire:navigate>
                    {{ __('Contatti') }}
                </x-nav-link>
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:justify-end w-48">
                <div class="flex items-center ml-auto">
                    <x-theme-toggle />
                </div>
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white/0 dark:bg-gray-800/0 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')" wire:navigate>
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profilo') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4 ml-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                        <a href="{{ route('register') }}"
                            class="text-sm text-gray-700 dark:text-gray-500 underline">Registrati</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click.stop="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden" @click.outside="open = false">

        <!-- Responsive Settings Options -->
        <div class="py-3 border-t border-gray-200 dark:border-gray-600 text-center">

            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            @endauth
            <div class="mt-3">
                <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" wire:navigate>
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('index')" :active="request()->routeIs('index')" wire:navigate>
                    {{ __('Noleggio') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('prices')" :active="request()->routeIs('prices')" wire:navigate>
                    {{ __('Prezzi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')" wire:navigate>
                    {{ __('Contatti') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @auth
            <div class="py-3 border-t border-gray-200 dark:border-gray-600 ">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate>
                    {{ __('Profilo') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        @else
            <div class="py-3 border-t border-gray-200 dark:border-gray-600 ">
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Log In') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('Registrati') }}
                </x-responsive-nav-link>
            </div>
        @endauth
    </div>

</nav>
