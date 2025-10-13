{{--<!DOCTYPE html>--}}
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

       @include('partial.styles')
        @stack('styles')
    <!-- Title -->
    <title>@yield('title')</title>
        </head>
    <body>
        @include('partial.header')
                @include('partial.sidebar')
                    <div class="main-content-container overflow-hidden">
                        @yield('content')
                    </div>
        @include('partial.footer')

       @include('partial.scripts')
        @stack('scripts')
    </body>
</html>
