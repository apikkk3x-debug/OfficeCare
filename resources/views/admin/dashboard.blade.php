@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Admin Banner (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin • Sarpras System
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Dashboard Ringkasan Utama</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau performa sarana prasarana, kelola aset, dan tanggapi pengaduan secara real-time.</p>
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

    <!-- Ringkasan Statistik Interaktif (4 Kolom Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Laporan -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Laporan</span>
                    <div class="p-2 bg-white text-indigo-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $laporanMasuk->count() ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Laporan pengaduan masuk</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola laporan masuk &rarr;
            </span>
        </a>

        <!-- Card 2: Status Menunggu -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-amber-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Perlu Diproses</span>
                    <div class="p-2 bg-white text-amber-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-amber-600 mt-3">
                    {{ $laporanMasuk->where('status_laporan', 'Menunggu')->count() ?? 0 }}
                </h4>
                <p class="text-slate-500 text-xs mt-1">Menunggu respon/perbaikan</p>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Tinjau laporan menunggu &rarr;
            </span>
        </a>

        <!-- Card 3: Kelola Pengguna -->
        <a href="{{ route('admin.users') }}" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-blue-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pengguna Terdaftar</span>
                    <div class="p-2 bg-white text-blue-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">Kelola</h4>
                <p class="text-slate-500 text-xs mt-1">Manajemen akun & hak akses</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola data pengguna &rarr;
            </span>
        </a>

        <!-- Card 4: Cetak Rekapitulasi -->
        <a href="{{ route('admin.cetakLaporan') }}" target="_blank" class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-slate-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Cetak Dokumen</span>
                    <div class="p-2 bg-white text-slate-700 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-slate-800 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold text-slate-800 mt-3">Rekap Laporan</h4>
                <p class="text-slate-500 text-xs mt-1">Unduh atau cetak file PDF</p>
            </div>
            <span class="text-xs font-semibold text-slate-700 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Unduh rekap PDF &rarr;
            </span>
        </a>

    </div>

    <!-- Quick Action Bar -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Butuh Meninjau Seluruh Pengaduan?</h3>
            <p class="text-slate-500 text-xs mt-0.5">Seluruh data rincian pelapor, foto kerusakan, dan pengubahan status tersedia terpusat di halaman Data Laporan.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 shrink-0">
            <span>Buka Halaman Data Laporan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>

<!-- Modal Tambah Barang (Diselaraskan) -->
<div id="barangModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-800">Form Tambah Barang Fasilitas</h3>
            <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('barang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: AC LG 1 PK" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama_barang') border-rose-500 @enderror">
                @error('nama_barang')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kategori Barang</label>
                <select name="kategori" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kategori') border-rose-500 @enderror">
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
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Ruang Meeting Lt. 2" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('lokasi') border-rose-500 @enderror">
                @error('lokasi')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-semibold mb-1">Kondisi Awal</label>
                <select name="kondisi" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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