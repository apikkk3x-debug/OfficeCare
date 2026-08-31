@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header Halaman (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 border border-indigo-700/50 p-4 rounded-2xl shadow-md text-white">
        <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
            Formulir Edit
        </span>
        <h2 class="text-xl font-bold text-white tracking-wide">Edit Laporan Kerusakan</h2>
        <p class="text-xs text-indigo-100/80 mt-1">Silakan perbarui data kerusakan fasilitas di bawah ini.</p>
    </div>

    <!-- Form Edit Laporan -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90">
        <form action="{{ route('laporan.update', $laporan->id_laporan ?? $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Pilih Barang -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Barang / Fasilitas</label>
                <input type="text" name="nama_barang" value="{{ $laporan->barang->nama_barang ?? '' }}" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-xs bg-white text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" placeholder="Contoh: AC Ruang Meeting / Proyektor" required>
            </div>

            <!-- Lokasi/Ruangan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Lokasi / Ruangan (Opsional)</label>
                <input type="text" name="lokasi" value="{{ $laporan->lokasi ?? $laporan->barang->lokasi ?? '' }}" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-xs bg-white text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm">
            </div>

            <!-- Foto Bukti Kerusakan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Foto Bukti Kerusakan (Opsional)</label>
                
                @if($laporan->foto_kondisi ?? $laporan->foto)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                        <img src="{{ asset('storage/' . ($laporan->foto_kondisi ?? $laporan->foto)) }}" alt="Foto Lama" class="w-16 h-16 object-cover rounded-lg border border-slate-200">
                        <span class="text-xs text-slate-500">Foto saat ini (biarkan kosong jika tidak ingin mengubah foto).</span>
                    </div>
                @endif

                <input type="file" name="foto" class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
            </div>

            <!-- Deskripsi Kerusakan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi Kerusakan</label>
                <textarea name="deskripsi_kerusakan" rows="4" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-xs bg-white text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" required>{{ $laporan->deskripsi_kerusakan }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20">
                    Simpan Perubahan
                </button>
                <a href="{{ route('laporan.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-5 py-2.5 rounded-xl text-xs transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection