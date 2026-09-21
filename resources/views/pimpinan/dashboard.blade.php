@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ assetModalOpen: false, searchAsset: '' }">
    
    <!-- Header Pimpinan Executive Banner (Selaras dengan Admin) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-6 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Pimpinan • Executive Command Center
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Dashboard Pengawasan Pimpinan</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pusat kendali persetujuan pengadaan, monitoring aset, dan pengawasan fasilitas kantor secara real-time.</p>
        </div>
        <span class="bg-white/10 backdrop-blur-md text-indigo-200 font-medium px-4 py-2 rounded-xl text-xs border border-white/10 shrink-0 shadow-sm">
            Role: Pimpinan / Manager
        </span>
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

    <!-- Pengumuman / Info Eksekutif (Jika Ada) -->
    @foreach($pengumumans as $p)
    <div x-data="{ showAnnouncement: true }" 
         x-show="showAnnouncement" 
         class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 shadow-sm flex items-center justify-between text-slate-800">
        <div class="flex items-start gap-3">
            <div class="p-2.5 bg-amber-500 text-white rounded-xl shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold tracking-wider uppercase rounded">Info Eksekutif</span>
                    <span class="text-[11px] text-slate-400">{{ $p->created_at->diffForHumans() }}</span>
                </div>
                <h4 class="text-xs font-bold text-slate-900">{{ $p->judul }}</h4>
                <p class="text-xs text-slate-600 mt-0.5">{{ $p->pesan }}</p>
            </div>
        </div>
        <button @click="showAnnouncement = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-amber-100/50 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endforeach

    <!-- 6 Kartu Statistik Eksekutif (Grid 3 Kolom - Seragam dengan Standar Admin) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        
        <!-- Card 1: Persetujuan Pengadaan (Fokus Utama Keputusan Pimpinan) -->
        <a href="{{ route('pimpinan.pengadaan.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-purple-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Persetujuan Pengadaan</span>
                    <div class="p-2.5 bg-purple-50/60 text-purple-600 rounded-xl shadow-sm border border-purple-200/60 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-purple-600 mt-3">{{ $pengadaanMenunggu ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Pengajuan menunggu tanda tangan</p>
            </div>
            <span class="text-xs font-semibold text-purple-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Proses sekarang &rarr;
            </span>
        </a>

        <!-- Card 2: Inventaris Aset (Memicu Modal Live Search) -->
        <button @click="assetModalOpen = true" type="button" class="text-left bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-emerald-400 hover:shadow-md group cursor-pointer w-full">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Inventaris Aset</span>
                    <div class="p-2.5 bg-emerald-50/60 text-emerald-600 rounded-xl shadow-sm border border-emerald-200/60 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $totalBarang ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total unit fasilitas aktif terdaftar</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Lihat daftar aset &rarr;
            </span>
        </button>

        <!-- Card 3: Operasional (Laporan Diproses) -->
        <a href="{{ route('pimpinan.rekap', ['status' => 'Diproses']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-blue-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Operasional Tim</span>
                    <div class="p-2.5 bg-blue-50/60 text-blue-600 rounded-xl shadow-sm border border-blue-200/60 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-blue-600 mt-3">{{ $laporanDiproses ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Laporan dalam pengerjaan teknisi</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Pantau progres &rarr;
            </span>
        </a>

        <!-- Card 4: Laporan Menunggu -->
        <a href="{{ route('pimpinan.rekap', ['status' => 'Menunggu']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-amber-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Laporan Menunggu</span>
                    <div class="p-2.5 bg-amber-50/60 text-amber-600 rounded-xl shadow-sm border border-amber-200/60 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $laporanMenunggu ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Laporan baru belum ditangani</p>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Tinjau laporan &rarr;
            </span>
        </a>

        <!-- Card 5: Performa Selesai -->
        <a href="{{ route('pimpinan.rekap', ['status' => 'Selesai']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Performa Selesai</span>
                    <div class="p-2.5 bg-indigo-50/60 text-indigo-600 rounded-xl shadow-sm border border-indigo-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-indigo-600 mt-3">{{ $laporanSelesai ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Perbaikan fasilitas tuntas</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Lihat riwayat &rarr;
            </span>
        </a>

        <!-- Card 6: Rekap & Cetak Laporan PDF -->
        <a href="{{ route('pimpinan.rekap') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-slate-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Dokumentasi</span>
                    <div class="p-2.5 bg-slate-50 text-slate-700 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-slate-800 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $totalLaporan ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total dokumen laporan resmi</p>
            </div>
            <span class="text-xs font-semibold text-slate-700 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Buka menu cetak &rarr;
            </span>
        </a>

    </div>

    <!-- Quick Action Footer (Selaras dengan Admin) -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Pusat Keputusan Eksekutif</h3>
            <p class="text-slate-500 text-xs mt-0.5">Seluruh persetujuan anggaran pengadaan dan pemantauan fasilitas kantor terpusat secara transparan.</p>
        </div>
        <a href="{{ route('pimpinan.pengadaan.index') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 shrink-0">
            <span>Tinjau Persetujuan Pengadaan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- ================= MODAL DAFTAR ASET (DENGAN LIVE SEARCH) ================= -->
    <div x-show="assetModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="assetModalOpen = false" 
             class="bg-white rounded-3xl shadow-xl max-w-2xl w-full p-6 space-y-4 text-left border border-slate-200 max-h-[85vh] flex flex-col">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Daftar Inventaris & Aset Kantor Terdaftar
                </h3>
                <button @click="assetModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-2xl hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div>
                <input type="text" 
                       x-model="searchAsset" 
                       placeholder="Cari nama barang atau aset..." 
                       class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="overflow-y-auto flex-1 pr-1 max-h-[50vh]">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase">
                            <th class="py-2 px-3">Nama Barang / Aset</th>
                            <th class="py-2 px-3">Kategori</th>
                            <th class="py-2 px-3">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($daftarBarang as $barang)
                        <tr x-show="searchAsset === '' || '{{ strtolower($barang->nama_barang ?? $barang->nama ?? '') }}'.includes(searchAsset.toLowerCase())">
                            <td class="py-2.5 px-3 font-bold text-slate-900">{{ $barang->nama_barang ?? $barang->nama ?? 'Barang Kantor' }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-bold uppercase">
                                    {{ $barang->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-md text-[10px] font-bold">
                                    {{ $barang->kondisi ?? 'Baik' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400">Belum ada data aset terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400">
                <span>Total keseluruhan: {{ count($daftarBarang) }} unit terdaftar</span>
                <button @click="assetModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-2xl text-xs transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection