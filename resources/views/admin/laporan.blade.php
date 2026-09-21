@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Admin -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-400/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin • Sarpras System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Data Semua Laporan Masuk</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau, ubah status, tanggapi pesan karyawan, dan kelola pengaduan kerusakan sarana prasarana.</p>
        </div>
        
        <a href="{{ route('admin.cetakLaporan') }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-indigo-600/20 transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak Laporan</span>
        </a>
    </div>

    <!-- Tabel Data Laporan Lengkap (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Pengaduan Kerusakan</h3>
            
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-600 cursor-pointer shadow-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data</span>
            </form>
        </div>
        
        <!-- Wrapper Tabel Tanpa Scroll Vertikal -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                    <tr>
                        <th class="p-3.5 font-bold">Tanggal</th>
                        <th class="p-3.5 font-bold">Pelapor</th>
                        <th class="p-3.5 font-bold">Barang & Lokasi</th>
                        <th class="p-3.5 font-bold">Deskripsi Kerusakan</th>
                        <th class="p-3.5 font-bold text-center">Status & Prioritas</th>
                        <th class="p-3.5 font-bold text-center">Aksi & Diskusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($laporan as $index => $item)
                        @php
                            $tanggal = $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-';
                            $pelapor = $item->user->nama ?? $item->user->name ?? 'Karyawan';
                            $email = $item->user->email ?? '-';
                            $barang = $item->barang->nama_barang ?? $item->nama_barang ?? '-';
                            $lokasi = $item->barang->lokasi ?? $item->lokasi ?? '-';
                            $deskripsi = $item->deskripsi_kerusakan ?? $item->deskripsi ?? $item->keterangan ?? '-';
                            $status = $item->status_laporan ?? 'Menunggu';
                            $prioritas = $item->prioritas ?? 'Sedang'; // Mengambil data prioritas
                            $idLaporan = $item->id_laporan ?? $item->id;
                        @endphp
                        
                        <!-- Baris Tabel dengan Pengecekan Highlight -->
                        <tr class="{{ request('highlight_log') == $idLaporan ? 'bg-indigo-50 ring-2 ring-indigo-400 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50/80' }} transition">
                            
                            <!-- Tanggal -->
                            <td class="p-3.5 font-medium text-slate-600 whitespace-nowrap">
                                {{ $tanggal }}
                            </td>

                            <!-- Pelapor -->
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 truncate max-w-[150px]" title="{{ $pelapor }}">
                                    {{ $pelapor }}
                                </div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $email }}">
                                    {{ $email }}
                                </div>
                            </td>

                            <!-- Barang & Lokasi -->
                            <td class="p-3.5">
                                <div class="font-semibold text-slate-800 truncate max-w-[160px]" title="{{ $barang }}">
                                    {{ $barang }}
                                </div>
                                <div class="text-[10px] text-slate-500 truncate max-w-[160px]" title="Lokasi: {{ $lokasi }}">
                                    Lokasi: {{ $lokasi }}
                                </div>
                            </td>

                            <!-- Deskripsi Kerusakan -->
                            <td class="p-3.5">
                                <p class="text-slate-600 truncate max-w-[220px]" title="{{ $deskripsi }}">
                                    {{ $deskripsi }}
                                </p>
                            </td>

                            <!-- Badge Status & Prioritas -->
                            <td class="p-3.5 text-center whitespace-nowrap space-y-1">
                                <!-- Status Badge -->
                                <div>
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full border inline-block
                                        @if($status == 'Selesai') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @elseif($status == 'Diproses') bg-indigo-50 text-indigo-700 border-indigo-200
                                        @elseif($status == 'Dibatalkan' || $status == 'Ditolak') bg-slate-100 text-slate-500 border-slate-200 line-through
                                        @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                        {{ $status }}
                                    </span>
                                </div>

                                <!-- Prioritas Badge (Fitur Baru) -->
                                <div>
                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-md border inline-flex items-center gap-1
                                        @if($prioritas == 'Darurat') bg-red-50 text-red-700 border-red-200 animate-pulse
                                        @elseif($prioritas == 'Sedang') bg-amber-50 text-amber-700 border-amber-200
                                        @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                                        ⚡ {{ $prioritas }}
                                    </span>
                                </div>
                            </td>

                            <!-- Form Aksi Ubah Status & Tombol Kelola (Detail + Chat) -->
                            <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Select Status -->
                                    <form action="{{ route('admin.laporan.updateStatus', $idLaporan) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <select name="status_laporan" onchange="this.form.submit()" class="text-[11px] bg-white border border-slate-300 rounded-xl px-2 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer font-medium text-slate-700 shadow-sm">
                                            <option value="Menunggu" {{ $status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Diproses" {{ $status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </form>

                                    <!-- Tombol Menuju Halaman Detail & Ruang Diskusi Terpadu -->
                                    <a href="{{ route('admin.laporan.show', $idLaporan) }}" 
                                        title="Buka Detail & Ruang Diskusi"
                                        class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition border border-indigo-200 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>Kelola</span>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-8 text-slate-400">
                                Belum ada data laporan pengaduan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi & Keterangan Jumlah Data -->
        @if(method_exists($laporan, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $laporan->firstItem() ?? 0 }} sampai {{ $laporan->lastItem() ?? 0 }} dari total {{ $laporan->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $laporan->links() }}
                </div>
            </div>
        @endif

    </div>
</div>
@endsection