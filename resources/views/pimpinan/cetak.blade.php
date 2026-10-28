@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner Executive -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Executive • Rekapitulasi
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Rekap & Cetak Laporan Kerusakan</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Seluruh rincian monitoring fasilitas kantor untuk pencetakan dokumen PDF.</p>
        </div>
        
        <!-- Tombol Cetak PDF (Membawa Filter Aktif) -->
        <a href="{{ route('pimpinan.laporan.cetak', request()->query()) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 shrink-0 cursor-pointer shadow-xs active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak Hasil Filter (PDF)</span>
        </a>
    </div>

    <!-- ================= KARTU FILTER PIMPINAN (SERASI DENGAN ADMIN) ================= -->
    <form method="GET" action="{{ route('pimpinan.rekap') }}" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-indigo-900"></div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Filter & Rekapitulasi Data</h3>
            </div>
            <span class="text-[11px] text-slate-400">Panel Executive</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <!-- Dari Tanggal -->
            <div>
                <label for="tanggal_mulai" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label for="tanggal_selesai" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Status Laporan -->
            <div>
                <label for="status_laporan" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Laporan</label>
                <select name="status_laporan" id="status_laporan" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ request('status_laporan') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ request('status_laporan') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status_laporan') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Limit Maksimal Data -->
            <div>
                <label for="limit" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Maksimal Tampil</label>
                <select name="limit" id="limit" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="50" {{ request('limit', '100') == '50' ? 'selected' : '' }}>50 Data Terbaru</option>
                    <option value="100" {{ request('limit', '100') == '100' ? 'selected' : '' }}>100 Data Terbaru</option>
                    <option value="200" {{ request('limit', '100') == '200' ? 'selected' : '' }}>200 Data Terbaru</option>
                    <option value="semua" {{ request('limit') == 'semua' ? 'selected' : '' }}>Semua Data (Tanpa Limit)</option>
                </select>
            </div>
        </div>

        <!-- Tombol Aksi Filter -->
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            @if(request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'status_laporan', 'limit']))
                <a href="{{ route('pimpinan.rekap') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                    Reset Filter
                </a>
            @endif
            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer active:scale-95 shadow-xs">
                Terapkan Filter
            </button>
        </div>
    </form>

        <!-- ================= TABEL REKAPITULASI PIMPINAN ================= -->
        <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            
            <!-- Sub-Header Tabel -->
            <div class="flex items-center justify-between px-1">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Daftar Laporan</h4>
                <span class="text-[11px] font-semibold text-slate-500 bg-white px-2.5 py-0.5 rounded-lg border border-slate-200 shadow-2xs">
                    Total: {{ count($laporan) }} Data
                </span>
            </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
        <table class="w-full text-left text-xs text-slate-600 border-collapse">
            <thead>
                <tr class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                    <th class="p-3.5 font-bold text-center w-12">No</th>
                    <th class="p-3.5 font-bold">Tanggal Laporan</th>
                    <th class="p-3.5 font-bold">Pelapor</th>
                    <th class="p-3.5 font-bold">Aset / Barang & Lokasi</th>
                    <th class="p-3.5 font-bold">Deskripsi Kerusakan</th>
                    <th class="p-3.5 font-bold">Status Saat Ini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/80 bg-white">
                @forelse($laporan as $index => $lap)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 text-center font-semibold text-slate-500">
                            {{ method_exists($laporan, 'firstItem') ? $laporan->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="p-3.5 text-slate-600 text-[11px] whitespace-nowrap">{{ $lap->created_at->format('d/m/Y') }}</td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $lap->user->name ?? $lap->user->nama ?? '-' }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-indigo-900">{{ $lap->barang->nama_barang ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400">Lokasi: {{ $lap->barang->lokasi ?? '-' }}</div>
                        </td>
                        <td class="p-3.5 text-slate-600 max-w-xs leading-relaxed">{{ $lap->deskripsi_kerusakan }}</td>
                        <td class="p-3.5 whitespace-nowrap">
                            @php
                                $status = $lap->status_laporan;
                                $badge = [
                                    'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Diproses' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Ditolak'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                ][$status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border inline-block {{ $badge }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 italic">
                            Belum ada data laporan kerusakan fasilitas yang sesuai dengan kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

</div>
@endsection