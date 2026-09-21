@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Manajemen Aset -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-400/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Manajemen Aset • Panel Admin
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Daftar Inventaris Sarpras Kantor</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola data inventaris fasilitas kantor dengan sistem kode dan kategori terstruktur.</p>
        </div>
        
        <button onclick="toggleModal(true)" class="inline-flex items-center justify-center gap-2 bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Aset Baru</span>
        </button>
    </div>
    <!-- Tabel Daftar Aset Kantor (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Semua Aset Kantor</h3>
            
            <form method="GET" action="{{ route('admin.barang.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
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

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
            <table class="w-full text-left text-xs text-slate-600 border-collapse">
                <thead class="text-slate-100 bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 border-b border-indigo-700/50">
                    <tr>
                        <th class="p-3.5 font-bold">No</th>
                        <th class="p-3.5 font-bold">Nama Barang</th>
                        <th class="p-3.5 font-bold">Kategori</th>
                        <th class="p-3.5 font-bold">Lokasi</th>
                        <th class="p-3.5 font-bold">Kode / Kondisi</th>
                        <th class="p-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($barangs ?? [] as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-semibold text-slate-500">
                                {{ method_exists($barangs, 'firstItem') ? $barangs->firstItem() + $index : $index + 1 }}
                            </td>
                            
                            <!-- Nama Barang (Dipendekkan jika kepanjangan, lengkap dengan title tooltip) -->
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 truncate max-w-[180px]" title="{{ $item->nama_barang }}">
                                    {{ $item->nama_barang }}
                                </div>
                            </td>
                            
                            <!-- Kategori -->
                            <td class="p-3.5 font-medium text-slate-700">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-md">
                                    {{ $item->kategori_barang ?? '-' }}
                                </span>
                            </td>

                            <!-- Lokasi -->
                            <td class="p-3.5 text-slate-600">{{ $item->lokasi ?? '-' }}</td>

                            <!-- Kode & Kondisi -->
                            <td class="p-3.5">
                                <span class="font-mono text-indigo-600 font-semibold block">{{ $item->kode_barang ?? '-' }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium inline-block mt-0.5 {{ $item->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </td>

                            <!-- Menu Aksi Titik Tiga -->
                            <td class="p-3.5 text-center align-middle whitespace-nowrap">
                                <div class="relative inline-flex items-center justify-center text-left" x-data="{ openMenu: false }">
                                    <button @click="openMenu = !openMenu" type="button" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition cursor-pointer inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>

                                    <div x-show="openMenu" 
                                         @click.away="openMenu = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 text-left space-y-1"
                                         style="display: none;">
                                        
                                        <!-- Tombol Detail -->
                                        <button type="button" 
                                            onclick="openDetailModal('{{ addslashes($item->nama_barang) }}', '{{ addslashes($item->kategori_barang ?? '-') }}', '{{ addslashes($item->lokasi ?? '-') }}', '{{ addslashes($item->kode_barang ?? '-') }}', '{{ addslashes($item->kondisi ?? '-') }}', '{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}')" 
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Detail Aset</span>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.barang.destroy', $item->id_barang ?? $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus Aset</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada aset terdaftar di sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi & Keterangan Jumlah Data -->
        @if(method_exists($barangs, 'links'))
            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $barangs->firstItem() ?? 0 }} sampai {{ $barangs->lastItem() ?? 0 }} dari total {{ $barangs->total() }} data
                </div>
                <div class="[&_p]:hidden">
                    {{ $barangs->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- ================= MODAL DETAIL ASET ================= -->
<div id="barangDetailModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Detail Rincian Aset</h3>
                    <p class="text-[11px] text-slate-400">Informasi lengkap inventaris fasilitas kantor</p>
                </div>
            </div>
            <button type="button" onclick="toggleDetailModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <div class="space-y-3 text-xs text-slate-600">
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Kode Barang / Aset</span>
                <p id="detailKode" class="font-mono text-indigo-700 font-semibold text-sm mt-0.5 bg-indigo-50/50 border border-indigo-100/60 px-3 py-1.5 rounded-xl inline-block"></p>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Nama Barang</span>
                <p id="detailNama" class="font-bold text-slate-800 text-sm mt-0.5"></p>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Kategori</span>
                    <p id="detailKategori" class="font-medium text-slate-700 mt-0.5"></p>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Kondisi Aset</span>
                    <div class="mt-0.5">
                        <span id="detailKondisi" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase inline-block"></span>
                    </div>
                </div>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Lokasi Penempatan</span>
                <p id="detailLokasi" class="font-medium text-slate-700 mt-0.5"></p>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal Terdaftar</span>
                <p id="detailTanggal" class="font-medium text-slate-600 mt-0.5"></p>
            </div>
        </div>

        <div class="flex justify-end pt-3 border-t border-slate-100">
            <button type="button" onclick="toggleDetailModal(false)" class="px-4 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah Aset Baru -->
<div id="barangModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-800">Form Tambah Barang Fasilitas</h3>
            <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.barang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: AC LG 1 PK" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('nama_barang') border-rose-500 @enderror">
                @error('nama_barang')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kategori Barang</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 @error('kategori') border-rose-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Furniture" {{ old('kategori') == 'Furniture' ? 'selected' : '' }}>Furniture / Mebel</option>
                    <option value="Peralatan Kantor" {{ old('kategori') == 'Peralatan Kantor' ? 'selected' : '' }}>Peralatan Kantor</option>
                    <option value="Fasilitas Umum" {{ old('kategori') == 'Fasilitas Umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                </select>
                @error('kategori')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Lokasi Ruangan</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Ruang Meeting Lt. 2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('lokasi') border-rose-500 @enderror">
                @error('lokasi')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kondisi Awal</label>
                <select name="kondisi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                    <option value="Baik">Baik</option>
                    <option value="Perbaikan Ringan">Perbaikan Ringan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20 cursor-pointer">Simpan Aset</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('barangModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function toggleDetailModal(show) {
        const modal = document.getElementById('barangDetailModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openDetailModal(nama, kategori, lokasi, kode, kondisi, tanggal) {
        document.getElementById('detailNama').innerText = nama;
        document.getElementById('detailKategori').innerText = kategori;
        document.getElementById('detailLokasi').innerText = lokasi;
        document.getElementById('detailKode').innerText = kode;
        document.getElementById('detailTanggal').innerText = tanggal;

        const kondisiEl = document.getElementById('detailKondisi');
        kondisiEl.innerText = kondisi;
        if (kondisi === 'Baik') {
            kondisiEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block';
        } else {
            kondisiEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200 inline-block';
        }

        toggleDetailModal(true);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    });

    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            toggleModal(true);
        });
    @endif
</script>
@endsection