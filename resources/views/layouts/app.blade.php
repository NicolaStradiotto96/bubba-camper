<x-layout>

    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 text-center">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow my-10">
            {{ $slot }}
        </main>

        {{-- Page Footer --}}
        <x-footer />
    </div>

    <div class="sm:hidden fixed bottom-5 right-5 z-50">
        <div class="bg-white dark:bg-gray-800 p-2 rounded-full shadow-lg border border-gray-200 dark:border-gray-700">
            <x-theme-toggle />
        </div>
    </div>

</x-layout>
