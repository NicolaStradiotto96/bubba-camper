@props(['size' => 'small'])

@php
    $sizes = [
        'small' => 'max-width:120px;',
        'medium' => 'max-width:180px;',
        'large' => 'max-width:240px;',
    ];

    $style = $sizes[$size] ?? $sizes['small'];
@endphp

<div>
    <img src="https://images.seeklogo.com/logo-png/38/1/bubba-logo-png_seeklogo-386759.png" alt="Logo {{ config('app.name', 'Bubba Camper') }}" style="{{ $style }}" loading="lazy">
    {{-- {{ config('app.url') }}/logo.png --}}
</div>
