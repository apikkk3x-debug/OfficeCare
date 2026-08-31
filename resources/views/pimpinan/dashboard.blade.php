@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Pimpinan Banner (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Executive • OfficeCare
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Dashboard Pimpinan</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Monitoring rekapitulasi kerusakan fasilitas kantor dan laporan operasional secara real-time.</p>
        </div>
        <span class="bg-white/10 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-full text-xs border border-white/10 shrink-0 shadow-sm">
            Pimpinan / Manager
        </span>
    </div>

    <!-- Ringkasan Statistik Interaktif (4 Kolom Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Aset Barang -->
        <div class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Aset Barang</span>
                    <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $totalBarang }} <span class="text-xs font-normal text-slate-500">Unit</span></h4>
                </div>
                <div class="p-2 bg-white text-indigo-600 rounded-xl shadow-sm border border-slate-200/60">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-xs mt-3">Inventaris aktif terdaftar</p>
        </div>

        <!-- Card 2: Menunggu Ditanggapi -->
        <div class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Menunggu Ditanggapi</span>
                    <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $laporanMenunggu }} <span class="text-xs font-normal text-slate-500">Laporan</span></h4>
                </div>
                <div class="p-2 bg-white text-amber-600 rounded-xl shadow-sm border border-slate-200/60">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-xs mt-3">Perlu respon tim Sarpras</p>
        </div>

        <!-- Card 3: Sedang Diproses -->
        <div class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Sedang Diproses</span>
                    <h4 class="text-3xl font-extrabold text-blue-600 mt-3">{{ $laporanDiproses }} <span class="text-xs font-normal text-slate-500">Laporan</span></h4>
                </div>
                <div class="p-2 bg-white text-blue-600 rounded-xl shadow-sm border border-slate-200/60">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-xs mt-3">Dalam pengerjaan perbaikan</p>
        </div>

        <!-- Card 4: Selesai Diperbaiki -->
        <div class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Selesai Diperbaiki</span>
                    <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $laporanSelesai }} <span class="text-xs font-normal text-slate-500">Laporan</span></h4>
                </div>
                <div class="p-2 bg-white text-emerald-600 rounded-xl shadow-sm border border-slate-200/60">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-xs mt-3">Penanganan telah tuntas</p>
        </div>

    </div>

    <!-- Navigation Card ke Rekap & Cetak -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-slate-800 text-sm">Rekapitulasi Laporan Kerusakan & Cetak PDF</h3>
            <p class="text-xs text-slate-500 mt-0.5">Tabel rincian pengaduan kerusakan beserta tombol cetak laporan kini dapat diakses penuh melalui menu Rekap & Cetak.</p>
        </div>
        <a href="{{ route('pimpinan.rekap') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 whitespace-nowrap flex items-center gap-2">
            <span>Buka Menu Rekap & Cetak</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

</div>
@endsection