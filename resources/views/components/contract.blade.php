@php
    $version = config('contracts.active_version');
@endphp

@include("contracts.$version.content")
