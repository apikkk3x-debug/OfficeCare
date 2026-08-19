@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Halaman -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">Riwayat Laporan Saya</h2>
            <p class="text-stone-700 text-sm mt-1">Kelola dan pantau seluruh laporan kerusakan fasilitas kantor yang pernah kamu ajukan.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="bg-amber-100 hover:bg-amber-300 text-amber-900 font-semibold px-4 py-2.5 rounded-xl text-sm border border-amber-200 transition shadow-sm inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Laporan Baru
        </a>
    </div>

    <!-- Tabel Riwayat Laporan -->
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-300/80">
        <h3 class="text-lg font-bold text-stone-800 mb-4">Daftar Semua Laporan</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-[rgb(255,222,112)] text-stone-800 border-b border-amber-300/60">
                        <th class="p-3.5 rounded-l-xl">Tanggal</th>
                        <th class="p-3.5">Barang</th>
                        <th class="p-3.5">Kerusakan</th>
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
                                    @else bg-amber-100 text-stone-700 border border-amber-300 @endif">
                                    {{ $lap->status_laporan }}
                                </span>
                            </td>

                            <!-- Kolom 5: Aksi (Edit & Batalkan) -->
                            <td class="p-3.5">
                                @if($lap->status_laporan == 'Menunggu')
                                    <div class="flex items-center gap-2">
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
                                    </div>
                                @else
                                    <span class="text-xs text-stone-500 italic">Terkunci</span>
                                @endif
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