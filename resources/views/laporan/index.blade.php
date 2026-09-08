@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- NOTIFIKASI BERHASIL / ERROR -->
    @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold transition-opacity duration-500 shadow-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold transition-opacity duration-500 shadow-sm flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Halaman (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-400/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Riwayat Pengaduan
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Riwayat Laporan Saya</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola dan pantau seluruh laporan pengaduan fasilitas kantor yang pernah kamu ajukan.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-indigo-600/20 transition inline-flex items-center gap-2 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Laporan Baru
        </a>
    </div>

    <!-- Form Search Bar & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Form Search -->
        <form action="{{ route('laporan.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            @if(request('filter') == 'aktif')
                <input type="hidden" name="filter" value="aktif">
            @endif
            
            <!-- Pertahankan nilai per_page saat melakukan pencarian -->
            @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan pengaduan..." class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-xs bg-white text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 w-full md:w-64 shadow-sm">
            
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-md shadow-indigo-600/20">
                Cari
            </button>

            @if(request('search') || request('filter') || request('per_page'))
                <a href="{{ route('laporan.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Riwayat Laporan -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Per Page -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-2 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Laporan</h3>
            
            <!-- Form Dropdown Pilihan Jumlah Baris -->
            <form method="GET" action="{{ route('laporan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif

                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-600 cursor-pointer shadow-sm">
                    @foreach([10, 25, 50, 100] as $value)
                        <option value="{{ $value }}" {{ (isset($perPage) && $perPage == $value) ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                <span>data</span>
            </form>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                        <th class="p-3.5 font-bold">Tanggal</th>
                        <th class="p-3.5 font-bold">Barang</th>
                        <th class="p-3.5 font-bold">Deskripsi Pengaduan</th>
                        <th class="p-3.5 font-bold">Status</th>
                        <th class="p-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80">
                    @forelse($laporanku as $lap)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Kolom 1: Tanggal -->
                            <td class="p-3.5 text-slate-600 whitespace-nowrap">{{ $lap->created_at->format('d/m/Y') }}</td>
                            
                            <!-- Kolom 2: Barang -->
                            <td class="p-3.5 font-semibold text-slate-800 whitespace-nowrap">{{ $lap->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                            
                            <!-- Kolom 3: Kerusakan (Ringkas dengan titik-titik) -->
                            <td class="p-3.5 text-slate-600 max-w-[220px]">
                                <div class="truncate" title="{{ $lap->deskripsi_kerusakan }}">
                                    {{ \Illuminate\Support\Str::limit($lap->deskripsi_kerusakan, 35, '...') }}
                                </div>
                            </td>
                            
                            <!-- Kolom 4: Status -->
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-block
                                    @if($lap->status_laporan == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($lap->status_laporan == 'Diproses') bg-indigo-50 text-indigo-700 border border-indigo-200
                                    @elseif($lap->status_laporan == 'Dibatalkan') bg-slate-100 text-slate-500 border border-slate-200 line-through
                                    @else bg-emerald-50 text-emerald-700 border border-emerald-200 @endif">
                                    {{ $lap->status_laporan }}
                                </span>
                            </td>

                            <!-- Kolom 5: Aksi (Menu Titik 3 / Dropdown Alpine.js) -->
                            <td class="p-3.5 whitespace-nowrap text-center">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
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
                                         class="absolute right-0 mt-1 w-32 bg-white border border-slate-200 rounded-xl shadow-lg z-30 py-1 text-left" 
                                         style="display: none;">
                                        
                                        <a href="{{ route('laporan.show', $lap->id_laporan ?? $lap->id) }}" class="block px-3 py-2 hover:bg-slate-50 text-slate-700 font-medium">
                                            Detail
                                        </a>

                                        @if($lap->status_laporan == 'Menunggu')
                                            <a href="{{ route('laporan.edit', $lap->id_laporan ?? $lap->id) }}" class="block px-3 py-2 hover:bg-indigo-50 text-indigo-600 font-semibold">
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('laporan.destroy', $lap->id_laporan ?? $lap->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-rose-50 text-rose-600 font-semibold">
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
                            <td colspan="5" class="p-8 text-center text-slate-500">Belum ada riwayat laporan kerusakan yang dikirim.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi Navigasi Bawah -->
        @if(method_exists($laporanku, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $laporanku->firstItem() ?? 0 }} sampai {{ $laporanku->lastItem() ?? 0 }} dari total {{ $laporanku->total() }} data
                </div>
                <div>
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
            }, 500);
        }, 4000);
    }
});
</script>
@endsection