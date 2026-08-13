<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeCare - Aplikasi Sarana Prasarana Kantor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <div class="min-h-screen flex flex-col">
        <header class="bg-blue-600 text-white shadow-md py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wide">OfficeCare</h1>
            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm bg-blue-700 px-3 py-1 rounded-full uppercase font-semibold">
                        {{ Auth::user()->role }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm font-medium transition cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto p-6">
            @yield('content')
        </main>

        <footer class="bg-white border-t text-center py-4 text-xs text-slate-500">
            &copy; 2026 OfficeCare. Sistem Manajemen Sarpras Kantor.
        </footer>
    </div>

</body>
</html>