<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeCare - Aplikasi Sarana Prasarana Kantor</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">

    <!-- Wrapper Utama dengan Layout Flex (Sidebar Kiri & Konten Kanan) -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- ================= SIDEBAR KIRI (TERANG) ================= -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shadow-sm">
            <div>
                <!-- Logo / Judul Brand -->
                <div class="h-16 flex items-center px-6 border-b border-slate-100">
                    <span class="text-xl font-bold tracking-wide text-blue-600 flex items-center gap-2">
                        OfficeCare
                    </span>
                </div>

                <!-- Menu Navigasi Samping -->
                <nav class="p-4 space-y-2">
                    <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-sm transition">
                        📊 Dashboard
                    </a>
                </nav>
            </div>

            <!-- Tombol Keluar di Bagian Bawah Sidebar -->
            @auth
            <div class="p-4 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition font-medium text-sm">
                        🚪 Keluar
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- ================= KONTEN UTAMA DI KANAN ================= -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- Navbar Atas / Header Mini untuk Info User -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex justify-between items-center sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-slate-700">Panel Karyawan</span>
                </div>

                @auth
                    <div class="flex items-center gap-4">
                        <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full uppercase font-semibold tracking-wider">
                            {{ Auth::user()->role }}
                        </span>
                        <!-- Tombol logout versi mobile -->
                        <form action="{{ route('logout') }}" method="POST" class="inline md:hidden">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs font-medium transition text-white">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </header>

            <!-- Area Konten Dinamis -->
            <main class="flex-1 max-w-7xl w-full mx-auto p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 text-center py-4 text-xs text-slate-500">
                &copy; 2026 OfficeCare. Sistem Manajemen Sarpras Kantor.
            </footer>
        </div>

    </div>

</body>
</html>