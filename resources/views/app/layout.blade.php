{{--<!DOCTYPE html>--}}
<html lang="zxx">
    <head>
    <!-- Required meta tags -->
       @include('partial.styles')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @stack('styles')
        @livewireStyles
    <!-- Title -->
    <title>@yield('title')</title>
        </head>
    <body style="background-color: #F6F7F9;">
        @include('partial.header')
                @include('partial.sidebar')
                    <div class="main-content-container overflow-hidden">
                        @yield('content')
                    </div>
        @include('partial.footer')
        @include('partial.theme-setting')
       @include('partial.scripts')
        @livewireScripts
        @stack('scripts')
    </body>
</html>
