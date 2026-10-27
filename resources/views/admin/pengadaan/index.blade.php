@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Admin -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 border border-slate-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin Sarpras • Procurement Execution
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Realisasi Pengadaan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Eksekusi pemesanan fisik barang yang telah di-ACC Pimpinan untuk didaftarkan ke Master Aset.</p>
        </div>
        <span class="bg-white/10 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-xl text-xs border border-white/10 shrink-0 shadow-xs">
            Administrator
        </span>
    </div>

    <!-- Alert Sukses Notifikasi -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-medium flex items-center gap-2.5 shadow-sm transition-opacity duration-500">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.style.display = 'none', 500);
                }
            }, 3000);
        </script>
    @endif

    <!-- Stat Cards Ringkasan Eksekusi Pengadaan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengadaan</p>
                <h3 class="text-lg font-bold text-slate-800 mt-0.5">
                    {{ method_exists($daftarPengadaan, 'total') ? $daftarPengadaan->total() : count($daftarPengadaan ?? []) }}
                </h3>
            </div>
            <div class="p-2.5 bg-slate-100 text-slate-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Siap Terbitkan Aset</p>
                <h3 class="text-lg font-bold text-indigo-600 mt-0.5">
                    {{ method_exists($daftarPengadaan, 'getCollection') ? $daftarPengadaan->getCollection()->whereIn('status_approval', ['disetujui', 'Disetujui'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selesai & Terdaftar</p>
                <h3 class="text-lg font-bold text-emerald-600 mt-0.5">
                    {{ method_exists($daftarPengadaan, 'getCollection') ? $daftarPengadaan->getCollection()->whereIn('status_approval', ['selesai', 'Selesai'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Realisasi (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Pengajuan Pengadaan</h3>
            
            <form method="GET" action="{{ route('admin.pengadaan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

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

        <!-- ================= BARIS FILTER & PENCARIAN MULTI-KRITERIA ================= -->
        <form method="GET" action="{{ route('admin.pengadaan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pb-2 border-b border-slate-200/60">
            @if(request()->has('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <!-- Input Pencarian Teks -->
            <div class="sm:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama barang pengganti, pemohon, alasan..." 
                       class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm">
            </div>

            <!-- Dropdown Filter Status Progress -->
            <div class="sm:col-span-4">
                <select name="status" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 shadow-sm cursor-pointer focus:outline-none focus:border-indigo-600">
                    <option value="">-- Semua Status Progress --</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Menunggu ACC</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Siap Dibeli</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai & Terdaftar</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Tombol Cari & Reset Filter -->
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm cursor-pointer py-2">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.pengadaan.index') }}" title="Reset Filter" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3.5 font-bold text-center w-12">No</th>
                        <th class="p-3.5 font-bold">Pemohon</th>
                        <th class="p-3.5 font-bold">Nama Barang Pengganti</th>
                        <th class="p-3.5 font-bold text-center">Jumlah</th>
                        <th class="p-3.5 font-bold text-right">Total Estimasi</th>
                        <th class="p-3.5 font-bold text-center">Status Progress</th>
                        <th class="p-3.5 font-bold text-center w-52">Aksi Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($daftarPengadaan as $index => $item)
                        @php
                            $namaBarang = $item->nama_barang_baru ?? $item->nama_barang;
                            $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $idPengadaan = $item->id_pengadaan ?? $item->id;

                            // Perhitungan Estimasi Biaya
                            $jumlah = max(1, (int) ($item->jumlah ?? 1));
                            $totalEstimasi = (float) ($item->estimasi_harga ?? 0);
                            $hargaSatuan = $jumlah > 0 ? ($totalEstimasi / $jumlah) : 0;
                        @endphp
                        
                        <tr class="{{ request('highlight_pengadaan') == $idPengadaan ? 'bg-indigo-50/90 ring-2 ring-indigo-400/50 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50/80' }} transition">
                            <td class="p-3.5 font-semibold text-slate-400 text-center">
                                {{ method_exists($daftarPengadaan, 'firstItem') ? $daftarPengadaan->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block">{{ $item->pemohon->nama ?? $item->pemohon->name ?? 'Karyawan' }}</span>
                            </td>
                            <td class="p-3.5 font-bold text-slate-800 min-w-[200px]" title="{{ $namaBarang }}">
                                {{ $namaBarang }}
                            </td>
                            <td class="p-3.5 font-semibold text-indigo-600 text-center whitespace-nowrap">
                                {{ $jumlah }} Unit
                            </td>
                            
                            <!-- Kolom Estimasi Harga -->
                            <td class="p-3.5 font-bold text-slate-700 text-right whitespace-nowrap">
                                <div>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</div>
                                @if($hargaSatuan > 0 && $jumlah > 1)
                                    <div class="text-[10px] text-slate-400 font-normal">
                                        (Rp {{ number_format($hargaSatuan, 0, ',', '.') }} / unit)
                                    </div>
                                @endif
                            </td>

                            <td class="p-3.5 text-center whitespace-nowrap">
                                @if(in_array($status, ['pending', 'menunggu']))
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200/80 inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu ACC
                                    </span>
                                @elseif($status === 'disetujui')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200/80 inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span> Siap Dibeli
                                    </span>
                                @elseif($status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/80 inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Selesai & Terdaftar
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200/80 inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>

                            <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <!-- Tombol Detail -->
                                    <button type="button" onclick='openDetailModal(@json($item))' 
                                            class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 font-medium px-2.5 py-1.5 rounded-xl text-xs border border-slate-300 transition shadow-2xs hover:border-slate-400 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Detail</span>
                                    </button>

                                    <!-- Tombol Aksi Utama / Badge Informasi -->
                                    @if($status === 'disetujui')
                                        <button type="button" onclick="openSelesaiModal('{{ $idPengadaan }}', '{{ addslashes($namaBarang) }}')" 
                                                class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <span>Terbitkan Aset</span>
                                        </button>
                                    @elseif($status === 'selesai')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200/70">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Terdaftar
                                        </span>
                                    @elseif($status === 'ditolak')
                                        <span class="inline-flex items-center text-[11px] font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-xl border border-slate-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-[11px] font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200/60">
                                            Menunggu ACC
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400 bg-slate-50/50">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-slate-500">Tidak Ada Data Pengadaan</p>
                                    <p class="text-xs text-slate-400">Data tidak ditemukan untuk kriteria pencarian/filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi & Keterangan Jumlah Data -->
        @if(method_exists($daftarPengadaan, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $daftarPengadaan->firstItem() ?? 0 }} sampai {{ $daftarPengadaan->lastItem() ?? 0 }} dari total {{ $daftarPengadaan->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $daftarPengadaan->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- ================= MODAL DETAIL RINCIAN PENGADAAN ================= -->
<div id="modalDetailPengadaan" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden items-center justify-center z-50 p-3 transition-all duration-200" onclick="toggleDetailModal(false)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 overflow-hidden space-y-0" onclick="event.stopPropagation()">
        
        <!-- Header Modal -->
        <div class="bg-slate-900 px-4 py-3 text-white flex justify-between items-center border-b border-slate-800">
            <div class="flex items-center gap-2">
                <span class="p-1 bg-indigo-500/20 text-indigo-300 rounded border border-indigo-400/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">Rincian Pengadaan Barang</h3>
            </div>
            <button type="button" onclick="toggleDetailModal(false)" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body Content -->
        <div class="p-3.5 space-y-2.5 text-xs max-h-[80vh] overflow-y-auto custom-scrollbar">

            <!-- Nama Barang -->
            <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-0.5">
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Nama Barang / Produk Pengganti</span>
                <h4 id="detail_nama_barang" class="text-xs font-bold text-slate-800 leading-snug break-words">-</h4>
            </div>

            <!-- Banner Mini: Estimasi & Kuantitas -->
            <div class="bg-indigo-50/70 border border-indigo-100/90 rounded-xl p-3 flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Total Estimasi Anggaran</span>
                    <p id="detail_estimasi" class="text-base font-extrabold text-indigo-900 tracking-tight">Rp 0</p>
                </div>
                <div class="text-right">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Jumlah Unit</span>
                    <span id="detail_jumlah" class="font-bold text-slate-800 bg-white px-2.5 py-1 rounded-lg border border-slate-200/80 text-[11px] shadow-2xs">
                        - Unit
                    </span>
                </div>
            </div>

            <!-- Grid Pemohon & Status -->
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                    <span class="text-[9px] font-bold uppercase text-slate-400 block">Pemohon</span>
                    <p id="detail_pemohon" class="font-bold text-slate-800 truncate">-</p>
                </div>

                <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                    <span class="text-[9px] font-bold uppercase text-slate-400 block">Status Approval</span>
                    <div id="detail_status_badge" class="mt-0.5">
                        <p id="detail_status" class="font-bold text-slate-800">-</p>
                    </div>
                </div>
            </div>

            <!-- Link Toko Referensi -->
            <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-wider text-purple-900 block">Link Toko Referensi</span>
                <div id="detail_area_link"></div>
            </div>

            <!-- Alasan Pengadaan -->
            <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Alasan & Catatan Pengalihan</span>
                <p id="detail_alasan" class="text-[11px] text-slate-700 leading-snug break-words pl-2 border-l-2 border-indigo-400">-</p>
            </div>

            <!-- Catatan Pimpinan -->
            <div class="p-2.5 bg-amber-50/70 border border-amber-200/80 rounded-xl space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-wider text-amber-800 block">Catatan dari Pimpinan</span>
                <p id="detail_catatan_pimpinan" class="text-[11px] text-amber-950 italic leading-snug break-words pl-2 border-l-2 border-amber-400">Belum ada catatan.</p>
            </div>

        </div>

        <!-- Footer Modal -->
        <div class="px-3.5 py-2.5 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="toggleDetailModal(false)" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition cursor-pointer active:scale-95">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL INPUT KATEGORI & LOKASI ASET ================= -->
<div id="selesaiPengadaanModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4">
        
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-800">Finalisasi & Pendaftaran Aset</h3>
                <p class="text-[11px] text-slate-400">Tentukan kategori dan lokasi penempatan barang baru</p>
            </div>
            <button type="button" onclick="toggleSelesaiModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <form id="formSelesaiPengadaan" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama Barang (Disabled - Hanya Info) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Barang Pengadaan</label>
                <input type="text" id="modal_nama_barang" disabled class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600">
            </div>

            <!-- Pilihan Kategori -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kategori Barang</label>
                <select name="kategori" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white cursor-pointer">
                    <option value="">-- Pilih Kategori Aset --</option>
                    <option value="Perangkat IT & Elektronik">Perangkat IT & Elektronik (PC, Printer, AC, TV, Jaringan)</option>
                    <option value="Furnitur & Mebel">Furnitur & Mebel (Meja, Kursi, Lemari, Rak)</option>
                    <option value="Perlengkapan Kantor">Perlengkapan Kantor (Dispenser, Brankas, Alat Fasilitas)</option>
                    <option value="Kendaraan Operasional">Kendaraan Operasional (Mobil / Motor Dinas)</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Input Lokasi -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Lokasi Penempatan Aset</label>
                <input type="text" name="lokasi" required placeholder="Contoh: Ruang Meeting / Gudang Utama" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white">
            </div>

            <!-- Kondisi Awal -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kondisi Awal Aset</label>
                <select name="kondisi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white cursor-pointer">
                    <option value="Baik">Baik (Baru Dibeli)</option>
                    <option value="Perbaikan Ringan">Perbaikan Ringan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleSelesaiModal(false)" class="px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-md shadow-emerald-600/20 cursor-pointer">Simpan & Masukkan ke Aset</button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.URLSearchParams) {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('highlight_pengadaan')) {
                urlParams.delete('highlight_pengadaan');
                const newRelativePathQuery = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                history.replaceState(null, '', newRelativePathQuery);
            }
        }
    });

    function openDetailModal(data) {
        document.getElementById('detail_nama_barang').innerText = data.nama_barang_baru || data.nama_barang || 'Barang Pengganti';
        document.getElementById('detail_pemohon').innerText = data.pemohon?.nama || data.pemohon?.name || 'Karyawan';
        
        // PERBAIKAN: Menggunakan Math.max agar tidak error di JavaScript
        const qty = Math.max(1, parseInt(data.jumlah || 1));
        document.getElementById('detail_jumlah').innerText = qty + ' Unit';
        
        const total = Math.round(parseFloat(data.estimasi_harga) || 0);
        document.getElementById('detail_estimasi').innerText = 'Rp ' + total.toLocaleString('id-ID');

        document.getElementById('detail_alasan').innerText = data.alasan_pengadaan || data.alasan || '-';
        document.getElementById('detail_catatan_pimpinan').innerText = data.catatan_pimpinan || 'Belum ada catatan dari pimpinan.';

        const statusVal = (data.status_approval || data.status || 'PENDING').toLowerCase();
        const badgeContainer = document.getElementById('detail_status_badge');
        
        if (statusVal === 'pending' || statusVal === 'menunggu') {
            badgeContainer.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200"><span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu ACC</span>`;
        } else if (statusVal === 'disetujui') {
            badgeContainer.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200"><span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span> Siap Dibeli</span>`;
        } else if (statusVal === 'selesai') {
            badgeContainer.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Selesai & Terdaftar</span>`;
        } else {
            badgeContainer.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Ditolak</span>`;
        }

        const linkContainer = document.getElementById('detail_area_link');
        if (data.link_referensi) {
            linkContainer.innerHTML = `
                <a href="${data.link_referensi}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-[11px] text-indigo-600 font-semibold hover:text-indigo-800 bg-indigo-50/80 hover:bg-indigo-100/80 px-2.5 py-1 rounded-lg border border-indigo-200/80 transition break-all">
                    <span>Buka Link Referensi Produk</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>`;
        } else {
            linkContainer.innerHTML = `<span class="text-[11px] text-slate-400 italic">Tidak ada tautan referensi.</span>`;
        }

        toggleDetailModal(true);
    }

    function toggleDetailModal(show) {
        const modal = document.getElementById('modalDetailPengadaan');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openSelesaiModal(id, namaBarang) {
        const modal = document.getElementById('selesaiPengadaanModal');
        const form = document.getElementById('formSelesaiPengadaan');
        
        form.action = `/admin/pengadaan/${id}/selesai`;
        document.getElementById('modal_nama_barang').value = namaBarang;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function toggleSelesaiModal(show) {
        const modal = document.getElementById('selesaiPengadaanModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endsection