@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Sambutan (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Overview
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Dashboard Karyawan</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Selamat datang, <span class="font-semibold text-white">{{ Auth::user()->nama ?? Auth::user()->name }}</span>. Kelola pengaduan fasilitas dan pengajuan barang baru kantor di sini.</p>
        </div>
        <span class="bg-white/10 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-full text-xs border border-white/10 shrink-0 shadow-sm">
            Karyawan Aktif
        </span>
    </div>

    <!-- Grid Kartu Utama (4 Kolom Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Action Card 1: Pengaduan Kerusakan -->
        <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Ada Fasilitas Rusak?</h3>
                <p class="text-slate-600 text-xs leading-relaxed mb-5">Laporkan AC, lampu, proyektor, atau alat kerja rusak agar segera diperbaiki.</p>
            </div>
                
            <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Pengaduan
            </a>
        </div>

        <!-- Action Card 2: Pengadaan Barang Baru (BARU ADDED) -->
        <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Butuh Barang Baru?</h3>
                <p class="text-slate-600 text-xs leading-relaxed mb-5">Ajukan usulan permohonan pengadaan barang atau fasilitas baru ke Pimpinan.</p>
            </div>
                
            <a href="{{ route('karyawan.pengadaan.create') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-emerald-600/20 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ajukan Barang Baru
            </a>
        </div>

        <!-- Stat Card 1: Total Laporan Pengaduan -->
        <a href="{{ route('laporan.index') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pengaduan</span>
                    <div class="p-2 bg-white text-indigo-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 02 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $laporanku->count() }}</h4>
                <p class="text-slate-500 text-xs mt-1">Pengaduan diajukan</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Lihat riwayat &rarr;
            </span>
        </a>

        <!-- Stat Card 2: Pengaduan Aktif -->
        <a href="{{ route('laporan.index', ['filter' => 'aktif']) }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Status Aktif</span>
                    <div class="p-2 bg-white text-indigo-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">
                    {{ $laporanku->where('status_laporan', '!=', 'Selesai')->count() }}
                </h4>
                <p class="text-slate-500 text-xs mt-1">Dalam proses perbaikan</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Filter aktif &rarr;
            </span>
        </a>
    </div>

    <!-- Log Aktivitas Terbaru -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-800">Aktivitas & Pembaruan Status</h3>
                <p class="text-slate-500 text-xs mt-0.5">Riwayat perubahan status terbaru dari laporan pengaduan fasilitas yang kamu ajukan.</p>
            </div>
            <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($logs as $activity)
                @php
                    $statusClasses = [
                        'Menunggu' => 'bg-rose-100 text-rose-700 border-rose-200',
                        'Diproses' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'Selesai'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'Ditolak'  => 'bg-slate-200 text-slate-700 border-slate-300',
                    ];

                    $badgeClass = $statusClasses[$activity->status_sekarang] ?? 'bg-indigo-100 text-indigo-700 border-indigo-200';
                    $isStatusChanged = isset($activity->status_sebelumnya) && $activity->status_sebelumnya !== $activity->status_sekarang;
                @endphp

                <a href="{{ route('laporan.show', $activity->id_laporan) }}#log-{{ $activity->id_log ?? $activity->id }}" 
                    class="block bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm hover:border-indigo-300 transition group cursor-pointer">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-start sm:items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-indigo-600 shrink-0 mt-1.5 sm:mt-0 group-hover:scale-125 transition-transform"></div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">
                                    Laporan Pengaduan untuk 
                                    <span class="text-indigo-600 font-bold group-hover:underline">
                                        {{ $activity->laporan->barang->nama_barang ?? 'Fasilitas Kantor' }}
                                    </span>
                                </p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    @if($isStatusChanged)
                                        Status berubah menjadi 
                                    @else
                                        Status: 
                                    @endif

                                    <span class="font-semibold px-2 py-0.5 rounded text-[10px] border {{ $badgeClass }}">
                                        {{ $activity->status_sekarang }}
                                    </span> 

                                    @if($activity->keterangan)
                                        • <span class="text-slate-600">{{ $activity->keterangan }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 self-end sm:self-center">
                            <span class="text-[10px] text-slate-500 font-medium whitespace-nowrap bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-1 group-hover:text-indigo-600 transition hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-8 bg-white/60 rounded-xl border border-dashed border-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-slate-500 text-xs">Belum ada pembaruan status laporan pengaduan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection