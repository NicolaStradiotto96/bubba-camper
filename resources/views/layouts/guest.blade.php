<x-layout>


    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Content -->
        <main class="flex-grow">
            <div class="flex flex-col justify-center items-center">
                <div class="flex justify-center mt-5">
                    <x-application-logo size="medium" class="px-5" />
                </div>


                <div
                    class="w-full sm:max-w-md mt-6 mb-16 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </main>

        {{-- Page Footer --}}
        <x-footer />
    </div>

</x-layout>
