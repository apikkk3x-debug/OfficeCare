@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80">
        <h2 class="text-xl font-bold text-stone-800 mb-1">Edit Laporan Kerusakan</h2>
        <p class="text-stone-700 text-sm mb-6">Silakan perbarui data kerusakan fasilitas di bawah ini.</p>

        <form action="{{ route('laporan.update', $laporan->id_laporan ?? $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Pilih Barang -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-700 mb-1">Nama Barang / Fasilitas</label>
                <!-- Menampilkan nilai nama barang saat ini, tapi bisa diketik/diubah manual -->
                <input type="text" name="nama_barang" value="{{ $laporan->barang->nama_barang ?? '' }}" class="w-full rounded-xl border-amber-300 border px-4 py-2.5 text-sm bg-white/60 focus:ring-2 focus:ring-amber-500 focus:bg-white outline-none" placeholder="Contoh: AC Ruang Meeting / Proyektor" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-700 mb-1">Lokasi/Ruangan (Opsional)</label>
                <input type="text" name="lokasi" value="{{ $laporan->lokasi ?? '' }}" class="w-full rounded-xl border-amber-300 border px-4 py-2.5 text-sm bg-white/60 focus:ring-2 focus:ring-amber-500 focus:bg-white outline-none">
            </div>

            <!-- Foto Bukti Kerusakan -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-700 mb-1">Foto Bukti Kerusakan (Opsional)</label>
                
                @if($laporan->foto)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-white/70 rounded-xl border border-amber-300">
                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Lama" class="w-16 h-16 object-cover rounded-lg border">
                        <span class="text-xs text-stone-600">Foto saat ini (biarkan kosong jika tidak ingin mengubah foto).</span>
                    </div>
                @endif

                <input type="file" name="foto" class="w-full text-sm text-stone-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-200/80 file:text-blue-900 hover:file:bg-amber-500 transition cursor-pointer">
                <p class="text-xs text-stone-500 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
            </div>

            <!-- Deskripsi Kerusakan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-stone-700 mb-1">Deskripsi Kerusakan</label>
                <textarea name="deskripsi_kerusakan" rows="4" class="w-full rounded-xl border-amber-300 border px-4 py-2.5 text-sm bg-white/60 focus:ring-2 focus:ring-amber-500 focus:bg-white outline-none" required>{{ $laporan->deskripsi_kerusakan }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-amber-300 hover:bg-amber-400 text-amber-950 font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-sm border border-amber-400">
                    Simpan Perubahan
                </button>
                <a href="{{ route('karyawan.dashboard') }}" class="bg-stone-200 hover:bg-stone-300 text-stone-800 font-semibold px-6 py-2.5 rounded-xl text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection