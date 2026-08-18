@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h2 class="text-xl font-bold text-slate-800 mb-1">Edit Laporan Kerusakan</h2>
        <p class="text-slate-500 text-sm mb-6">Silakan perbarui data kerusakan fasilitas di bawah ini.</p>

        <form action="{{ route('laporan.update', $laporan->id_laporan ?? $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Pilih Barang -->
           <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Barang / Fasilitas</label>
                <!-- Menampilkan nilai nama barang saat ini, tapi bisa diketik/diubah manual -->
                <input type="text" name="nama_barang" value="{{ $laporan->barang->nama_barang ?? '' }}" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: AC Ruang Meeting / Proyektor" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi/Ruangan (Opsional)</label>
                <input type="text" name="lokasi" value="{{ $laporan->lokasi ?? '' }}" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Foto Bukti Kerusakan -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Bukti Kerusakan (Opsional)</label>
                
                @if($laporan->foto)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Lama" class="w-16 h-16 object-cover rounded-lg border">
                        <span class="text-xs text-slate-500">Foto saat ini (biarkan kosong jika tidak ingin mengubah foto).</span>
                    </div>
                @endif

                <input type="file" name="foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
            </div>

            <!-- Deskripsi Kerusakan -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Kerusakan</label>
                <textarea name="deskripsi_kerusakan" rows="4" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>{{ $laporan->deskripsi_kerusakan }}</textarea>
            </div>
            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('karyawan.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-2.5 rounded-xl text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection