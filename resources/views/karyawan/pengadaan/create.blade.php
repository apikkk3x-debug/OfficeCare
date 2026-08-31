@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Pengadaan Barang
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Form Ajukan Pengadaan Barang Baru</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Isi rincian barang atau fasilitas baru yang dibutuhkan untuk operasional kantor.</p>
        </div>
        <a href="{{ route('karyawan.pengadaan.index') }}" class="bg-white/10 hover:bg-white/20 text-indigo-100 font-medium px-3.5 py-1.5 rounded-full text-xs border border-white/10 transition shrink-0">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card Container -->
    <div class="bg-slate-100/80 p-6 rounded-2xl border border-slate-200/90 shadow-sm">
        <form action="{{ route('karyawan.pengadaan.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama Barang -->
            <div>
                <label for="nama_barang_baru" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Barang / Fasilitas Baru
                </label>
                <input type="text" name="nama_barang_baru" id="nama_barang_baru" 
                    value="{{ old('nama_barang_baru') }}" required 
                    placeholder="Contoh: Proyektor Portable EPSON" 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama_barang_baru') border-rose-500 @enderror">
                @error('nama_barang_baru')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid Jumlah & Estimasi Harga -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jumlah (Unit)
                    </label>
                    <input type="number" name="jumlah" id="jumlah" 
                        value="{{ old('jumlah', 1) }}" min="1" required 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('jumlah') border-rose-500 @enderror">
                    @error('jumlah')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estimasi Harga -->
                <div>
                    <label for="estimasi_harga" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Estimasi Harga Per Unit (Opsional)
                    </label>
                    <input type="number" name="estimasi_harga" id="estimasi_harga" 
                        value="{{ old('estimasi_harga') }}" min="0" 
                        placeholder="Contoh: 1500000" 
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('estimasi_harga') border-rose-500 @enderror">
                    @error('estimasi_harga')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alasan Pengadaan -->
            <div>
                <label for="alasan_pengadaan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Alasan / Kebutuhan Pengadaan
                </label>
                <textarea name="alasan_pengadaan" id="alasan_pengadaan" rows="4" required 
                    placeholder="Jelaskan mengapa barang ini dibutuhkan untuk operasional kantor..." 
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('alasan_pengadaan') border-rose-500 @enderror">{{ old('alasan_pengadaan') }}</textarea>
                @error('alasan_pengadaan')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <a href="{{ route('karyawan.pengadaan.index') }}" 
                    class="px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/60 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                    Kirim Pengajuan ke Pimpinan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection