<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeCare - Aplikasi Sarana Prasarana Kantor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN untuk Interaktivitas Dropdown Profil -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased">

    <!-- Wrapper Utama -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- ================= SIDEBAR KIRI DESKTOP ================= -->
        <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between hidden md:flex shadow-xl z-20">
            <div>
                <!-- Logo / Judul Brand -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800/80">
                    <span class="text-xl font-bold tracking-wide text-white flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0v-5a2 2 0 012-2h2a2 2 0 012 2v5"></path>
                            </svg>
                        </div>
                        OfficeCare
                    </span>
                </div>

                <!-- Menu Navigasi Samping Dinamis (3 Role) -->
                <nav class="p-4 space-y-1.5">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- 1. MENU ADMIN -->
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                Dashboard Admin
                            </a>

                            <a href="{{ route('admin.users') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Kelola Pengguna
                            </a>

                            <a href="{{ route('admin.laporan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Data Laporan
                            </a>

                            <a href="{{ route('admin.barang.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.barang*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Manajemen Aset
                            </a>

                        @elseif(Auth::user()->role === 'pimpinan')
                            <!-- 2. MENU PIMPINAN -->
                            <a href="{{ route('pimpinan.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('pimpinan.pengadaan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('pimpinan.pengadaan.*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Persetujuan Pengadaan
                            </a>

                            <a href="{{ route('pimpinan.rekap') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('pimpinan.rekap') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Rekap & Cetak
                            </a>

                        @else
                            <!-- 3. MENU KARYAWAN -->
                            <a href="{{ route('karyawan.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('karyawan.dashboard') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('laporan.create') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('laporan.create') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Buat Laporan
                            </a>

                            <a href="{{ route('laporan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('laporan.index') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Riwayat Laporan
                            </a>

                            <a href="{{ route('karyawan.pengadaan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('karyawan.pengadaan.*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Pengadaan Barang
                            </a>
                        @endif

                        <!-- Menu Profil Umum -->
                        <a href="{{ route('karyawan.profile') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('karyawan.profile*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil Saya
                        </a>
                    @endauth
                </nav>
            </div>

            <!-- Tombol Keluar (Bottom Sidebar Desktop) -->
            @auth
            <div class="p-4 border-t border-slate-800/80">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-300 border border-slate-700/60 hover:text-white transition font-medium text-xs shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Area Kanan (Header + Konten + Footer) -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <!-- Header Atas Dinamis -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-6 md:px-8 flex justify-between items-center z-10 shadow-sm shrink-0">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-slate-800 text-base md:text-lg">
                        @auth
                            @if(Auth::user()->role === 'admin')
                                Panel Admin Sarpras
                            @elseif(Auth::user()->role === 'pimpinan')
                                Panel Pimpinan Executive
                            @else
                                Panel Karyawan
                            @endif
                        @else
                            OfficeCare
                        @endauth
                    </span>
                </div>

                @auth
                    <div class="flex items-center gap-3">
                        <!-- Profil Dropdown Top Bar (Klik untuk membuka menu melayang) -->
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <!-- Trigger Button: Foto Profil Bundar dengan Ring Hijau -->
                            <button @click="open = !open" type="button" class="w-9 h-9 rounded-full p-0.5 bg-emerald-500 focus:outline-none cursor-pointer hover:ring-2 hover:ring-emerald-400 transition shrink-0">
                                <div class="w-full h-full rounded-full overflow-hidden bg-slate-800 flex items-center justify-center">
                                    @if(Auth::user()->foto)
                                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold text-white uppercase">
                                            {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                            </button>

                            <!-- Dropdown Menu Box -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 z-50 space-y-3"
                                 style="display: none;">
                                
                                <!-- Header Info User -->
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full p-0.5 bg-emerald-500 shrink-0">
                                        <div class="w-full h-full rounded-full overflow-hidden bg-slate-800 flex items-center justify-center">
                                            @if(Auth::user()->foto)
                                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-sm font-bold text-white uppercase">
                                                    {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 class="text-sm font-bold text-slate-800 truncate leading-tight">
                                            {{ Auth::user()->nama ?? Auth::user()->name }}
                                        </h4>
                                        <span class="inline-block mt-1 px-2.5 py-0.5 bg-blue-100 text-blue-600 font-semibold rounded-md text-[11px] capitalize">
                                            {{ Auth::user()->role }}
                                        </span>
                                    </div>
                                </div>

                                <hr class="border-slate-100">

                                <!-- Navigasi Link -->
                                <div class="space-y-1">
                                    @php
                                        $dashRoute = match(Auth::user()->role) {
                                            'admin' => route('admin.dashboard'),
                                            'pimpinan' => route('pimpinan.dashboard'),
                                            default => route('karyawan.dashboard'),
                                        };
                                    @endphp
                                    <a href="{{ $dashRoute }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>

                                    <a href="{{ route('karyawan.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>Profil Saya</span>
                                    </a>
                                </div>

                                <hr class="border-slate-100">
                                <!-- Tombol Log out Red Card -->
                                <form action="{{ route('logout') }}" method="POST" class="pt-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-600 font-bold text-xs py-2.5 rounded-xl transition text-center cursor-pointer">
                                        Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Tombol Logout Quick (Mobile Top Bar) -->
                        <form action="{{ route('logout') }}" method="POST" class="inline md:hidden">
                            @csrf
                            <button type="submit" class="bg-slate-100 hover:bg-slate-200 p-2 rounded-full text-slate-600 transition shadow-sm" title="Logout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </header>

            <!-- Area Konten Utama -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 pb-20 md:pb-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- Footer Desktop -->
            <footer class="bg-white border-t border-slate-200/80 text-center py-3 text-xs text-slate-400 shrink-0 hidden md:block">
                &copy; 2026 OfficeCare. Sistem Manajemen Sarpras Kantor.
            </footer>
        </div>

    </div>

    <!-- Navigasi Bawah Mobile (Khusus HP - Dinamis 3 Role) -->
    @auth
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-800 flex justify-around p-2 z-30 shadow-lg">
        @if(Auth::user()->role === 'admin')
            <!-- Mobile Admin -->
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('admin.users*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Pengguna
            </a>

        @elseif(Auth::user()->role === 'pimpinan')
            <!-- Mobile Pimpinan -->
            <a href="{{ route('pimpinan.dashboard') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('pimpinan.dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6m2 0h2a2 2 0 002-2v-5a2 2 0 00-2-2h-2m-4-6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"></path>
                </svg>
                Executive
            </a>
            <a href="{{ route('pimpinan.rekap') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('pimpinan.rekap*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Rekap & Cetak
            </a>

        @else
            <!-- Mobile Karyawan -->
            <a href="{{ route('karyawan.dashboard') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('karyawan.dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('laporan.create') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('laporan.create') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Buat
            </a>
            <a href="{{ route('laporan.index') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('laporan.index') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Riwayat
            </a>
        @endif

        <a href="{{ route('karyawan.profile') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('karyawan.profile*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil
        </a>
    </nav>
    @endauth

</body>
</html>