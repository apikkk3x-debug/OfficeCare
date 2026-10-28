@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, modalData: {}, approvalModal: false, approvalAction: '', approvalUrl: '', catatanPimpinan: '' }">
    
    <!-- Header Banner Khusus Pimpinan -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-700/60 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Pimpinan • Approval System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Persetujuan Pengadaan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Tinjau dan berikan keputusan persetujuan beserta catatan untuk permohonan fasilitas/barang baru kantor.</p>
        </div>
        <span class="bg-white/10 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-xl text-xs border border-white/10 shrink-0 shadow-xs">
            Pimpinan / Management
        </span>
    </div>
    <!-- Stat Cards Ringkasan Pengadaan Khusus Pimpinan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Permohonan</p>
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
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Butuh Approval</p>
                <h3 class="text-lg font-bold text-amber-600 mt-0.5">
                    {{ method_exists($daftarPengadaan, 'getCollection') ? $daftarPengadaan->getCollection()->whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Telah Disetujui</p>
                <h3 class="text-lg font-bold text-indigo-600 mt-0.5">
                    {{ method_exists($daftarPengadaan, 'getCollection') ? $daftarPengadaan->getCollection()->whereIn('status_approval', ['disetujui', 'Disetujui', 'selesai', 'Selesai'])->count() : 0 }}
                </h3>
            </div>
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengajuan (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Baris Atas Tabel: Judul & Dropdown Tampilkan Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-2 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Permohonan Masuk</h3>
            
            <form method="GET" action="{{ route('pimpinan.pengadaan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
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

        <!-- ================= BARIS FILTER & PENCARIAN PENGADAAN PIMPINAN ================= -->
        <form method="GET" action="{{ route('pimpinan.pengadaan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pb-2 border-b border-slate-200/60">
            @if(request()->has('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <!-- 1. Input Pencarian Teks -->
            <div class="sm:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama barang pengganti, pemohon, alasan..." 
                       class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm">
            </div>

            <!-- 2. Dropdown Filter Status Approval Pimpinan -->
            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 shadow-sm cursor-pointer focus:outline-none focus:border-indigo-600">
                    <option value="">-- Semua Status Approval --</option>
                    <option value="Pending" {{ request('status') == 'Pending' || request('status') == 'pending' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' || request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui / Siap Dibeli</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' || request('status') == 'selesai' ? 'selected' : '' }}>Selesai & Terdaftar</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' || request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- 3. Tombol Cari & Reset Filter -->
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm cursor-pointer py-2">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('pimpinan.pengadaan.index') }}" title="Reset Filter" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center justify-center">
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
                        <th class="p-3.5 font-bold">No</th>
                        <th class="p-3.5 font-bold">Pemohon</th>
                        <th class="p-3.5 font-bold">Nama Barang Baru</th>
                        <th class="p-3.5 font-bold text-center">Jumlah</th>
                        <th class="p-3.5 font-bold">Estimasi Harga</th>
                        <th class="p-3.5 font-bold text-center">Referensi</th>
                        <th class="p-3.5 font-bold">Status</th>
                        <th class="p-3.5 font-bold">Catatan Pimpinan</th>
                        <th class="p-3.5 font-bold text-center">Aksi Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($daftarPengadaan as $index => $item)
                        @php
                            $namaBarang = $item->nama_barang_baru ?? $item->nama_barang;
                            $alasanBarang = $item->alasan_pengadaan ?? $item->alasan;
                            $namaPemohon = $item->pemohon->nama ?? $item->pemohon->name ?? 'Karyawan';
                            $emailPemohon = $item->pemohon->email ?? '-';
                            $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $idPengadaan = $item->id_pengadaan ?? $item->id;
                            $catatan = $item->catatan_pimpinan ?? '-';

                            // Perhitungan Estimasi Biaya (estimasi_harga di DB dianggap sebagai Harga Satuan)
                            $jumlah = max(1, (int) ($item->jumlah ?? 1));
                            $hargaSatuan = (float) ($item->estimasi_harga ?? 0);
                            $totalEstimasi = $hargaSatuan * $jumlah;
                            
                            $statusConfig = [
                                'pending'   => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200',   'label' => 'Menunggu Approval'],
                                'menunggu'  => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200',   'label' => 'Menunggu Approval'],
                                'disetujui' => ['bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',  'label' => 'Disetujui'],
                                'selesai'   => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200','label' => 'Selesai & Terbeli'],
                                'ditolak'   => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200',     'label' => 'Ditolak'],
                            ];
                            $config = $statusConfig[$status] ?? $statusConfig['pending'];
                        @endphp
                        
                        <!-- Baris Tabel dengan Pengecekan Highlight -->
                        <tr class="{{ request('highlight_pengadaan') == $idPengadaan ? 'bg-indigo-50 ring-2 ring-indigo-400 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50/80' }} transition">
                            <td class="p-3.5 font-semibold text-slate-500">
                                {{ method_exists($daftarPengadaan, 'firstItem') ? $daftarPengadaan->firstItem() + $index : $index + 1 }}
                            </td>

                            <td class="p-3.5">
                                <span class="font-bold text-slate-800 block">{{ $namaPemohon }}</span>
                                <span class="text-[10px] text-slate-400">{{ $emailPemohon }}</span>
                            </td>

                            <td class="p-3.5 font-bold text-slate-800 truncate max-w-[160px]" title="{{ $namaBarang }}">
                                {{ $namaBarang }}
                            </td>

                            <td class="p-3.5 font-semibold text-indigo-600 text-center whitespace-nowrap">
                                {{ $jumlah }} Unit
                            </td>

                            <!-- Kolom Estimasi Harga (Total + Satuan) -->
                            <td class="p-3.5 font-semibold text-slate-700 whitespace-nowrap">
                                <div>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</div>
                                @if($hargaSatuan > 0 && $jumlah > 1)
                                    <div class="text-[10px] text-slate-400 font-normal">
                                        (Rp {{ number_format($hargaSatuan, 0, ',', '.') }} / unit)
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Kolom Referensi Eksternal -->
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

                            <!-- Badge Status -->
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border inline-block {{ $config['bg'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            
                            <!-- Kolom Catatan Pimpinan -->
                            <td class="p-3.5 text-slate-600 max-w-[180px] truncate" title="{{ $catatan }}">
                                {{ $catatan }}
                            </td>

                            <td class="p-3.5 text-center relative whitespace-nowrap align-middle">
                                
                                <!-- DROPDOWN MENU TITIK TIGA (Alpine.js) -->
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

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
                                        
                                        <!-- Detail -->
                                        <button type="button" 
                                                @click="open = false; modalData = {
                                                    pemohon: '{{ addslashes($namaPemohon) }}',
                                                    nama: '{{ addslashes($namaBarang) }}',
                                                    jumlah: '{{ $jumlah }}',
                                                    harga: 'Rp {{ number_format($totalEstimasi, 0, ',', '.') }} @if($jumlah > 1)(Satuan: Rp {{ number_format($hargaSatuan, 0, ',', '.') }})@endif',
                                                    link: '{{ addslashes($item->link_referensi ?? '') }}',
                                                    alasan: '{{ addslashes($alasanBarang) }}',
                                                    status: '{{ $config['label'] }}',
                                                    catatan: '{{ addslashes($catatan) }}',
                                                    tanggal: '{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}'
                                                }; showModal = true;"
                                                class="w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition flex items-center gap-1.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </button>

                                        @if(in_array($status, ['pending', 'menunggu']))
                                            <!-- Keputusan Setujui / Tolak dengan Modal Catatan -->
                                            <div class="border-t border-slate-100 pt-0.5 mt-0.5">
                                                <button type="button" 
                                                        @click="open = false; approvalAction = 'setujui'; approvalUrl = '{{ route('pimpinan.pengadaan.setujui', $idPengadaan) }}'; catatanPimpinan = ''; approvalModal = true;"
                                                        class="w-full text-left px-3 py-2 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 transition flex items-center gap-1.5 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>

                                                <button type="button" 
                                                        @click="open = false; approvalAction = 'tolak'; approvalUrl = '{{ route('pimpinan.pengadaan.tolak', $idPengadaan) }}'; catatanPimpinan = ''; approvalModal = true;"
                                                        class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Tolak
                                                </button>
                                            </div>
                                        @else
                                            <div class="px-3 py-2 text-slate-400 italic text-[11px] select-none border-t border-slate-100">
                                                Selesai Ditinjau
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">
                                Belum ada permohonan pengadaan barang baru yang sesuai kriteria pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi Laravel -->
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

    <!-- ================= MODAL INPUT CATATAN DISPOSISI ================= -->
    <template x-teleport="body">
        <div x-show="approvalModal" 
             style="display: none;" 
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
            
            <div @click.outside="approvalModal = false" 
                 class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md p-6 space-y-4 relative animate-in fade-in zoom-in duration-200">
                
                <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md"
                              :class="approvalAction === 'setujui' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200'"
                              x-text="approvalAction === 'setujui' ? 'Konfirmasi Persetujuan' : 'Konfirmasi Penolakan'"></span>
                        <h3 class="text-base font-bold text-slate-800 mt-1">Berikan Catatan / Alasan Pimpinan</h3>
                    </div>
                    <button @click="approvalModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form :action="approvalUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan / Instruksi Pimpinan (Opsional)</label>
                        <textarea name="catatan_pimpinan" x-model="catatanPimpinan" rows="3" 
                                  placeholder="Tuliskan catatan, instruksi budget, atau alasan keputusan di sini..."
                                  class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 transition leading-relaxed"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="approvalModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-white rounded-xl text-xs font-semibold transition shadow-md cursor-pointer"
                                :class="approvalAction === 'setujui' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20' : 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/20'"
                                x-text="approvalAction === 'setujui' ? 'Ya, Setujui' : 'Ya, Tolak'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
    
    <!-- ================= MODAL POP-UP DETAIL PERMOHONAN PENGADAAN ================= -->
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
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200">Rincian Permohonan Pengadaan</h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body Content -->
                <div class="p-3.5 space-y-2.5 text-xs max-h-[80vh] overflow-y-auto custom-scrollbar">

                    <!-- Nama Barang / Permohonan -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-0.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Nama Barang / Permohonan</span>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug break-words" x-text="modalData.nama || '-'"></h4>
                    </div>

                    <!-- Banner Mini Highlight: Estimasi Biaya & Kuantitas -->
                    <div class="bg-indigo-50/70 border border-indigo-100/90 rounded-xl p-3 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Total Estimasi Biaya</span>
                            <p class="text-base font-extrabold text-indigo-900 tracking-tight" x-text="modalData.harga || 'Rp 0'"></p>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Jumlah Unit</span>
                            <span class="font-bold text-slate-800 bg-white px-2.5 py-1 rounded-lg border border-slate-200/80 text-[11px] shadow-2xs" x-text="(modalData.jumlah || '-') + ' Unit'"></span>
                        </div>
                    </div>

                    <!-- Grid Pemohon & Status Pengajuan -->
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Pemohon</span>
                            <p class="font-bold text-slate-800 truncate" x-text="modalData.pemohon || '-'"></p>
                        </div>

                        <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Status Pengajuan</span>
                            <p class="font-bold text-slate-800 truncate" x-text="modalData.status || '-'"></p>
                        </div>
                    </div>

                    <!-- Tanggal Pengajuan -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-xl space-y-0.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Tanggal Pengajuan</span>
                        <p class="font-semibold text-slate-700 leading-snug" x-text="modalData.tanggal || '-'"></p>
                    </div>

                    <!-- Catatan Pimpinan (Tampil jika ada catatan) -->
                    <div x-show="modalData.catatan && modalData.catatan !== '-'" 
                         class="p-2.5 bg-amber-50/70 border border-amber-200/80 rounded-xl space-y-1">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-amber-800 block">Catatan dari Pimpinan</span>
                        <p class="text-[11px] text-amber-950 italic leading-snug break-words pl-2 border-l-2 border-amber-400" x-text="modalData.catatan"></p>
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