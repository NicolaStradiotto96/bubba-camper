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
    <img src="{{ url('/logo.png') }}" alt="Bubba Camper" style="{{ $style }}">
</div>
