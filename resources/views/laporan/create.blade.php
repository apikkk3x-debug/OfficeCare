@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-[rgb(255,232,157)] p-6 rounded-xl shadow-sm border border-amber-400 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Form Laporan Pengaduan</h2>
            <p class="text-slate-500 text-sm mt-1">Laporkan pengaduan terkait fasilitas kantor. Jika belum ada, Anda bisa menambahkannya.</p>
        </div>
        <a href="{{ route('karyawan.dashboard') }}" class="bg-[rgb(255,218,97)] text-slate-600 hover:text-slate-900 border-amber-400 text-sm font-semibold border px-3 py-1.5 rounded-lg transition">
            ← Kembali
        </a>
    </div>

    <div class="bg-[rgb(255,232,157)]  p-6 rounded-xl shadow-sm border border-amber-400">
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Pilihan Barang -->
            <div class="mb-4">
                <label class=" block text-slate-700 text-sm font-semibold mb-2">Pilih Barang Fasilitas</label>
                <select name="id_barang" id="select_barang" onchange="cekBarangBaru(this)" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-[rgb(255,247,222)] text-sm">
                    <option value="">-- Pilih Barang / Fasilitas --</option>
                    @foreach($barangFasilitas as $barang)
                        <option value="{{ $barang->id_barang }}">
                            {{ $barang->nama_barang }} — (Lokasi: {{ $barang->lokasi }})
                        </option>
                    @endforeach
                    <option value="tambah_baru" class="font-bold text-blue-600">+ Tambah Barang / fasilitas Baru...</option>
                </select>
            </div>

            <!-- Input Tersembunyi untuk Barang Baru (Muncul jika pilih 'tambah_baru') -->
            <div id="form_barang_baru" class="hidden p-4 bg-slate-50 rounded-lg border border-slate-200 mb-4 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600">Form Tambah Barang / Fasilitas Baru</h4>
                <div>
                    <label class="block text-slate-700 text-xs font-semibold mb-1">Nama Barang / Fasilitas Baru</label>
                    <input type="text" name="nama_barang_baru" placeholder="Contoh: AC LG 2 PK" class="w-full px-3 py-2 border rounded-lg text-sm bg-[rgb(255,247,222)]">
                </div>
                <div>
                    <label class="block text-slate-700 text-xs font-semibold mb-1">Lokasi / Ruangan</label>
                    <input type="text" name="lokasi_baru" placeholder="Contoh: Ruang Meeting B" class="w-full px-3 py-2 border rounded-lg text-sm bg-[rgb(255,247,222)]">
                </div>
            </div>

            <!-- Upload Foto -->
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Upload Foto Bukti Kendala / Masalah dengan benar</label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg" class="w-full px-3 py-2 border rounded-lg text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700">
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi Pengaduan</label>
                <textarea name="deskripsi_kerusakan" rows="3" required placeholder="Jelaskan kendalanya..." class="w-full px-3 py-2 border rounded-lg text-sm bg-[rgb(255,247,222)]"></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('karyawan.dashboard') }}" class="bg-red-400 text-slate-100 px-4 py-2 rounded-lg text-sm font-semibold">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-400">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk memunculkan form barang baru secara otomatis -->
<script>
function cekBarangBaru(select) {
    const formBaru = document.getElementById('form_barang_baru');
    if (select.value === 'tambah_baru') {
        formBaru.classList.remove('hidden');
    } else {
        formBaru.classList.add('hidden');
    }
}
</script>
@endsection