@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Pimpinan</h2>
            <p class="text-slate-500 text-sm mt-1">Monitoring rekapitulasi kerusakan fasilitas kantor dan laporan operasional.</p>
        </div>
        <span class="bg-emerald-100 text-emerald-700 font-semibold px-4 py-2 rounded-lg text-sm">
            Pimpinan / Manager
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Aset Barang</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalBarang }} <span class="text-xs font-normal text-slate-500">Unit</span></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Ditanggapi</p>
            <p class="text-2xl font-bold text-amber-700 mt-2">{{ $laporanMenunggu }} <span class="text-xs font-normal text-slate-500">Laporan</span></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Sedang Diproses</p>
            <p class="text-2xl font-bold text-blue-700 mt-2">{{ $laporanDiproses }} <span class="text-xs font-normal text-slate-500">Laporan</span></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Selesai Diperbaiki</p>
            <p class="text-2xl font-bold text-green-700 mt-2">{{ $laporanSelesai }} <span class="text-xs font-normal text-slate-500">Laporan</span></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Laporan Kerusakan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Data bersifat informatif untuk monitoring kinerja penanganan sarana kantor.</p>
            </div>
            <a href="{{ route('pimpinan.laporan.cetak') }}" target="_blank" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 cursor-pointer shadow-sm">
                🖨️ Cetak Rekapitulasi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 border-b">
                        <th class="p-3">No</th>
                        <th class="p-3">Tanggal Laporan</th>
                        <th class="p-3">Pelapor</th>
                        <th class="p-3">Nama Barang & Lokasi</th>
                        <th class="p-3">Deskripsi Kerusakan</th>
                        <th class="p-3">Status Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($laporan as $index => $lap)
                        <tr>
                            <td class="p-3 text-slate-600">{{ $index + 1 }}</td>
                            <td class="p-3 text-slate-600">{{ $lap->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 font-medium text-slate-800">{{ $lap->user->name ?? '-' }}</td>
                            <td class="p-3 text-slate-600">
                                <div class="font-medium text-slate-800">{{ $lap->barang->nama_barang ?? '-' }}</div>
                                <div class="text-xs text-slate-400">Lokasi: {{ $lap->barang->lokasi ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-slate-600">{{ $lap->deskripsi_kerusakan }}</td>
                            <td class="p-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($lap->status_laporan == 'Menunggu') text-amber-700 bg-amber-50 border border-amber-200
                                    @elseif($lap->status_laporan == 'Diproses') text-blue-700 bg-blue-50 border border-blue-200
                                    @else text-green-700 bg-green-50 border border-green-200 @endif">
                                    {{ $lap->status_laporan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada data laporan kerusakan fasilitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
