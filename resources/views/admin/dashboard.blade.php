@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Admin Sarpras</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola inventaris fasilitas kantor dan tanggapi laporan kerusakan dari karyawan.</p>
        </div>
        <span class="bg-indigo-100 text-indigo-700 font-semibold px-4 py-2 rounded-lg text-sm">
            Admin Sarpras
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 lg:col-span-1 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Manajemen Aset</h3>
                <p class="text-slate-500 text-sm mb-6">Kelola inventaris fasilitas kantor dengan sistem kode otomatis dan kategori terstruktur.</p>
            </div>
            
            <button onclick="toggleModal(true)" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                ➕ Tambah Barang Baru
            </button>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 lg:col-span-2">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Daftar Laporan Kerusakan Masuk</h3>
                <a href="{{ route('admin.laporan.cetak') }}" target="_blank" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 cursor-pointer">
                    🖨️ Cetak Laporan
                </a>

                <a href="{{ route('admin.users') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 border border-indigo-200 cursor-pointer">
                    👥 Kelola Pengguna
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b">
                            <th class="p-3">Pelapor</th>
                            <th class="p-3">Barang & Lokasi</th>
                            <th class="p-3">Kerusakan</th>
                            <th class="p-3">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($laporanMasuk as $lap)
                            <tr>
                                <td class="p-3 font-medium text-slate-800">{{ $lap->user->name ?? 'User Dihapus' }}</td>
                                <td class="p-3 text-slate-600">
                                    <div class="font-medium text-slate-800">{{ $lap->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                                    <div class="text-xs text-slate-400">Lokasi: {{ $lap->barang->lokasi ?? '-' }}</div>
                                </td>
                                <td class="p-3 text-slate-600">{{ $lap->deskripsi_kerusakan }}</td>
                                <td class="p-3">
                                    <!-- Diperbarui ke admin.laporan.updateStatus agar sinkron dengan Controller -->
                                    <form action="{{ route('admin.laporan.updateStatus', $lap->id_laporan) }}" method="POST">
                                        @csrf
                                        <select name="status_laporan" onchange="this.form.submit()" class="px-2.5 py-1 rounded-lg text-xs font-semibold border bg-white cursor-pointer
                                            @if($lap->status_laporan == 'Menunggu') text-amber-700 bg-amber-50 border-amber-200
                                            @elseif($lap->status_laporan == 'Diproses') text-blue-700 bg-blue-50 border-blue-200
                                            @else text-green-700 bg-green-50 border-green-200 @endif">
                                            <option value="Menunggu" {{ $lap->status_laporan == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Diproses" {{ $lap->status_laporan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai" {{ $lap->status_laporan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-400">Belum ada laporan kerusakan dari karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div id="barangModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-bold text-slate-800">Form Tambah Barang Fasilitas</h3>
            <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('barang.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: AC LG 1 PK" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 @error('nama_barang') border-red-500 @enderror">
                @error('nama_barang')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-1">Kategori Barang</label>
                <select name="kategori" class="w-full px-3 py-2 border rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Furniture" {{ old('kategori') == 'Furniture' ? 'selected' : '' }}>Furniture / Mebel</option>
                    <option value="Peralatan Kantor" {{ old('kategori') == 'Peralatan Kantor' ? 'selected' : '' }}>Peralatan Kantor</option>
                    <option value="Fasilitas Umum" {{ old('kategori') == 'Fasilitas Umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                </select>
                @error('kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-1">Lokasi Ruangan</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Ruang Meeting Lt. 2" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 @error('lokasi') border-red-500 @enderror">
                @error('lokasi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-1">Kondisi Awal</label>
                <select name="kondisi" class="w-full px-3 py-2 border rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500">
                    <option value="Baik">Baik</option>
                    <option value="Perbaikan Ringan">Perbaikan Ringan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 border rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition cursor-pointer">Simpan Aset</button>
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

    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            toggleModal(true);
        });
    @endif
</script>
@endsection