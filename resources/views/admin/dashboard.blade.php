@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Admin Banner (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin • Sarpras System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Dashboard Ringkasan Utama</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau performa sarana prasarana, kelola aset, dan tanggapi pengaduan secara real-time.</p>
        </div>
        <!-- Tombol Pintasan Profil Aman -->
        <a href="{{ route('admin.profile') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Pengaturan Profil</span>
        </a>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-medium transition-opacity duration-500 flex items-center gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Ringkasan Statistik Interaktif (4 Kolom Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Laporan -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Laporan</span>
                    <div class="p-2 bg-white text-indigo-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $laporanMasuk->count() ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Laporan pengaduan masuk</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola laporan masuk &rarr;
            </span>
        </a>

        <!-- Card 2: Status Menunggu -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-amber-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Perlu Diproses</span>
                    <div class="p-2 bg-white text-amber-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-amber-600 mt-3">
                    {{ $laporanMasuk->where('status_laporan', 'Menunggu')->count() ?? 0 }}
                </h4>
                <p class="text-slate-500 text-xs mt-1">Menunggu respon/perbaikan</p>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Tinjau laporan menunggu &rarr;
            </span>
        </a>

        <!-- Card 3: Kelola Pengguna -->
        <a href="{{ route('admin.users') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-blue-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pengguna Terdaftar</span>
                    <div class="p-2 bg-white text-blue-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">Kelola</h4>
                <p class="text-slate-500 text-xs mt-1">Manajemen akun & hak akses</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola data pengguna &rarr;
            </span>
        </a>

        <!-- Card 4: Cetak Rekapitulasi -->
        <a href="{{ route('admin.cetakLaporan') }}" target="_blank" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-slate-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Cetak Dokumen</span>
                    <div class="p-2 bg-white text-slate-700 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-slate-800 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-slate-800 mt-3">Rekap Laporan</h4>
                <p class="text-slate-500 text-xs mt-1">Unduh atau cetak file PDF</p>
            </div>
            <span class="text-xs font-semibold text-slate-700 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Unduh rekap PDF &rarr;
            </span>
        </a>

    </div>

    <!-- Quick Action Bar -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Butuh Meninjau Seluruh Pengaduan?</h3>
            <p class="text-slate-500 text-xs mt-0.5">Seluruh data rincian pelapor, foto kerusakan, dan pengubahan status tersedia terpusat di halaman Data Laporan.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 shrink-0">
            <span>Buka Halaman Data Laporan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>
@endsection