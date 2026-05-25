@php
    $hasNotifyPackageView = view()->exists('notify::components.notify');
@endphp

@if($hasNotifyPackageView)
    {{-- Wrapper ini aman: begitu package laravel-notify berhasil di-install, komponen vendor akan ikut dirender. --}}
    @include('notify::components.notify')
@endif

@include('partial.flash')
