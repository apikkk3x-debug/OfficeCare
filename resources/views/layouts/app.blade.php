<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeCare - Aplikasi Sarana Prasarana Kantor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FDFBF7] text-stone-700 font-sans antialiased">

    <!-- Wrapper Utama -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- ================= SIDEBAR KIRI (Warm Cream & Soft Amber) ================= -->
        <aside class="w-64 bg-white border-r border-amber-300/80 flex flex-col justify-between hidden md:flex shadow-sm">
            <div>
                <!-- Logo / Judul Brand -->
                <div class="bg-[rgb(255,232,157)] h-16 flex items-center px-6 border-b border-amber-300/60">
                    <span class="text-xl font-bold tracking-wide text-amber-900 flex items-center gap-2">
                        OfficeCare
                    </span>
                </div>

                <!-- Menu Navigasi Samping -->
                <nav class="p-4 space-y-2">
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('karyawan.dashboard') ? 'bg-amber-100/80 text-amber-900 shadow-sm font-semibold border border-amber-200/60' : 'text-stone-600 hover:bg-amber-50/50' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('laporan.create') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('laporan.create') ? 'bg-amber-100/80 text-amber-900 shadow-sm font-semibold border border-amber-200/60' : 'text-stone-600 hover:bg-amber-50/50' }}">
                        📝 Buat Laporan
                    </a>
                    <a href="{{ route('laporan.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('laporan.index') ? 'bg-amber-100/80 text-amber-900 shadow-sm font-semibold border border-amber-200/60' : 'text-stone-600 hover:bg-amber-50/50' }}">
                        📋 Riwayat Laporan
                    </a>
                </nav>
            </div>

            <!-- Tombol Keluar di Bawah Sidebar -->
            @auth
            <div class="p-4 border-t border-amber-100/60">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[rgb(255,112,112)] text-stone-100 hover:bg-red-50 hover:text-red-600 transition font-medium text-sm border border-stone-200/60">
                        🚪 logout
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Area Kanan (Header + Konten + Footer) -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <!-- Header Atas -->
            <header class="h-14 bg-white/80 backdrop-blur border-b border-amber-100/80 px-8 flex justify-between items-center z-10 shadow-sm shrink-0">
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-stone-700 text-base">Panel Karyawan</span>
                </div>

                @auth
                    <div class="flex items-center gap-4">
                        <!-- Badge Karyawan Krem Elegan -->
                        <span class="text-xs bg-amber-100 text-amber-900 border border-amber-200 px-4 py-1.5 rounded-full uppercase font-semibold tracking-wider shadow-sm">
                            {{ Auth::user()->role }}
                        </span>
                        
                        <!-- Tombol logout mobile -->
                        <form action="{{ route('logout') }}" method="POST" class="inline md:hidden">
                            @csrf
                            <button type="submit" class="bg-amber-50 hover:bg-amber-100 px-3.5 py-1.5 rounded-lg text-xs font-medium transition text-amber-900">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </header>

            <!-- Area Konten Dinamis yang Bisa Di-scroll dengan Padding yang Nyaman -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-amber-100/80 text-center py-4 text-xs text-stone-500 shrink-0">
                &copy; 2026 OfficeCare. Sistem Manajemen Sarpras Kantor.
            </footer>
        </div>

    </div>

</body>
</html>