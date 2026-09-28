@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Sambutan Utama -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-700/60 rounded-2xl p-6 shadow-sm text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-[10px] font-semibold tracking-wider uppercase rounded-md">
                    Portal Karyawan
                </span>
                <span class="text-xs text-slate-400 font-medium">OfficeCare System</span>
            </div>
            
            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-wide">
                Selamat Datang, {{ ucwords(Auth::user()->nama ?? Auth::user()->name) }}
            </h2>
            
            <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">
                Kelola pengaduan fasilitas operasional kantor dan ajukan permohonan pengadaan inventaris secara terintegrasi.
            </p>
        </div>
        
        <div class="bg-white/5 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-xl text-xs border border-white/10 shrink-0 shadow-xs">
            Karyawan Aktif
        </div>
    </div>

    <!-- Quick Action Cards (Aksi Cepat) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Action Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Pengaduan Kerusakan Fasilitas</h3>
                <p class="text-slate-500 text-xs leading-relaxed mb-5">Laporkan kendala perangkat kerja seperti AC, pencahayaan, proyektor, atau fasilitas kantor lainnya.</p>
            </div>
                
            <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-xs w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Pengaduan Baru
            </a>
        </div>

        <!-- Action Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Pengadaan Barang & Inventaris</h3>
                <p class="text-slate-500 text-xs leading-relaxed mb-5">Ajukan proposal permohonan pengadaan barang atau fasilitas penunjang kerja baru kepada manajemen.</p>
            </div>
                
            <a href="{{ route('karyawan.pengadaan.create') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-xs w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ajukan Pengadaan Barang
            </a>
        </div>

    </div>

    <!-- Ringkasan Statistik Interaktif (4 Kolom Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Stat Card 1 -->
        <a href="{{ route('laporan.index') }}" class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between transition hover:border-indigo-300 hover:shadow-sm group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pengaduan</span>
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-slate-800 mt-3">{{ $laporanku->count() }}</h4>
                <p class="text-slate-400 text-xs mt-1">Keseluruhan riwayat</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Lihat riwayat &rarr;
            </span>
        </a>

        <!-- Stat Card 2 -->
        <a href="{{ route('laporan.index', ['filter' => 'aktif']) }}" class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between transition hover:border-amber-300 hover:shadow-sm group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Perbaikan Aktif</span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-amber-600 mt-3">
                    {{ $laporanku->where('status_laporan', '!=', 'Selesai')->count() }}
                </h4>
                <p class="text-slate-400 text-xs mt-1">Dalam penanganan</p>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Filter aktif &rarr;
            </span>
        </a>

        <!-- Stat Card 3 -->
        <a href="{{ route('karyawan.pengadaan.index') }}" class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between transition hover:border-emerald-300 hover:shadow-sm group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Pengadaan Barang</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-slate-800 mt-3">
                    {{ $pengadaanBarang ?? 0 }}
                </h4>
                <p class="text-slate-400 text-xs mt-1">Usulan diajukan</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Daftar pengadaan &rarr;
            </span>
        </a>

        <!-- Stat Card 4 -->
        <a href="{{ route('karyawan.pengadaan.index') }}" class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between transition hover:border-blue-300 hover:shadow-sm group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Disetujui Manajemen</span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-blue-600 mt-3">
                    {{ $pengadaanDisetujui ?? 0 }}
                </h4>
                <p class="text-slate-400 text-xs mt-1">Telah disetujui</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Lihat daftar &rarr;
            </span>
        </a>

    </div>

    <!-- SECTION BAWAH: GRID 2 KOLOM SEIMBANG -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        
        <!-- KOLOM KIRI: Widget Pengumuman Terbaru -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-800"></span>
                        Pengumuman & Informasi Kantor
                    </h3>
                    <span class="text-[11px] text-slate-400 font-medium">Internal</span>
                </div>

                <!-- Kontainer Daftar Pengumuman -->
                <div id="pengumuman-list" class="space-y-3">
                    @forelse($pengumumans ?? [] as $p)
                        <!-- Diberikan ID unik agar target notifikasi dapat langsung mengarah ke sini -->
                        <div id="pengumuman-item-{{ $p->id }}" class="pengumuman-item bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/25 rounded-xl p-3.5 shadow-xs flex items-center justify-between text-slate-800 transition-all duration-500" data-id="{{ $p->id }}">
                            
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 bg-amber-500/20 text-amber-800 text-[10px] font-bold tracking-wider uppercase rounded-md">
                                            {{ $p->target_role === 'semua' ? 'Pengumuman Umum' : 'Info Sarpras' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $p->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $p->judul }}</h4>
                                    <p class="text-xs font-medium text-slate-600 mt-0.5 leading-relaxed">
                                        {{ $p->pesan }}
                                    </p>
                                </div>
                            </div>

                            <!-- Tombol Tutup Pengumuman -->
                            <button type="button" onclick="dismissPengumuman('{{ $p->id }}')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-amber-500/10 transition cursor-pointer shrink-0 ml-3" title="Tutup Pengumuman">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 px-4 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs font-medium text-slate-500">Belum ada informasi pengumuman aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- State Kosong Jika Ditutup -->
                <div id="empty-pengumuman-state" class="text-center py-8 px-4 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 hidden">
                    <p class="text-xs font-medium text-slate-500">Belum ada informasi pengumuman aktif saat ini.</p>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Log Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        Log Riwayat & Status Laporan
                    </h3>
                    <span class="text-[11px] text-slate-400 font-medium">Aktivitas Sistem</span>
                </div>

                <div class="space-y-3">
                    @forelse($logs ?? [] as $activity)
                        @php
                            $statusClasses = [
                                'Menunggu' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'Diproses' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Ditolak'  => 'bg-slate-100 text-slate-700 border-slate-200',
                            ];

                            $badgeClass = $statusClasses[$activity->status_sekarang] ?? 'bg-indigo-50 text-indigo-700 border-indigo-100';
                            $isStatusChanged = isset($activity->status_sebelumnya) && $activity->status_sebelumnya !==$activity->status_sekarang;
                        @endphp

                        <a href="{{ route('laporan.show', $activity->id_laporan) }}#log-{{ $activity->id_log ?? $activity->id }}" 
                            class="block bg-slate-50/60 p-3.5 rounded-xl border border-slate-200/60 hover:border-indigo-200 hover:bg-white transition group cursor-pointer">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-2 h-2 rounded-full bg-indigo-600 shrink-0 mt-1.5 group-hover:scale-110 transition-transform"></div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">
                                            Laporan 
                                            <span class="text-indigo-600 font-bold group-hover:underline">
                                                {{ $activity->laporan->barang->nama_barang ?? 'Fasilitas Kantor' }}
                                            </span>
                                        </p>
                                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                            @if($isStatusChanged)
                                                Status pembaruan menjadi 
                                            @else
                                                Status: 
                                            @endif

                                            <span class="font-semibold px-1.5 py-0.5 rounded text-[10px] border {{ $badgeClass }}">
                                                {{ $activity->status_sekarang }}
                                            </span> 

                                            @if($activity->keterangan)
                                                • <span class="text-slate-600">{{ $activity->keterangan }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap bg-white px-2 py-0.5 rounded-md border border-slate-200 shrink-0">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-slate-500 text-xs font-medium">Belum ada riwayat aktivitas terbaru saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Logika Dismiss Pengumuman via LocalStorage
        let dismissedIds = JSON.parse(localStorage.getItem('dismissed_pengumuman') || '[]');
        let items = document.querySelectorAll('.pengumuman-item');
        
        items.forEach(item => {
            let id = item.getAttribute('data-id');
            if (dismissedIds.includes(id)) {
                item.style.display = 'none';
            }
        });

        checkEmptyPengumumanState();

        // 2. Logika Auto-Scroll & Highlight Berdasarkan Query Parameter (?highlight_pengumuman=X)
        const urlParams = new URLSearchParams(window.location.search);
        const highlightId = urlParams.get('highlight_pengumuman');

        if (highlightId) {
            const targetElement = document.getElementById('pengumuman-item-' + highlightId);
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    targetElement.classList.add('ring-2', 'ring-amber-500', 'shadow-md', 'scale-[1.01]');
                    
                    setTimeout(() => {
                        targetElement.classList.remove('ring-2', 'ring-amber-500', 'shadow-md', 'scale-[1.01]');
                    }, 3500);
                }, 250);
            }
        }
    });

    function dismissPengumuman(id) {
        let item = document.querySelector(`.pengumuman-item[data-id="${id}"]`);
        if (item) {
            item.style.opacity = '0';
            setTimeout(() => {
                item.style.display = 'none';
                checkEmptyPengumumanState();
            }, 300);
        }

        let dismissedIds = JSON.parse(localStorage.getItem('dismissed_pengumuman') || '[]');
        if (!dismissedIds.includes(id)) {
            dismissedIds.push(id);
            localStorage.setItem('dismissed_pengumuman', JSON.stringify(dismissedIds));
        }
    }

    function checkEmptyPengumumanState() {
        let items = document.querySelectorAll('.pengumuman-item');
        let visibleCount = 0;

        items.forEach(item => {
            if (item.style.display !== 'none') {
                visibleCount++;
            }
        });

        let emptyState = document.getElementById('empty-pengumuman-state');
        if (visibleCount === 0 && emptyState) {
            emptyState.classList.remove('hidden');
        }
    }
</script>
@endsection