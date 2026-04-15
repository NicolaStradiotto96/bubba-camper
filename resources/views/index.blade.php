<x-app-layout>

    {{-- TITLE --}}
    <header class="flex flex-col items-center justify-center mt-5">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight text-center">I NOSTRI CAMPER</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4 text-center">
            {{ __('Dimentica la routine e riscopri la libertà. Scegli il compagno di viaggio ideale per la tua prossima avventura all\'aria aperta.') }}
        </p>

        <div class="mt-4 flex justify-center">
            <div class="w-72 md:w-96 h-1 bg-amber-600 rounded-full"></div>
        </div>
    </header>


    {{-- INDEX --}}
    <section class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            @foreach ($campers as $camper)
                <x-card :camper="$camper" />
            @endforeach
        </div>
    </section>

</x-app-layout>
