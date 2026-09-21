@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Admin -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-slate-400/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin Sarpras • Procurement Execution
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Realisasi Pengadaan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Eksekusi pemesanan fisik barang yang telah di-ACC Pimpinan untuk didaftarkan ke Master Aset.</p>
        </div>
    </div>

    <!-- Tabel Realisasi (Card Pembungkus Utama) -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
        
        <!-- Header Daftar & Dropdown Batas Tampil Data -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Pengajuan Pengadaan</h3>
            
            <form method="GET" action="{{ route('admin.pengadaan.index') }}" class="flex items-center gap-2 text-xs text-slate-600">
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
                        <th class="p-3.5 font-bold">Pemohon</th>
                        <th class="p-3.5 font-bold">Nama Barang</th>
                        <th class="p-3.5 font-bold">Jumlah</th>
                        <th class="p-3.5 font-bold">Status Progress</th>
                        <th class="p-3.5 font-bold text-center">Aksi Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white">
                    @forelse($daftarPengadaan as $index => $item)
                        @php
                            $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                            $idPengadaan = $item->id_pengadaan ?? $item->id;
                        @endphp
                        
                        <!-- Baris Tabel dengan Pengecekan Highlight -->
                        <tr class="{{ request('highlight_pengadaan') == $idPengadaan ? 'bg-indigo-50 ring-2 ring-indigo-400 transition-all duration-700 animate-pulse' : 'hover:bg-slate-50/80' }} transition">
                            <td class="p-3.5 font-semibold text-slate-500">
                                {{ method_exists($daftarPengadaan, 'firstItem') ? $daftarPengadaan->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-800 block">{{ $item->pemohon->nama ?? $item->pemohon->name ?? 'Karyawan' }}</span>
                            </td>
                            <td class="p-3.5 font-bold text-indigo-900">{{ $item->nama_barang_baru ?? $item->nama_barang }}</td>
                            <td class="p-3.5 font-semibold text-slate-700">{{ $item->jumlah }} Unit</td>
                            <td class="p-3.5">
                                @if($status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200 inline-block">Menunggu Pimpinan</span>
                                @elseif($status === 'disetujui')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200 inline-block">Proses Pembelian (Admin)</span>
                                @elseif($status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block">Selesai & Terdaftar</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200 inline-block">Ditolak</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center align-middle">
                                @if($status === 'disetujui')
                                    <button type="button" onclick="openSelesaiModal('{{ $idPengadaan }}', '{{ addslashes($item->nama_barang_baru ?? $item->nama_barang) }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3 py-1.5 rounded-xl text-[11px] transition shadow-sm cursor-pointer inline-flex items-center gap-1.5">
                                        <span>Tandai Selesai & Daftarkan Aset</span>
                                    </button>
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
                <select name="kategori" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Peralatan Kantor">Peralatan Kantor</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Komputer & Jaringan">Komputer & Jaringan</option>
                    <option value="Kendaraan">Kendaraan</option>
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
                <select name="kondisi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    <option value="Baik">Baik (Baru Dibeli)</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Baru">Baru</option>
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