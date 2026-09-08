@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Admin -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin Sarpras • Procurement Execution
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Realisasi Pengadaan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Eksekusi pemesanan fisik barang yang telah di-ACC Pimpinan untuk didaftarkan ke Master Aset.</p>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-medium flex items-center gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Realisasi -->
    <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-200/60 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Pemohon</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Status Progress</th>
                        <th class="p-3 text-center">Aksi Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white/70">
                    @forelse($daftarPengadaan as $index => $item)
                        @php
                            $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800 block">{{ $item->pemohon->nama ?? $item->pemohon->name ?? 'Karyawan' }}</span>
                            </td>
                            <td class="p-3 font-bold text-indigo-900">{{ $item->nama_barang_baru ?? $item->nama_barang }}</td>
                            <td class="p-3 font-semibold text-slate-700">{{ $item->jumlah }} Unit</td>
                            <td class="p-3">
                                @if($status === 'pending')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-amber-100 text-amber-700 border border-amber-200">Menunggu Pimpinan</span>
                                @elseif($status === 'disetujui')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-blue-100 text-blue-700 border border-blue-200">Proses Pembelian (Admin)</span>
                                @elseif($status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">Selesai & Terdaftar</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-rose-100 text-rose-700 border border-rose-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($status === 'disetujui')
                                    <!-- Tombol Eksekusi Admin -->
                                    <form action="{{ route('admin.pengadaan.selesai', $item->id_pengadaan ?? $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="return confirm('Apakah fisik barang sudah dibeli dan siap didaftarkan ke inventaris?')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1.5 rounded-lg text-[11px] transition shadow-xs cursor-pointer">
                                            Tandai Selesai & Daftarkan Aset
                                        </button>
                                    </form>
                                @elseif($status === 'selesai')
                                    <span class="text-[11px] text-emerald-600 font-semibold italic">Aset Telah Diterbitkan</span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Menunggu Persetujuan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada pengajuan pengadaan barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection