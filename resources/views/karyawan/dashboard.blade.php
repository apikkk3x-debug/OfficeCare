@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Sambutan -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Dashboard Karyawan</h2>
            <p class="text-stone-700 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}. Pantau status laporan dan fasilitas kantor di sini.</p>
        </div>
        <span class="bg-amber-100 text-amber-900 font-semibold px-4 py-2 rounded-xl text-sm border border-amber-200">
            Karyawan Aktif
        </span>
    </div>

    <!-- Bagian Atas: Banner Ajukan Kerusakan & Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Banner Interaktif Menuju Form Input -->
        <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl border border-amber-400/60 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-stone-800 mb-2">Ada Fasilitas Rusak?</h3>
                <p class="text-stone-700 text-sm mb-6">Laporkan kerusakan AC, lampu, proyektor, atau fasilitas kantor lainnya agar segera ditangani.</p>
            </div>
                
            <!-- Tombol Pintasan dengan Nuansa Soft Cream & Amber -->
            <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center gap-2 bg-amber-100 hover:bg-amber-300 text-amber-900 font-semibold px-5 py-2.5 rounded-xl text-sm transition shadow-sm border border-amber-200 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Kerusakan Baru
            </a>
        </div>

        <!-- Ringkasan Total Laporan -->
        <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex flex-col justify-center">
            <span class="text-stone-700 text-xs font-semibold uppercase tracking-wider">Total Laporan Anda</span>
            <h4 class="text-3xl font-bold text-stone-900 mt-1">{{ count($laporanku) }}</h4>
            <p class="text-stone-700 text-xs mt-1">Laporan yang pernah diajukan</p>
        </div>

        <!-- Ringkasan Laporan Aktif/Dalam Proses -->
        <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/60 flex flex-col justify-center">
            <span class="text-stone-700 text-xs font-semibold uppercase tracking-wider">Status Aktif</span>
            <h4 class="text-3xl font-bold text-stone-900 mt-1">
                {{ $laporanku->where('status_laporan', '!=', 'Selesai')->count() }}
            </h4>
            <p class="text-stone-700 text-xs mt-1">Laporan dalam penanganan</p>
        </div>
    </div>

    <!-- Informasi / Pintasan Opsional ke Halaman Riwayat Laporan -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl border border-amber-400/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-stone-800">Butuh Melihat Riwayat Lengkap?</h3>
            <p class="text-sm text-stone-700 mt-1">Semua daftar riwayat laporan, status, serta opsi edit/pembatalan kini bisa diakses melalui menu tabel terpisah.</p>
        </div>
        <a href="{{ route('laporan.index') }}" class="bg-stone-800 hover:bg-stone-900 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition shadow-sm whitespace-nowrap">
            Buka Menu Riwayat &rarr;
        </a>
    </div>

</div>
@endsection