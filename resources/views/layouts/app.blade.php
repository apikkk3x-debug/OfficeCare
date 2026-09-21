<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeCare - Aplikasi Sarana Prasarana Kantor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN untuk Interaktivitas Dropdown Profil, Toast & Notifikasi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased">

    <!-- ================= GLOBAL TOAST NOTIFIKASI (MUNCUL DI SEMUA HALAMAN) ================= -->
    @if(session('success'))
        <div x-data="{ show: true, progress: 100 }" 
             x-init="
                setTimeout(() => { show = false }, 3000);
                let interval = setInterval(() => {
                    progress -= 1;
                    if (progress <= 0) clearInterval(interval);
                }, 30);
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden pointer-events-auto">
            
            <div class="p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <h5 class="text-xs font-bold text-slate-800">Berhasil!</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Garis Hitung Mundur (Progress Bar) -->
            <div class="h-1 bg-slate-100 w-full">
                <div class="h-full bg-emerald-500 transition-all duration-75" :style="`width: ${progress}%`"></div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true, progress: 100 }" 
             x-init="
                setTimeout(() => { show = false }, 3000);
                let interval = setInterval(() => {
                    progress -= 1;
                    if (progress <= 0) clearInterval(interval);
                }, 30);
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden pointer-events-auto">
            
            <div class="p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <h5 class="text-xs font-bold text-slate-800">Terjadi Kesalahan</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Garis Hitung Mundur (Progress Bar) -->
            <div class="h-1 bg-slate-100 w-full">
                <div class="h-full bg-rose-500 transition-all duration-75" :style="`width: ${progress}%`"></div>
            </div>
        </div>
    @endif

    @auth
        <!-- Wrapper Utama (Hanya Tampil Jika Sudah Login) -->
        <div class="min-h-screen flex bg-slate-50">
            
            <!-- ================= SIDEBAR KIRI DESKTOP (Fixed) ================= -->
            <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between hidden md:flex shadow-xl fixed inset-y-0 left-0 z-30">
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
                    <nav class="p-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-8rem)]">
                        @php
                            $userRole = strtolower(Auth::user()->role ?? 'karyawan');
                            $profileRoute = match($userRole) {
                                'admin'    => route('admin.profile'),
                                'pimpinan' => route('pimpinan.profile'),
                                default    => route('karyawan.profile'),
                            };
                        @endphp

                        @if($userRole === 'admin')
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

                            <a href="{{ route('admin.pengadaan.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.pengadaan.*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Pengadaan Barang
                            </a>

                            <a href="{{ route('admin.barang.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.barang*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Manajemen Aset
                            </a>

                            <!-- Menu Sidebar: Kelola Pengumuman -->
                            <a href="{{ route('admin.pengumuman.index') }}" 
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.pengumuman*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                                Pengumuman Kantor
                            </a>

                        @elseif($userRole === 'pimpinan')
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
                        <a href="{{ $profileRoute }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('*.profile*') ? 'bg-slate-200 text-black font-semibold shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil Saya
                        </a>
                    </nav>
                </div>
                <!-- INFORMASI KONTAK & LAYANAN DI BAWAH SIDEBAR -->
                <div class="mt-auto p-3.5 mx-3 mb-4 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Ext. Sarpras</span>
                        <span class="font-mono text-indigo-300 font-bold">#4042</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Jam Kerja</span>
                        <span class="text-slate-200">08:00 - 17:00</span>
                    </div>
                    <div class="pt-1.5 border-t border-white/5 text-[10px] text-slate-400 truncate">
                        officecare.support@gmail.com
                    </div>
                </div>
            </aside>

            <!-- ================= AREA KANAN (ml-64 agar tidak tertutup sidebar fixed) ================= -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden ml-0 md:ml-64">
                
                <!-- Header Atas Dinamis (Sticky) -->
                <header class="h-16 bg-white border-b border-slate-200/80 px-6 md:px-8 flex justify-between items-center sticky top-0 z-20 shadow-sm shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-slate-800 text-base md:text-lg">
                            @if($userRole === 'admin')
                                Panel Admin Sarpras
                            @elseif($userRole === 'pimpinan')
                                Panel Pimpinan Executive
                            @else
                                Panel Karyawan
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        @php
                            $headerProfileRoute = $profileRoute;
                        @endphp
              <!-- ================= PUSAT NOTIFIKASI MODERN & PERSISTENT ================= -->
                            <script>
                            window.globalNotifications = @json($globalNotifications ?? []);
                        </script>
                        
                        <div class="relative" 
                             x-data="{ 
                                openNotification: false,
                                dismissed: JSON.parse(localStorage.getItem('officecare_dismissed_notifs') || '[]'),
                                notifications: window.globalNotifications,
                                
                                get activeNotifications() {
                                    return this.notifications.filter(n => !this.dismissed.includes(n.id));
                                },
                                
                                dismiss(id) {
                                    if (!this.dismissed.includes(id)) {
                                        this.dismissed.push(id);
                                        localStorage.setItem('officecare_dismissed_notifs', JSON.stringify(this.dismissed));
                                    }
                                }
                             }">
                            
                            <!-- Tombol Lonceng (Bell Button) -->
                            <button @click="openNotification = !openNotification" 
                                    class="relative p-2.5 text-slate-600 hover:text-indigo-600 bg-slate-100 hover:bg-slate-200/80 rounded-full transition cursor-pointer"
                                    title="Notifikasi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                
                                <!-- Dot Indikator MERAH -->
                                <template x-if="activeNotifications.length > 0">
                                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                                </template>
                            </button>

                            <!-- Dropdown Pop-up Notifikasi -->
                            <div x-show="openNotification" 
                                 @click.outside="openNotification = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                 style="display: none;"
                                 class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-3 z-50">
                                
                                <div class="px-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pusat Notifikasi</h4>
                                    </div>
                                    <span class="text-[10px] bg-indigo-50 text-indigo-600 font-semibold px-2 py-0.5 rounded-md border border-indigo-100"
                                          x-text="activeNotifications.length + ' Baru'">
                                    </span>
                                </div>

                                <div class="max-h-80 overflow-y-auto px-2 py-2 space-y-1.5">
                                    <template x-for="notif in activeNotifications" :key="notif.id">
                                        <div class="group relative bg-slate-50/60 hover:bg-indigo-50/40 rounded-xl p-3 border border-slate-200/60 hover:border-indigo-200 transition">
                                            <div class="flex items-start justify-between gap-2.5">
                                                
                                                <!-- LINK DETAIL NOTIFIKASI -->
                                            <a :href="notif.url" class="flex items-start gap-3 flex-1 min-w-0">
                                                
                                                <!-- 1. Ikon Pengumuman (Warna Amber/Kuning) -->
                                                <template x-if="notif.type === 'pengumuman'">
                                                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 border border-amber-500/20 mt-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                                        </svg>
                                                    </div>
                                                </template>

                                                <!-- 2. Ikon Pengadaan Barang (Warna Biru) -->
                                                <template x-if="notif.type === 'pengadaan'">
                                                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center shrink-0 border border-blue-500/20 mt-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                        </svg>
                                                    </div>
                                                </template>

                                                <!-- 3. Ikon Komentar (Warna Ungu) -->
                                                <template x-if="notif.type === 'komentar'">
                                                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0 border border-purple-500/20 mt-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                        </svg>
                                                    </div>
                                                </template>

                                                <!-- 4. Ikon Laporan Kerusakan (Warna Indigo) -->
                                                <template x-if="notif.type === 'laporan'">
                                                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-500/20 mt-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                </template>

                                                <!-- Konten Teks -->
                                                <div class="flex-1 min-w-0 pr-4">
                                                    <div class="flex items-center gap-1.5 mb-1">
                                                        <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded border"
                                                            :class="notif.badge_style"
                                                            x-text="notif.badge"></span>
                                                    </div>
                                                    <h5 class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-600 transition" 
                                                        x-text="notif.title"></h5>
                                                    <p class="text-[11px] text-slate-600 mt-0.5 line-clamp-2 leading-tight" 
                                                    x-text="notif.desc"></p>
                                                </div>
                                            </a>

                                                <button @click="dismiss(notif.id)" 
                                                        class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1 rounded-lg transition cursor-pointer shrink-0" 
                                                        title="Hapus notifikasi ini">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="activeNotifications.length === 0">
                                        <div class="text-center py-8 text-slate-400 text-xs italic bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                            Tidak ada notifikasi saat ini.
                                        </div>
                                    </template>
                                </div>

                                <div class="pt-2 px-4 border-t border-slate-100 text-center">
                                    <span class="text-[10px] text-slate-400 font-medium">OfficeCare Notification Hub</span>
                                </div>
                            </div>
                        </div>

                        <!-- Profil Dropdown Top Bar dengan Alpine.js -->
                        <div x-data="{ open: false }" class="relative inline-block text-left">
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
                                        $dashRoute = match($userRole) {
                                            'admin'    => route('admin.dashboard'),
                                            'pimpinan' => route('pimpinan.dashboard'),
                                            default    => route('karyawan.dashboard'),
                                        };
                                    @endphp
                                    <a href="{{ $dashRoute }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>

                                    <a href="{{ $headerProfileRoute }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
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
                            <button type="submit" class="bg-slate-100 hover:bg-slate-200 p-2 rounded-full text-slate-600 transition shadow-sm cursor-pointer" title="Logout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Area Konten Utama -->
                <main class="flex-1 min-h-0 overflow-y-auto p-4 md:p-8 pb-24 md:pb-8">
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
        @php
            $mobileProfileRoute = $profileRoute;
        @endphp
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-800 flex justify-around p-2 z-30 shadow-lg">
            @if($userRole === 'admin')
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

            @elseif($userRole === 'pimpinan')
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

            <a href="{{ $mobileProfileRoute }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('*.profile*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profil
            </a>
        </nav>
    @else
        <!-- ================= JIKA BELUM LOGIN (HALAMAN TAMU / LOGIN & REGISTER) ================= -->
        <main class="w-full min-h-screen flex items-center justify-center p-0 md:p-6">
            <div class="w-full">
                @yield('content')
            </div>
        </main>
    @endauth

</body>
</html>