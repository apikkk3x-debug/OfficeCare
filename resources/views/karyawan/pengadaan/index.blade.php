@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, modalData: {} }">
    
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
    <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        
        <!-- Baris Atas Tabel: Judul & Dropdown Tampilkan Data -->
        <div class="flex justify-between items-center text-xs text-slate-700">
            <h3 class="font-bold uppercase tracking-wider text-slate-700">DAFTAR PENGAJUAN</h3>
            
            <form method="GET" action="{{ route('karyawan.pengadaan.index') }}" class="flex items-center gap-2">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white border border-slate-300 rounded-lg px-2.5 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data</span>
            </form>
        </div>

        <!-- Wrapper Tabel Tanpa table-fixed agar kolom menyesuaikan isi -->
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="bg-indigo-900 text-white uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Barang</th>
                        <th class="p-3.5 text-center">Jumlah</th>
                        <th class="p-3.5">Estimasi Harga</th>
                        <th class="p-3.5 text-center">Referensi</th>
                        <th class="p-3.5">Alasan / Kebutuhan</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($pengadaanku as $index => $item)
                        @php
                            $namaBarang = $item->nama_barang_baru ?? $item->nama_barang;
                            $alasanBarang = $item->alasan_pengadaan ?? $item->alasan;
                            $rawStatus = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $isPending = in_array($rawStatus, ['pending', 'menunggu']);

                            $statusConfig = [
                                'pending'   => ['bg' => 'bg-amber-100 text-amber-700 border-amber-200',    'label' => 'Menunggu'],
                                'disetujui' => ['bg' => 'bg-blue-100 text-blue-700 border-blue-200',      'label' => 'Proses'],
                                'selesai'   => ['bg' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Selesai'],
                                'ditolak'   => ['bg' => 'bg-rose-100 text-rose-700 border-rose-200',      'label' => 'Ditolak'],
                            ];
                            $config = $statusConfig[$rawStatus] ?? $statusConfig['pending'];
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 text-slate-500 whitespace-nowrap">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                            </td>
                            
                            <td class="p-3.5 font-bold text-slate-800 truncate max-w-[200px]" title="{{ $namaBarang }}">
                                {{ $namaBarang }}
                            </td>
                            
                            <td class="p-3.5 font-medium text-center whitespace-nowrap">{{ $item->jumlah }} Unit</td>
                            
                            <td class="p-3.5 font-semibold text-slate-700 whitespace-nowrap">
                                Rp {{ number_format($item->estimasi_harga ?? 0, 0, ',', '.') }}
                            </td>
                            
                            <!-- Kolom Referensi dengan Tombol & Ikon Standar -->
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

                            <td class="p-3.5 text-slate-500 truncate max-w-[220px]" title="{{ $alasanBarang }}">
                                {{ $alasanBarang }}
                            </td>
                            
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border inline-block {{ $config['bg'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            <td class="p-3.5 text-center relative whitespace-nowrap" x-data="{ open: false }">
                                <div class="inline-block relative">
                                    <button @click="open = !open" @click.outside="open = false" 
                                            class="p-1.5 rounded-lg hover:bg-slate-200/80 text-slate-500 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                        </svg>
                                    </button>

                                    <div x-show="open" style="display: none;" 
                                         class="absolute right-0 mt-1 w-40 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left">
                                        
                                        <button @click="modalData = {
                                                    nama: '{{ addslashes($namaBarang) }}',
                                                    jumlah: '{{ $item->jumlah }}',
                                                    harga: 'Rp {{ number_format($item->estimasi_harga ?? 0, 0, ',', '.') }}',
                                                    link: '{{ $item->link_referensi }}',
                                                    alasan: '{{ addslashes($alasanBarang) }}',
                                                    status: '{{ $config['label'] }}',
                                                    tanggal: '{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}'
                                                }; showModal = true; open = false;" 
                                                class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-slate-100 font-medium cursor-pointer">
                                            Lihat Detail
                                        </button>

                                        @if($isPending)
                                            <a href="{{ route('karyawan.pengadaan.edit', $item->id_pengadaan ?? $item->id) }}" 
                                               class="block px-4 py-2 text-xs text-amber-600 hover:bg-slate-100 font-medium">
                                                Edit Pengajuan
                                            </a>

                                            <form action="{{ route('karyawan.pengadaan.destroy', $item->id_pengadaan ?? $item->id) }}" 
                                                  method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengadaan barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-slate-100 font-medium cursor-pointer">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400">
                                Belum ada riwayat pengajuan barang baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi Laravel -->
        <div class="pt-3 border-t border-slate-200">
            {{ $pengadaanku->links() }}
        </div>

    </div>

    <!-- Modal Pop-up Detail Pengadaan -->
    <div x-show="showModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        
        <div @click.outside="showModal = false" 
             class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg p-6 space-y-4 relative">
            
            <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md">Rincian Pengadaan</span>
                    <h3 class="text-base font-bold text-slate-800 mt-1" x-text="modalData.nama"></h3>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Pengajuan</span>
                    <span class="font-bold text-slate-700" x-text="modalData.tanggal"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Status Pengajuan</span>
                    <span class="font-bold text-slate-700" x-text="modalData.status"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Jumlah Unit</span>
                    <span class="font-bold text-slate-700" x-text="modalData.jumlah + ' Unit'"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Estimasi Harga</span>
                    <span class="font-bold text-slate-700" x-text="modalData.harga"></span>
                </div>

                <div class="col-span-2">
                    <span class="text-slate-400 block font-medium mb-1">Tautan Referensi Produk</span>
                    <template x-if="modalData.link">
                        <a :href="modalData.link" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1.5 text-indigo-600 hover:underline font-semibold bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-200">
                            <span>Buka Link Produk Eksternal</span>
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </template>
                    <template x-if="!modalData.link">
                        <span class="text-slate-400 italic">Tidak ada tautan referensi yang dilampirkan.</span>
                    </template>
                </div>

                <div class="col-span-2">
                    <span class="text-slate-400 block font-medium mb-1">Alasan / Kebutuhan Pengadaan</span>
                    <p class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-700 leading-relaxed" x-text="modalData.alasan"></p>
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button @click="showModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection