{{--<!DOCTYPE html>--}}
<html lang="zxx">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
       @include('partial.styles')
    <!-- Title -->
    <title>@yield('title')</title>
        </head>
    <body style="background-color: #F6F7F9;">
        @include('partial.header')
                @include('partial.sidebar')
                    <div class="main-content-container overflow-hidden">
                        @yield('content')
                    </div>

        <div class="flex-grow-1"></div>
        @include('partial.theme-setting')
        @include('partial.footer')


        @include('partial.scripts')
        @stack('scripts')
    </body>
</html>
