@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Halaman -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Riwayat Laporan Saya</h2>
            <p class="text-stone-700 text-sm mt-1">Kelola dan pantau seluruh laporan Pengaduan fasilitas kantor yang pernah kamu ajukan.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="bg-amber-100 hover:bg-amber-300 text-amber-900 font-semibold px-4 py-2.5 rounded-xl text-sm border border-amber-200 transition shadow-sm inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Laporan Baru
        </a>
    </div>
    <!-- Form Search Bar & Filter -->
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    
    <!-- Form Search -->
    <form action="{{ route('laporan.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
        <!-- Jika sedang aktif filter-nya, bawa juga saat melakukan search -->
        @if(request('filter') == 'aktif')
            <input type="hidden" name="filter" value="aktif">
        @endif
        
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan pengaduan..." class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-full md:w-64">
        
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
            Cari
        </button>

        @if(request('search') || request('filter'))
            <a href="{{ route('laporan.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 rounded-xl text-sm font-semibold transition flex items-center">
                Reset
            </a>
        @endif
    </form>

</div>

    <!-- Tabel Riwayat Laporan -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-300/80">
        <h3 class="text-lg font-bold text-stone-800 mb-4">Daftar Laporan</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-[rgb(255,222,112)] text-stone-800 border-b border-amber-300/60">
                        <th class="p-3.5 rounded-l-xl">Tanggal</th>
                        <th class="p-3.5">Barang</th>
                        <th class="p-3.5">Deskripsi Pengaduan</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-300/40">
                    @forelse($laporanku as $lap)
                        <tr class="hover:bg-amber-200/30 transition">
                            <!-- Kolom 1: Tanggal -->
                            <td class="p-3.5 text-stone-700">{{ $lap->created_at->format('d/m/Y') }}</td>
                            
                            <!-- Kolom 2: Barang -->
                            <td class="p-3.5 font-medium text-stone-900">{{ $lap->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                            
                            <!-- Kolom 3: Kerusakan -->
                            <td class="p-3.5 text-stone-700">{{ $lap->deskripsi_kerusakan }}</td>
                            
                            <!-- Kolom 4: Status -->
                            <td class="p-3.5">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($lap->status_laporan == 'Menunggu') bg-amber-100 text-amber-900 border border-amber-300
                                    @elseif($lap->status_laporan == 'Diproses') bg-amber-100 text-amber-900 border border-amber-300
                                    @elseif($lap->status_laporan == 'Dibatalkan') bg-stone-200 text-stone-700 border border-stone-300 line-through
                                    @else bg-amber-100 text-stone-700 border border-amber-300 @endif">
                                    {{ $lap->status_laporan }}
                                </span>
                            </td>

                            <!-- Kolom 5: Aksi (Detail, Edit & Batalkan) -->
                            <td class="p-3.5">
                                <div class="flex items-center gap-2">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('laporan.show', $lap->id_laporan ?? $lap->id) }}" class="bg-stone-100 text-stone-800 hover:bg-stone-200 px-3 py-1.5 rounded-lg text-xs font-semibold border border-stone-300 transition">
                                        Detail
                                    </a>

                                    @if($lap->status_laporan == 'Menunggu')
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('laporan.edit', $lap->id_laporan ?? $lap->id) }}" class="bg-amber-100 text-amber-900 hover:bg-amber-200 px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-200 transition">
                                            Edit
                                        </a>
                                        
                                        <!-- Tombol Batalkan / Hapus -->
                                        <form action="{{ route('laporan.destroy', $lap->id_laporan ?? $lap->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-800 hover:bg-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200 transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-stone-500 italic">Terkunci</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-stone-600">Belum ada riwayat laporan kerusakan yang dikirim.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection