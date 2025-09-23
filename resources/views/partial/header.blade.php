<header class="bg-blue-700 text-white p-4 mb-4">
    <h1 class="text-lg font-bold">Aplikasi Dashboard</h1>
    @auth
        <div class="flex items-center gap-3">
            <span class="hidden sm:inline">Hi, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                    Logout
                </button>
            </form>
        </div>
    @endauth
</header>
