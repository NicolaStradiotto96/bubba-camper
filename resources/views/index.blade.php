<x-app-layout>

    {{-- TITLE --}}
    <header class="flex flex-col items-center justify-center mt-5">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight text-center">I NOSTRI
            CAMPER</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4 text-center">
            {{ __('Dimentica la routine e riscopri la libertà. Scegli il compagno di viaggio ideale per la tua prossima avventura all\'aria aperta.') }}
        </p>

        <div class="mt-4 flex justify-center">
            <div class="w-72 md:w-96 h-1 bg-amber-600 rounded-full"></div>
        </div>
    </header>


    {{-- INDEX --}}
    <section>
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($campers as $camper)
                    <div class="w-full md:w-[calc(50%-2rem)] lg:w-[calc(33.333%-2rem)] max-w-sm">
                        <x-card :camper="$camper" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
