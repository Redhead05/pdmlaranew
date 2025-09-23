<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
</body>
</html>
