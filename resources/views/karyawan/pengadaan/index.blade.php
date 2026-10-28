@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, modalData: {} }">
    
    <!-- Banner Header Karyawan -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Pengadaan Barang • Karyawan
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Daftar Pengajuan Barang Baru</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau status permohonan fasilitas/barang baru yang kamu ajukan ke Pimpinan.</p>
        </div>
        
        <a href="{{ route('karyawan.pengadaan.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-indigo-600/20 transition shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Ajukan Barang Baru</span>
        </a>
    </div>

    <!-- Stat Cards Ringkasan Pengadaan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengajuan</p>
                <h3 class="text-lg font-bold text-slate-800 mt-0.5">
                    {{ method_exists($pengadaanku, 'total') ? $pengadaanku->total() : count($pengadaanku ?? []) }}
                </h3>
            </div>
            <div class="p-2.5 bg-slate-100 text-slate-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Menunggu Approval</p>
                <h3 class="text-lg font-bold text-amber-600 mt-0.5">
                    {{ method_exists($pengadaanku, 'getCollection') ? $pengadaanku->getCollection()->whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selesai / Terbeli</p>
                <h3 class="text-lg font-bold text-emerald-600 mt-0.5">
                    {{ method_exists($pengadaanku, 'getCollection') ? $pengadaanku->getCollection()->whereIn('status_approval', ['selesai', 'Selesai'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengajuan (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Baris Atas Tabel: Judul & Dropdown Tampilkan Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Pengajuan Saya</h3>
            
            <form method="GET" action="{{ route('karyawan.pengadaan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-600 cursor-pointer shadow-sm">
                    <option value="10" {{ (isset($perPage) && $perPage == 10) || request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ (isset($perPage) && $perPage == 25) || request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ (isset($perPage) && $perPage == 50) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (isset($perPage) && $perPage == 100) || request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data</span>
            </form>
        </div>

        <!-- ================= BARIS FILTER & PENCARIAN ================= -->
        <form method="GET" action="{{ route('karyawan.pengadaan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pb-2 border-b border-slate-200/60">
            @if(request()->has('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <!-- Input Pencarian Teks -->
            <div class="sm:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama barang, alasan kebutuhan..." 
                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm">
            </div>

            <!-- Dropdown Filter Status -->
            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 shadow-sm cursor-pointer focus:outline-none focus:border-indigo-600">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' || request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' || request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui Pimpinan</option>
                    <option value="selesai" {{ request('status') == 'selesai' || request('status') == 'Selesai' ? 'selected' : '' }}>Selesai & Terbeli</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' || request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Tombol Cari & Reset Filter -->
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm cursor-pointer py-2">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('karyawan.pengadaan.index') }}" title="Reset Filter" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead>
                    <tr class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                        <th class="p-3.5 font-bold">Tanggal</th>
                        <th class="p-3.5 font-bold">Barang</th>
                        <th class="p-3.5 font-bold text-center">Jumlah</th>
                        <th class="p-3.5 font-bold">Estimasi Harga</th>
                        <th class="p-3.5 font-bold text-center">Referensi</th>
                        <th class="p-3.5 font-bold">Alasan / Kebutuhan</th>
                        <th class="p-3.5 font-bold">Status</th>
                        <th class="p-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($pengadaanku as $index => $item)
                        @php
                            $namaBarang = $item->nama_barang_baru ?? $item->nama_barang;
                            $alasanBarang = $item->alasan_pengadaan ?? $item->alasan;
                            $rawStatus = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $isPending = in_array($rawStatus, ['pending', 'menunggu']);
                            $idPengadaan = $item->id_pengadaan ?? $item->id;

                            // Perhitungan Estimasi Biaya (estimasi_harga di DB dianggap sebagai Harga Satuan)
                            $jumlah = max(1, (int) ($item->jumlah ?? 1));
                            $hargaSatuan = (float) ($item->estimasi_harga ?? 0);
                            $totalEstimasi = $hargaSatuan * $jumlah;

                            $statusConfig = [
                                'pending'   => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200',   'label' => 'Menunggu Approval'],
                                'menunggu'  => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200',   'label' => 'Menunggu Approval'],
                                'disetujui' => ['bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',  'label' => 'Disetujui Pimpinan'],
                                'selesai'   => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200','label' => 'Selesai & Terbeli'],
                                'ditolak'   => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200',     'label' => 'Ditolak'],
                            ];
                            $config = $statusConfig[$rawStatus] ?? $statusConfig['pending'];
                        @endphp
                        
                        <!-- Baris Tabel dengan Pengecekan Highlight -->
                        <tr class="{{ request('highlight_pengadaan') == $idPengadaan ? 'bg-indigo-50 ring-2 ring-indigo-400 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50/80' }} transition">
                            <td class="p-3.5 text-slate-600 whitespace-nowrap">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                            </td>
                            
                            <td class="p-3.5 font-bold text-slate-800 truncate max-w-[200px]" title="{{ $namaBarang }}">
                                {{ $namaBarang }}
                            </td>
                            
                            <td class="p-3.5 font-semibold text-center text-indigo-600 whitespace-nowrap">{{ $jumlah }} Unit</td>
                            
                            <!-- Kolom Estimasi Harga (Total + Satuan jika > 1 unit) -->
                            <td class="p-3.5 font-semibold text-slate-700 whitespace-nowrap">
                                <div>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</div>
                                @if($hargaSatuan > 0 && $jumlah > 1)
                                    <div class="text-[10px] text-slate-400 font-normal">
                                        (Rp {{ number_format($hargaSatuan, 0, ',', '.') }} / unit)
                                    </div>
                                @endif
                            </td>
                            
                            <td class="p-3.5 text-center whitespace-nowrap">
                                @if(!empty($item->link_referensi))
                                    <a href="{{ $item->link_referensi }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-200 font-semibold transition">
                                        <span>Buka</span>
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>

                            <td class="p-3.5 text-slate-600 truncate max-w-[220px]" title="{{ $alasanBarang }}">
                                {{ $alasanBarang }}
                            </td>
                            
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border inline-block {{ $config['bg'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            <td class="p-3.5 whitespace-nowrap text-center align-middle">
                                <div class="relative inline-flex items-center justify-center text-left" x-data="{ open: false }">
                                    <button @click="open = !open" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu Pop-up Kiri -->
                                    <div x-show="open" 
                                         @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left space-y-0.5" 
                                         style="display: none;">
                                        
                                        <button @click="modalData = {
                                                nama: '{{ addslashes($namaBarang) }}',
                                                jumlah: '{{ $jumlah }}',
                                                harga: 'Rp {{ number_format($totalEstimasi, 0, ',', '.') }} @if($jumlah > 1)(Satuan: Rp {{ number_format($hargaSatuan, 0, ',', '.') }})@endif',
                                                link: '{{ addslashes($item->link_referensi ?? '') }}',
                                                alasan: '{{ addslashes($alasanBarang) }}',
                                                status: '{{ $config['label'] }}',
                                                tanggal: '{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}'
                                            }; showModal = true; open = false;" 
                                            class="w-full text-left px-3 py-2 hover:bg-slate-50 text-slate-700 font-medium cursor-pointer transition">
                                            Detail
                                        </button>

                                        @if($isPending)
                                            <a href="{{ route('karyawan.pengadaan.edit', $idPengadaan) }}" 
                                               class="block px-3 py-2 hover:bg-indigo-50 text-indigo-600 font-semibold transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('karyawan.pengadaan.destroy', $idPengadaan) }}" 
                                                  method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengadaan barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full text-left px-3 py-2 hover:bg-rose-50 text-rose-600 font-semibold cursor-pointer transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <div class="px-3 py-2 text-slate-400 italic text-[11px] select-none border-t border-slate-100">
                                                Terkunci
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                Belum ada data riwayat pengajuan barang baru yang sesuai kriteria pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi Laravel -->
        @if(method_exists($pengadaanku, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $pengadaanku->firstItem() ?? 0 }} sampai {{ $pengadaanku->lastItem() ?? 0 }} dari total {{ $pengadaanku->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $pengadaanku->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ================= MODAL POP-UP DETAIL PENGADAAN (ALPINE.JS TELEPORT) ================= -->
    <template x-teleport="body">
        <div x-show="showModal" 
             style="display: none;" 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-[9999] p-3 transition-all duration-200">
            
            <div @click.outside="showModal = false" 
                 class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-700 overflow-hidden space-y-0 text-left">
                
                <!-- Header Modal Dark Mode -->
                <div class="bg-slate-900 px-4 py-3 text-white flex justify-between items-center border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="p-1 bg-indigo-500/20 text-indigo-300 rounded border border-indigo-400/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">Rincian Pengadaan Barang</h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body Content -->
                <div class="p-3.5 space-y-2.5 text-xs max-h-[80vh] overflow-y-auto custom-scrollbar">

                    <!-- Nama Barang -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-0.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Nama Barang</span>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug break-words" x-text="modalData.nama || '-'"></h4>
                    </div>

                    <!-- Banner Mini Highlight: Estimasi Biaya & Kuantitas -->
                    <div class="bg-indigo-50/70 border border-indigo-100/90 rounded-xl p-3 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Estimasi Biaya</span>
                            <p class="text-base font-extrabold text-indigo-900 tracking-tight" x-text="modalData.harga || 'Rp 0'"></p>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Jumlah Unit</span>
                            <span class="font-bold text-slate-800 bg-white px-2.5 py-1 rounded-lg border border-slate-200/80 text-[11px] shadow-2xs" x-text="(modalData.jumlah || '-') + ' Unit'"></span>
                        </div>
                    </div>

                    <!-- Grid Tanggal & Status Pengajuan -->
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Tanggal Pengajuan</span>
                            <p class="font-bold text-slate-800 truncate" x-text="modalData.tanggal || '-'"></p>
                        </div>

                        <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Status Pengajuan</span>
                            <p class="font-bold text-slate-800 truncate" x-text="modalData.status || '-'"></p>
                        </div>
                    </div>

                    <!-- Tautan Referensi Produk -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-1">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-purple-900 block">Tautan Referensi Produk</span>
                        <template x-if="modalData.link && modalData.link.trim() !== ''">
                            <a :href="modalData.link" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 hover:underline font-bold text-[11px]">
                                <span>Buka Link Produk Eksternal</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </template>
                        <template x-if="!modalData.link || modalData.link.trim() === ''">
                            <p class="text-[11px] text-slate-400 italic">Tidak ada tautan referensi yang dilampirkan.</p>
                        </template>
                    </div>

                    <!-- Alasan / Kebutuhan Pengadaan -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-1">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Alasan / Kebutuhan Pengadaan</span>
                        <p class="text-[11px] text-slate-700 leading-snug break-words pl-2 border-l-2 border-indigo-400" x-text="modalData.alasan || '-'"></p>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="px-3.5 py-2.5 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="button" @click="showModal = false" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition cursor-pointer active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>

<!-- Skrip Pembersih URL Parameter Highlight -->
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
</script>
@endsection