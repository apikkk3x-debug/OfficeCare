@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Executive • Rekapitulasi
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Rekap & Cetak Laporan Kerusakan</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Seluruh rincian monitoring fasilitas kantor untuk pencetakan dokumen PDF.</p>
        </div>
        
        <!-- Tombol Cetak PDF -->
        <a href="{{ route('pimpinan.laporan.cetak') }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 border border-slate-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak Rekapitulasi PDF</span>
        </a>
    </div>

    <!-- Tabel Rekapitulasi Laporan Dipindahkan ke Sini -->
    <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-200/60 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Tanggal Laporan</th>
                        <th class="p-3">Pelapor</th>
                        <th class="p-3">Nama Barang & Lokasi</th>
                        <th class="p-3">Deskripsi Kerusakan</th>
                        <th class="p-3">Status Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white/70">
                    @forelse($laporan as $index => $lap)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-3 text-slate-500 text-[11px]">{{ $lap->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $lap->user->name ?? $lap->user->nama ?? '-' }}</td>
                            <td class="p-3">
                                <div class="font-bold text-indigo-900">{{ $lap->barang->nama_barang ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">Lokasi: {{ $lap->barang->lokasi ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-slate-500 max-w-xs leading-relaxed">{{ $lap->deskripsi_kerusakan }}</td>
                            <td class="p-3">
                                @php
                                    $status = $lap->status_laporan;
                                    $badge = [
                                        'Menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'Diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'Selesai'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    ][$status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border {{ $badge }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada data laporan kerusakan fasilitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection