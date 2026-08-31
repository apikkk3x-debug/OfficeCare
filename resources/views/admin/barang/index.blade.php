@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg flex justify-between items-center">
        <div>
            <span class="text-xs font-semibold bg-indigo-600 px-3 py-1 rounded-full uppercase">Manajemen Aset</span>
            <h1 class="text-2xl font-bold mt-2">Daftar Inventaris Sarpras Kantor</h1>
            <p class="text-slate-300 text-sm mt-1">Kelola data inventaris fasilitas kantor dengan sistem kode dan kategori terstruktur.</p>
        </div>
        <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-md">
            + Tambah Barang Baru
        </a>
    </div>

    <!-- Tabel Daftar Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4">Semua Aset Kantor</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs border-b">
                    <tr>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Kategori / Lokasi</th>
                        <th class="p-3">Kode / Detail</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($barangFasilitas as $item)
                        <tr>
                            <td class="p-3 font-semibold text-slate-800">{{ $item->nama_barang ?? $item->nama }}</td>
                            <td class="p-3 text-slate-600">{{ $item->lokasi ?? '-' }}</td>
                            <td class="p-3 text-slate-600">{{ $item->kode_barang ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <span class="text-xs text-indigo-600 font-medium">Kelola</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-6 text-slate-400">Belum ada data barang atau aset yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection