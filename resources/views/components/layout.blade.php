<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Noleggio camper curati nei dettagli. Prenota la tua avventura on-the-road con Bubba Camper e vivi la libertà in totale comfort.">

    {{-- Socials --}}
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ !empty($title) ? $title . ' | ' : '' }}{{ config('app.name') }}">
    <meta property="og:description"
        content="Noleggio camper curati nei dettagli. Prenota la tua avventura on-the-road con Bubba Camper e vivi la libertà in totale comfort.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:type" content="website">

    {{-- Title --}}
    <title>
        {{ !empty($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Bubba Camper') }}
    </title>

    {{-- Robots --}}
    @stack('meta')

    {{-- Preload Welcome Background --}}
    @if (request()->routeIs('welcome'))
        <link rel="preload" as="image" href="{{ asset('images/bg.webp') }}">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/images/logo.svg') }}">

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">

    {{ $slot }}

    {{-- SWEET ALERT 2 MESSAGES --}}
    @if (session()->has('swal-success'))
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.dispatch('swal-success', {
                    message: @json(session('swal-success'))
                });
            }, {
                once: true
            });
        </script>
    @endif

    @if (session()->has('swal-error'))
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.dispatch('swal-error', {
                    message: @json(session('swal-error'))
                });
            }, {
                once: true
            });
        </script>
    @endif
</body>

</html>
