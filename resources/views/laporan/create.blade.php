@extends('layouts.app')

@section('content')
<!-- KONTEN FORM LAPORAN PENGADUAN -->
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Halaman (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-4 shadow-md text-white flex items-center justify-between">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Formulir Online
            </span>
            <h1 class="text-xl font-bold text-white tracking-wide">Form Laporan Pengaduan</h1>
            <p class="text-xs text-indigo-100/80 mt-1">Laporkan kendala fasilitas kantor. Anda juga dapat menambahkan data barang baru jika belum terdaftar.</p>
        </div>
        <div class="p-3 bg-white/10 backdrop-blur-md text-indigo-200 rounded-xl hidden sm:block shrink-0 border border-white/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </div>
    </div>

    <!-- Form Container (Nuansa Soft Slate Accent) -->
    <div class="bg-slate-100/80 border border-slate-200/90 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <!-- Pilihan Barang -->
            <div>
                <label for="select_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Pilih Barang / Fasilitas <span class="text-red-500">*</span>
                </label>
                <select name="id_barang" id="select_barang" onchange="cekBarangBaru(this)" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
                    <option value="">-- Pilih Barang / Fasilitas --</option>
                    @foreach($barangFasilitas as $barang)
                        <option value="{{ $barang->id_barang }}">
                            {{ $barang->nama_barang }} — (Lokasi: {{ $barang->lokasi }})
                        </option>
                    @endforeach
                    <option value="tambah_baru" class="font-bold text-indigo-600">+ Tambah Barang / Fasilitas Baru...</option>
                </select>
            </div>

            <!-- Input Tersembunyi untuk Barang Baru -->
            <div id="form_barang_baru" class="hidden p-4 bg-indigo-50 border border-indigo-200 rounded-xl space-y-3.5 transition-all">
                <div class="flex items-center gap-2 text-indigo-900 border-b border-indigo-200/60 pb-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Tambah Barang / Fasilitas Baru</h4>
                </div>
                
                <div>
                    <label class="block text-slate-700 text-xs font-semibold mb-1">Nama Barang / Fasilitas Baru <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang_baru" placeholder="Contoh: AC LG 2 PK" class="w-full px-3.5 py-2 bg-white border border-indigo-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                </div>
                
                <div>
                    <label class="block text-slate-700 text-xs font-semibold mb-1">Lokasi / Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_baru" placeholder="Contoh: Ruang Meeting B" class="w-full px-3.5 py-2 bg-white border border-indigo-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                </div>
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Upload Foto Bukti Kendala / Masalah</label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer bg-white border border-slate-300 rounded-xl p-1 transition shadow-sm">
                <p class="text-[11px] text-slate-500 mt-1.5">Format yang didukung: JPG, JPEG, PNG (Maksimal 2MB).</p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Deskripsi Pengaduan <span class="text-red-500">*</span></label>
                <textarea name="deskripsi_kerusakan" rows="4" required placeholder="Jelaskan secara detail kendala atau kerusakan fasilitas yang ditemui..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition resize-none shadow-sm"></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <a href="{{ route('karyawan.dashboard') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-md shadow-indigo-600/20">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script Toggle Form Barang Baru -->
<script>
function cekBarangBaru(select) {
    const formBaru = document.getElementById('form_barang_baru');
    const inputNama = formBaru.querySelector('input[name="nama_barang_baru"]');
    const inputLokasi = formBaru.querySelector('input[name="lokasi_baru"]');

    if (select.value === 'tambah_baru') {
        formBaru.classList.remove('hidden');
        inputNama.setAttribute('required', 'required');
        inputLokasi.setAttribute('required', 'required');
    } else {
        formBaru.classList.add('hidden');
        inputNama.removeAttribute('required');
        inputLokasi.removeAttribute('required');
    }
}
</script>
@endsection