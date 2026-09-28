@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, modalData: {}, approvalModal: false, approvalAction: '', approvalUrl: '', catatanPimpinan: '' }">
    
    <!-- Header Banner Khusus Pimpinan -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Pimpinan • Approval System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Persetujuan Pengadaan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Tinjau dan berikan keputusan persetujuan beserta catatan untuk permohonan fasilitas/barang dari karyawan.</p>
        </div>
        <span class="bg-white/15 backdrop-blur-md text-indigo-200 font-medium px-3.5 py-1.5 rounded-full text-xs border border-white/10 shrink-0 shadow-sm">
            Pimpinan / Management
        </span>
    </div>

    <!-- Alert Sukses -->
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

    <!-- Tabel Daftar Pengajuan Seluruh Karyawan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        
        <!-- Baris Atas Tabel: Judul & Dropdown Tampilkan Data -->
        <div class="flex justify-between items-center text-xs text-slate-700">
            <h3 class="font-bold uppercase tracking-wider text-slate-700">DAFTAR PERMOHONAN MASUK</h3>
            
            <form method="GET" action="{{ route('pimpinan.pengadaan.index') }}" class="flex items-center gap-2">
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

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="bg-indigo-900 text-white uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3.5">No</th>
                        <th class="p-3.5">Pemohon (Karyawan)</th>
                        <th class="p-3.5">Nama Barang Baru</th>
                        <th class="p-3.5 text-center">Jumlah</th>
                        <th class="p-3.5">Estimasi Harga</th>
                        <th class="p-3.5 text-center">Referensi</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Catatan Pimpinan</th>
                        <th class="p-3.5 text-center">Aksi Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($daftarPengadaan as $index => $item)
                        @php
                            $namaBarang = $item->nama_barang_baru ?? $item->nama_barang;
                            $alasanBarang = $item->alasan_pengadaan ?? $item->alasan;
                            $namaPemohon = $item->pemohon->nama ?? $item->pemohon->name ?? 'Karyawan';
                            $emailPemohon = $item->pemohon->email ?? '-';
                            $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $idPengadaan = $item->id_pengadaan ?? $item->id;
                            $catatan = $item->catatan_pimpinan ?? '-';
                            
                            $badge = [
                                'pending'   => 'bg-amber-100 text-amber-700 border-amber-200',
                                'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'ditolak'   => 'bg-rose-100 text-rose-700 border-rose-200',
                            ][$status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp
                        
                        <!-- Baris Tabel dengan Pengecekan Highlight -->
                        <tr class="{{ request('highlight_pengadaan') == $idPengadaan ? 'bg-indigo-50 ring-2 ring-indigo-400 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50' }} transition">
                            <td class="p-3.5 font-semibold text-slate-500">
                                {{ ($daftarPengadaan->currentPage() - 1) * $daftarPengadaan->perPage() + $index + 1 }}
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-800 block">{{ $namaPemohon }}</span>
                                <span class="text-[10px] text-slate-400">{{ $emailPemohon }}</span>
                            </td>
                            <td class="p-3.5 font-bold text-indigo-900 truncate max-w-[160px]" title="{{ $namaBarang }}">{{ $namaBarang }}</td>
                            <td class="p-3.5 font-semibold text-slate-700 text-center whitespace-nowrap">{{ $item->jumlah }} Unit</td>
                            <td class="p-3.5 font-medium text-slate-600 whitespace-nowrap">
                                {{ $item->estimasi_harga ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.') : '-' }}
                            </td>
                            
                            <!-- Kolom Referensi dengan Simbol Standar Eksternal Link -->
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

                            <td class="p-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            
                            <!-- Kolom Catatan Pimpinan -->
                            <td class="p-3.5 text-slate-600 max-w-[180px] truncate" title="{{ $catatan }}">
                                {{ $catatan }}
                            </td>

                            <td class="p-3.5 text-center relative whitespace-nowrap">
                                
                                <!-- DROPDOWN MENU TITIK TIGA (Alpine.js) -->
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" type="button" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 focus:outline-none transition cursor-pointer">
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
                                        class="absolute right-0 mt-1 w-36 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50 divide-y divide-slate-100 text-left"
                                        style="display: none;">
                                        
                                        <!-- Option 1: Detail -->
                                        <div class="py-0.5">
                                            <button type="button" 
                                                @click="open = false; modalData = {
                                                    pemohon: '{{ addslashes($namaPemohon) }}',
                                                    nama: '{{ addslashes($namaBarang) }}',
                                                    jumlah: '{{ $item->jumlah }}',
                                                    harga: '{{ $item->estimasi_harga ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.') : '-' }}',
                                                    link: '{{ $item->link_referensi }}',
                                                    alasan: '{{ addslashes($alasanBarang) }}',
                                                    status: '{{ ucfirst($status) }}',
                                                    catatan: '{{ addslashes($catatan) }}',
                                                    tanggal: '{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}'
                                                }; showModal = true;"
                                                class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center gap-1.5 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail
                                            </button>
                                        </div>

                                        @if($status === 'pending')
                                            <!-- Option 2 & 3: Keputusan Setujui / Tolak dengan Modal Catatan -->
                                            <div class="py-0.5">
                                                <button type="button" 
                                                    @click="open = false; approvalAction = 'setujui'; approvalUrl = '{{ route('pimpinan.pengadaan.setujui', $idPengadaan) }}'; catatanPimpinan = ''; approvalModal = true;"
                                                    class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-emerald-600 hover:bg-emerald-50 transition flex items-center gap-1.5 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>

                                                <button type="button" 
                                                    @click="open = false; approvalAction = 'tolak'; approvalUrl = '{{ route('pimpinan.pengadaan.tolak', $idPengadaan) }}'; catatanPimpinan = ''; approvalModal = true;"
                                                    class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Tolak
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-400">
                                Belum ada permohonan pengadaan barang baru dari karyawan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi Laravel -->
        <div class="pt-3 border-t border-slate-200">
            {{ $daftarPengadaan->links() }}
        </div>

    </div>

    <!-- ================= MODAL INPUT CATATAN DISPOSISI ================= -->
    <div x-show="approvalModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        
        <div @click.outside="approvalModal = false" 
             class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md p-6 space-y-4 relative">
            
            <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md"
                          :class="approvalAction === 'setujui' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
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
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan / Instruksi (Opsional)</label>
                    <textarea name="catatan_pimpinan" x-model="catatanPimpinan" rows="3" 
                              placeholder="Tuliskan catatan, instruksi budget, atau alasan keputusan di sini..."
                              class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 transition"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
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

    <!-- Modal Pop-up Detail Pengadaan Khusus Pimpinan (Alpine.js) -->
    <div x-show="showModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        
        <div @click.outside="showModal = false" 
             class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg p-6 space-y-4 relative">
            
            <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md">Rincian Permohonan</span>
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
                    <span class="text-slate-400 block font-medium">Nama Pemohon</span>
                    <span class="font-bold text-slate-700" x-text="modalData.pemohon"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Pengajuan</span>
                    <span class="font-bold text-slate-700" x-text="modalData.tanggal"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Jumlah Unit</span>
                    <span class="font-bold text-slate-700" x-text="modalData.jumlah + ' Unit'"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Estimasi Harga</span>
                    <span class="font-bold text-slate-700" x-text="modalData.harga"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Status Pengajuan</span>
                    <span class="font-bold text-slate-700" x-text="modalData.status"></span>
                </div>

                <div class="col-span-2" x-show="modalData.catatan && modalData.catatan !== '-'">
                    <span class="text-slate-400 block font-medium mb-1">Catatan Pimpinan</span>
                    <p class="bg-indigo-50 p-3 rounded-xl border border-indigo-100 text-indigo-900 font-medium leading-relaxed" x-text="modalData.catatan"></p>
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

<!-- Skrip Pembersih URL Parameter Highlight agar hilang saat di-refresh -->
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