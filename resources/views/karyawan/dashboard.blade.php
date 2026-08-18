@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Sambutan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}. Pantau status laporan dan fasilitas kantor di sini.</p>
        </div>
        <span class="bg-blue-50 text-blue-700 font-semibold px-4 py-2 rounded-xl text-sm border border-blue-100">
            Karyawan Aktif
        </span>
    </div>

    <!-- Bagian Atas: Banner Ajukan Kerusakan & Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Banner Interaktif Menuju Form Input -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 rounded-2xl shadow-sm text-white flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold mb-2">Ada Fasilitas Rusak?</h3>
                <p class="text-blue-100 text-sm mb-4">Laporkan kerusakan AC, lampu, proyektor, atau fasilitas kantor lainnya agar segera ditangani.</p>
            </div>
            <a href="{{ route('laporan.create') }}" class="inline-flex items-center justify-center bg-white text-blue-700 hover:bg-blue-50 font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm">
                + Ajukan Kerusakan atau Keluhan
            </a>
        </div>

        <!-- Ringkasan Total Laporan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-center">
            <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Laporan Anda</span>
            <h4 class="text-3xl font-bold text-slate-800 mt-1">{{ count($laporanku) }}</h4>
            <p class="text-slate-500 text-xs mt-1">Laporan yang pernah diajukan</p>
        </div>

        <!-- Ringkasan Laporan Aktif/Dalam Proses -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-center">
            <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Status Aktif</span>
            <h4 class="text-3xl font-bold text-emerald-600 mt-1">
                {{ $laporanku->where('status_laporan', '!=', 'Selesai')->count() }}
            </h4>
            <p class="text-slate-500 text-xs mt-1">Laporan dalam penanganan</p>
        </div>
    </div>

    <!-- Tabel Riwayat Laporan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Riwayat Laporan Saya</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <th class="p-3.5 rounded-l-xl">Tanggal</th>
                        <th class="p-3.5">Barang</th>
                        <th class="p-3.5">Kerusakan</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporanku as $lap)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Kolom 1: Tanggal -->
                            <td class="p-3.5 text-slate-600">{{ $lap->created_at->format('d/m/Y') }}</td>
                            
                            <!-- Kolom 2: Barang -->
                            <td class="p-3.5 font-medium text-slate-800">{{ $lap->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                            
                            <!-- Kolom 3: Kerusakan -->
                            <td class="p-3.5 text-slate-600">{{ $lap->deskripsi_kerusakan }}</td>
                            
                            <!-- Kolom 4: Status -->
                            <td class="p-3.5">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($lap->status_laporan == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($lap->status_laporan == 'Diproses') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-emerald-50 text-emerald-700 border border-emerald-200 @endif">
                                    {{ $lap->status_laporan }}
                                </span>
                            </td>

                            <!-- Kolom 5: Aksi (Edit & Batalkan) -->
                            <td class="p-3.5">
                                @if($lap->status_laporan == 'Menunggu')
                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('laporan.edit', $lap->id_laporan ?? $lap->id) }}" class="bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-200 transition">
                                            Edit
                                        </a>
                                        
                                        <!-- Tombol Batalkan / Hapus -->
                                        <form action="{{ route('laporan.destroy', $lap->id_laporan ?? $lap->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200 transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada laporan kerusakan yang dikirim.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection