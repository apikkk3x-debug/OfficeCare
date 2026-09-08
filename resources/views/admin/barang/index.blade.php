@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Banner Header Manajemen Aset -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Manajemen Aset • Panel Admin
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Daftar Inventaris Sarpras Kantor</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola data inventaris fasilitas kantor dengan sistem kode dan kategori terstruktur.</p>
        </div>
        
        <button onclick="toggleModal(true)" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer border border-indigo-400/30 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Aset Baru</span>
        </button>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-medium transition-opacity duration-500 flex items-center gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Daftar Aset Kantor -->
    <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Semua Aset Kantor</h3>
            <span class="text-xs text-slate-500 font-medium">
                Total: {{ isset($barangs) ? count($barangs) : 0 }} Aset Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-200/60 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Kategori</th> <!-- Kolom Kategori Dipisah -->
                        <th class="p-3">Lokasi</th>   <!-- Kolom Lokasi Dipisah -->
                        <th class="p-3">Kode / Kondisi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white/70">
                    @forelse($barangs ?? [] as $index => $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $item->nama_barang }}</td>
                            
                            <!-- Menampilkan Kategori secara Terpisah -->
                            <td class="p-3 font-medium text-slate-700">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-md">
                                    {{ $item->kategori_barang ?? '-' }}
                                </span>
                            </td>

                            <!-- Menampilkan Lokasi secara Terpisah -->
                            <td class="p-3 text-slate-600">{{ $item->lokasi ?? '-' }}</td>

                            <td class="p-3">
                                <span class="font-mono text-indigo-600 font-semibold block">{{ $item->kode_barang ?? '-' }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $item->kondisi == 'Baik' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.barang.destroy', $item->id_barang) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus aset ini?')" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-3 py-1.5 rounded-lg text-[11px] border border-rose-200 transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
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
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: AC LG 1 PK" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('nama_barang') border-rose-500 @enderror">
                @error('nama_barang')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kategori Barang</label>
                <select name="kategori" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 @error('kategori') border-rose-500 @enderror">
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
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Ruang Meeting Lt. 2" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 @error('lokasi') border-rose-500 @enderror">
                @error('lokasi')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kondisi Awal</label>
                <select name="kondisi" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                    <option value="Baik">Baik</option>
                    <option value="Perbaikan Ringan">Perbaikan Ringan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20 cursor-pointer">Simpan Aset</button>
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