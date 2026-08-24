@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Sambutan -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Dashboard Karyawan</h2>
            <p class="text-stone-700 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}. Pantau status laporan pengaduan fasilitas kantor di sini.</p>
        </div>
        <span class="bg-amber-100 text-amber-900 font-semibold px-4 py-2 rounded-xl text-sm border border-amber-200">
            Karyawan Aktif
        </span>
    </div>

    <!-- Bagian Atas: Banner Ajukan Kerusakan & Statistik Ringkas (Interaktif & Beda Fungsi) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Banner Interaktif Menuju Form Input -->
        <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl border border-amber-400/60 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-stone-800 mb-2">Ada Fasilitas Rusak?</h3>
                <p class="text-stone-700 text-sm mb-6">Laporkan pengaduan terkait AC, lampu, proyektor, atau fasilitas kantor lainnya agar segera ditangani.</p>
            </div>
                
            <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center gap-2 bg-amber-100 hover:bg-amber-300 text-amber-900 font-semibold px-5 py-2.5 rounded-xl text-sm transition shadow-sm border border-amber-200 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Pengaduan
            </a>
        </div>

        <!-- Ringkasan Total Laporan (Fungsi: Menuju Semua Riwayat) -->
        <a href="{{ route('laporan.index') }}" class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex flex-col justify-between transition hover:border-amber-500 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-stone-700 text-xs font-semibold uppercase tracking-wider">Total Laporan Pengaduan  Anda</span>
                    <div class="p-2 bg-amber-200/60 rounded-xl text-amber-900 group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-stone-900 mt-1">{{ count($laporanku) }}</h4>
                <p class="text-stone-700 text-xs mt-1">Laporan pengaduan yang pernah diajukan</p>
            </div>
            <span class="text-xs font-medium text-amber-900 mt-4 flex items-center gap-1">
                Lihat semua riwayat pengaduan &rarr;
            </span>
        </a>

        <!-- Ringkasan Laporan Aktif (Fungsi: Memfilter Khusus Laporan Aktif) -->
        <a href="{{ route('laporan.index', ['filter' => 'aktif']) }}" class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/60 flex flex-col justify-between transition hover:border-amber-500 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-stone-700 text-xs font-semibold uppercase tracking-wider">Status Aktif</span>
                    <div class="p-2 bg-amber-200/60 rounded-xl text-amber-900 group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-stone-900 mt-1">
                    {{ $laporanku->where('status_laporan', '!=', 'Selesai')->count() }}
                </h4>
                <p class="text-stone-700 text-xs mt-1">Laporan pengaduan dalam penanganan</p>
            </div>
            <span class="text-xs font-medium text-amber-900 mt-4 flex items-center gap-1">
                Filter laporan pengaduan aktif &rarr;
            </span>
        </a>
    </div>

    <!-- Bagian Aktivitas / Log Terbaru (Activity Feed) -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-stone-800">Aktivitas & Pembaruan Status Pengaduan</h3>
                <p class="text-stone-700 text-sm mt-0.5">Riwayat perubahan status terbaru dari laporan pengaduan fasilitas yang kamu ajukan.</p>
            </div>
            <div class="p-2 bg-amber-200/70 rounded-xl text-amber-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            @php
                $latestActivities = \App\Models\LaporanLog::whereHas('laporan', function($query) {
                    $query->where('id_user', Auth::id());
                })->with('laporan')->latest()->take(3)->get();
            @endphp

            @forelse($latestActivities as $activity)
                <div class="bg-white/70 p-4 rounded-xl border border-amber-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-stone-900">
                                Laporan Pengaduan untuk <span class="text-amber-900 underline">{{ $activity->laporan->barang->nama_barang ?? 'Fasilitas Kantor' }}</span>
                            </p>
                            <p class="text-xs text-stone-600 mt-0.5">
                                Status berubah menjadi <span class="bg-amber-100 text-amber-900 font-bold px-1.5 py-0.5 rounded">{{ $activity->status_sekarang }}</span> • {{ $activity->keterangan }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs text-stone-500 font-medium whitespace-nowrap bg-amber-100/60 px-2.5 py-1 rounded-lg border border-amber-200">
                        {{ $activity->created_at->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="text-center py-6 bg-white/40 rounded-xl border border-dashed border-amber-300">
                    <p class="text-stone-600 text-sm">Belum ada pembaruan status laporan pengaduan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection