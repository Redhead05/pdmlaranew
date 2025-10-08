{{--<!DOCTYPE html>--}}
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{ asset ('assets/css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/simplebar.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/apexcharts.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/prism.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/rangeslider.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/quill.snow.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/google-icon.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/remixicon.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/fullcalendar.main.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/jsvectormap.min.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/lightpick.css')}}">
    <link rel="stylesheet" href="{{ asset ('assets/css/style.css')}}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset ('assets/images/favicon.png')}}">
    <!-- Title -->
    <title>Trezo - Bootstrap 5 Admin Dashboard Template</title>
        </head>
    <body>
    @include('partial.header')
        <div class="container flex">
            @include('partial.sidebar')
            <main class="flex-1 p-4">
                @yield('content')
            </main>
        </div>
    @include('partial.footer')

        <script src="{{ asset ('assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset ('assets/js/sidebar-menu.js') }}"></script>
        <script src="{{ asset ('assets/js/dragdrop.js') }}"></script>
        <script src="{{ asset ('assets/js/rangeslider.min.js') }}"></script>
        <script src="{{ asset ('assets/js/quill.min.js') }}"></script>
        <script src="{{ asset ('assets/js/data-table.js') }}"></script>
        <script src="{{ asset ('assets/js/prism.js') }}"></script>
        <script src="{{ asset ('assets/js/clipboard.min.js') }}"></script>
        <script src="{{ asset ('assets/js/feather.min.js') }}"></script>
        <script src="{{ asset ('assets/js/simplebar.min.js') }}"></script>
        <script src="{{ asset ('assets/js/apexcharts.min.js') }}"></script>
        <script src="{{ asset ('assets/js/echarts.min.js') }}"></script>
        <script src="{{ asset ('assets/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset ('assets/js/fullcalendar.main.js') }}"></script>
        <script src="{{ asset ('assets/js/jsvectormap.min.js') }}"></script>
        <script src="{{ asset ('assets/js/world-merc.js') }}"></script>
        <script src="{{ asset ('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset ('assets/js/lightpick.js') }}"></script>
        <script src="{{ asset ('assets/js/custom/apexcharts.js') }}"></script>
        <script src="{{ asset ('assets/js/custom/echarts.js') }}"></script>
        <script src="{{ asset ('assets/js/custom/custom.js') }}"></script>
    </body>
</html>
