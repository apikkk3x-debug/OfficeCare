@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Pengadaan Barang
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Daftar Pengajuan Barang Baru</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau status permohonan fasilitas/barang baru yang kamu ajukan ke Pimpinan.</p>
        </div>
        
        <a href="{{ route('karyawan.pengadaan.create') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-emerald-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Ajukan Barang Baru</span>
        </a>
    </div>

    <!-- Tabel Daftar Pengajuan -->
    <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-200/60 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Alasan / Kebutuhan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tanggal Pengajuan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white/70">
                    @forelse($pengadaanku as $index => $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $item->nama_barang_baru ?? $item->nama_barang }}</td>
                            <td class="p-3 font-medium">{{ $item->jumlah }} Unit</td>
                            <td class="p-3 text-slate-500 max-w-xs truncate">{{ $item->alasan_pengadaan ?? $item->alasan }}</td>
                            <td class="p-3">
                                @php
                                    $status = strtolower($item->status ?? 'pending');
                                    $badge = [
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    ][$status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-400 text-[11px]">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">
                                Belum ada riwayat pengajuan barang baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection