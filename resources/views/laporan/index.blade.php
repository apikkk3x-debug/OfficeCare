@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Banner Header Karyawan -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Karyawan • Sarpras System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Riwayat Laporan Saya</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola dan pantau seluruh laporan pengaduan fasilitas kantor yang pernah kamu ajukan.</p>
        </div>
        
        <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-indigo-600/20 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

    <!-- Tabel Data Laporan Lengkap (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Laporan Saya</h3>
            
            <form method="GET" action="{{ route('laporan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                @if(request('prioritas')) <input type="hidden" name="prioritas" value="{{ request('prioritas') }}"> @endif
                @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-600 cursor-pointer shadow-sm">
                    <option value="10" {{ (isset($perPage) && $perPage == 10) || request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="30" {{ (isset($perPage) && $perPage == 30) || request('per_page') == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ (isset($perPage) && $perPage == 50) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (isset($perPage) && $perPage == 100) || request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data</span>
            </form>
        </div>

        <!-- ================= BARIS FILTER & PENCARIAN (GRID SEARCH, STATUS, & PRIORITAS) ================= -->
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pb-2 border-b border-slate-200/60">
            @if(request()->has('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif
            @if(request()->has('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <!-- 1. Input Pencarian Teks -->
            <div class="sm:col-span-5">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama barang, lokasi, deskripsi..." 
                       class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm">
            </div>

            <!-- 2. Dropdown Filter Status -->
            <div class="sm:col-span-3">
                <select name="status" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 shadow-sm cursor-pointer focus:outline-none focus:border-indigo-600">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- 3. Dropdown Filter Prioritas -->
            <div class="sm:col-span-2">
                <select name="prioritas" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 shadow-sm cursor-pointer focus:outline-none focus:border-indigo-600">
                    <option value="">-- Prioritas --</option>
                    <option value="Darurat" {{ request('prioritas') == 'Darurat' ? 'selected' : '' }}>Darurat</option>
                    <option value="Sedang" {{ request('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>

            <!-- 4. Tombol Cari & Reset Filter -->
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm cursor-pointer py-2">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status', 'prioritas', 'filter']))
                    <a href="{{ route('laporan.index') }}" title="Reset Filter" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>
        
        <!-- Wrapper Tabel Data -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                    <tr>
                        <th class="p-3.5 font-bold">Tanggal</th>
                        <th class="p-3.5 font-bold">Barang & Lokasi</th>
                        <th class="p-3.5 font-bold">Deskripsi Kerusakan</th>
                        <th class="p-3.5 font-bold text-center">Status & Prioritas</th>
                        <th class="p-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($laporanku as $item)
                        @php
                            $tanggal = $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-';
                            $barang = $item->barang->nama_barang ?? 'Barang Dihapus';
                            $lokasi = $item->barang->lokasi ?? '-';
                            $deskripsi = $item->deskripsi_kerusakan ?? '-';
                            $status = $item->status_laporan ?? 'Menunggu';
                            $prioritas = $item->prioritas ?? 'Sedang';
                            $idLaporan = $item->id_laporan ?? $item->id;
                        @endphp
                        
                        <tr id="laporan-{{ $idLaporan }}" class="hover:bg-slate-50/80 transition">
                            
                            <!-- Kolom 1: Tanggal -->
                            <td class="p-3.5 font-medium text-slate-600 whitespace-nowrap">
                                {{ $tanggal }}
                            </td>

                            <!-- Kolom 2: Barang & Lokasi -->
                            <td class="p-3.5">
                                <div class="font-semibold text-slate-800 truncate max-w-[180px]" title="{{ $barang }}">
                                    {{ \Illuminate\Support\Str::limit($barang, 25, '...') }}
                                </div>
                                <div class="text-[10px] text-slate-500 truncate max-w-[180px]" title="Lokasi: {{ $lokasi }}">
                                    Lokasi: {{ $lokasi }}
                                </div>
                            </td>

                            <!-- Kolom 3: Deskripsi Kerusakan -->
                            <td class="p-3.5">
                                <p class="text-slate-600 truncate max-w-[240px]" title="{{ $deskripsi }}">
                                    {{ \Illuminate\Support\Str::limit($deskripsi, 40, '...') }}
                                </p>
                            </td>

                            <!-- Kolom 4: Badge Status & Prioritas -->
                            <td class="p-3.5 text-center whitespace-nowrap space-y-1">
                                <!-- Status Badge -->
                                <div>
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full border inline-block
                                        @if($status == 'Selesai') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @elseif($status == 'Diproses') bg-indigo-50 text-indigo-700 border-indigo-200
                                        @elseif($status == 'Dialihkan ke Pengadaan') bg-purple-50 text-purple-700 border-purple-200
                                        @elseif($status == 'Dibatalkan' || $status == 'Ditolak') bg-slate-100 text-slate-500 border-slate-200 line-through
                                        @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                        {{ $status }}
                                    </span>
                                </div>

                                <!-- Prioritas Badge -->
                                <div>
                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-md border inline-flex items-center gap-1
                                        @if($prioritas == 'Darurat' || $prioritas == 'Tinggi') bg-red-50 text-red-700 border-red-200 animate-pulse
                                        @elseif($prioritas == 'Sedang') bg-amber-50 text-amber-700 border-amber-200
                                        @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                                        @if($prioritas == 'Darurat' || $prioritas == 'Tinggi') 🚨 @elseif($prioritas == 'Sedang') ⚠️ @else ℹ️ @endif {{ $prioritas }}
                                    </span>
                                </div>
                            </td>

                            <!-- Kolom 5: Aksi (Alpine Dropdown Menu) -->
                            <td class="p-3.5 whitespace-nowrap text-center align-middle">
                                <div class="relative inline-flex items-center justify-center text-left" x-data="{ open: false }">
                                    <button @click="open = !open" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu Pop-up -->
                                    <div x-show="open" 
                                         @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-full mr-2 top-1/2 -translate-y-1/2 w-32 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left" 
                                         style="display: none;">
                                        
                                        <a href="{{ route('laporan.show', $idLaporan) }}" class="block px-3 py-2 hover:bg-slate-50 text-slate-700 font-medium">
                                            Detail
                                        </a>

                                        @if($status == 'Menunggu')
                                            <a href="{{ route('laporan.edit', $idLaporan) }}" class="block px-3 py-2 hover:bg-indigo-50 text-indigo-600 font-semibold">
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('laporan.destroy', $idLaporan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-rose-50 text-rose-600 font-semibold cursor-pointer">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <div class="px-3 py-2 text-slate-400 italic text-[11px] select-none">
                                                Terkunci
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-8 text-slate-400">
                                Belum ada data riwayat laporan pengaduan yang sesuai dengan kriteria pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi & Keterangan Jumlah Data -->
        @if(method_exists($laporanku, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $laporanku->firstItem() ?? 0 }} sampai {{ $laporanku->lastItem() ?? 0 }} dari total {{ $laporanku->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $laporanku->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- SCRIPT JAVASCRIPT UNTUK AUTO-HIDE NOTIFIKASI -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.remove();
            }, 500);
        }, 3000);
    }

    const errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        setTimeout(function() {
            errorAlert.style.opacity = '0';
            setTimeout(function() {
                errorAlert.remove();
            }, 4000);
        }, 4000);
    }
});
</script>
@endsection