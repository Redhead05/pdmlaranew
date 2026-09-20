{{--<!DOCTYPE html>--}}
<html lang="zxx">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
       @include('partial.styles')
    <style>turbo-frame{display:block;}</style>
    {{-- Bootstrap dimuat di head agar skrip inline halaman (mis. modal) tersedia sebelum konten diframe --}}
    <script src="{{ asset ('assets/js/bootstrap.bundle.min.js')}}"></script>
    {{-- jQuery & DataTables dimuat SEKALI secara global di <head> (di luar turbo-frame).
         Ini penting agar tetap tersedia saat navigasi Turbo frame: skrip eksternal yang
         dire-insert di dalam frame dimuat async, sehingga skrip inline $(document).ready
         bisa jalan SEBELUM jQuery/DataTables selesai diunduh. --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <!-- Title -->
    <title>@yield('title')</title>
        </head>
    <body style="background-color: #F6F7F9;">
        @include('partial.header')
        @include('partial.sidebar')

            {{--main-content di matikan karena merusak halaman kerja--}}
            <div class="transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
                <turbo-frame id="main_frame">
                    @stack('styles')
                    @yield('content')
                    @stack('scripts')
                </turbo-frame>
            </div>

        <div class="flex-grow-1"></div>

        {{--@include('partial.theme-setting')--}}
        @include('partial.footer')
        @include('partial.scripts')

        {{-- Vite entry untuk halaman adminlanding chat (aman karena script hanya jalan jika ada data-adminlanding-chat) --}}
        {{-- turbo.js memuat @hotwired/turbo tanpa preflight Tailwind agar tidak bentrok dengan Bootstrap --}}
        @vite(['resources/js/turbo.js', 'resources/js/adminlanding-chat.js'])
    </body>
</html>
