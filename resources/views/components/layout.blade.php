<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bubba Camper') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/images/logo.svg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">

    {{ $slot }}

    {{-- SWEET ALERT 2 MESSAGES --}}
    @if (session()->has('swal-success'))
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.dispatch('swal-success', {
                    message: "{{ session('swal-success') }}"
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
                    message: "{{ session('swal-error') }}"
                });
            }, {
                once: true
            });
        </script>
    @endif

</body>

</html>
