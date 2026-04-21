<x-layout>


    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Content -->
        <main class="flex flex-col min-h-[calc(100vh-80px)]">
            <div class="flex-1 flex flex-col justify-center items-center my-5">

                <x-application-logo size="medium" class="px-5" />

                <div
                    class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg border border-gray-300 dark:border-gray-700">
                    {{ $slot }}
                </div>

            </div>
        </main>

        {{-- Page Footer --}}
        <x-footer />
    </div>

</x-layout>
