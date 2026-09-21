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
            
            <!-- Pilihan Barang (Alpine.js Searchable Dropdown - Berada di Dalam Form) -->
            <div class="relative" x-data="{ open: false, search: '', selectedText: '-- Pilih Barang / Fasilitas --', selectedId: '{{ old('id_barang') }}' }">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Pilih Barang / Fasilitas <span class="text-red-500">*</span>
                </label>

                <!-- Hidden input untuk mengirim ID ke database -->
                <input type="hidden" name="id_barang" x-model="selectedId" required>

                <!-- Kotak Trigger -->
                <button type="button" @click="open = !open" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm flex items-center justify-between text-left cursor-pointer">
                    <span x-text="selectedText"></span>
                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Menu Dropdown & Kotak Pencarian -->
                <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-1 bg-white border border-slate-300 rounded-xl shadow-lg overflow-hidden text-xs" style="display: none;">
                    
                    <!-- Input untuk mencari -->
                    <div class="p-2 border-b border-slate-200 bg-slate-50">
                        <input type="text" x-model="search" placeholder="Ketik untuk mencari nama barang atau lokasi..." class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-600 text-xs">
                    </div>

                    <!-- Daftar Pilihan -->
                    <div class="max-h-48 overflow-y-auto">
                        <div @click="selectedId = ''; selectedText = '-- Pilih Barang / Fasilitas --'; open = false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer text-slate-400">
                            -- Pilih Barang / Fasilitas --
                        </div>

                        @foreach($barangFasilitas as $barang)
                            <div x-show="('{{ strtolower($barang->nama_barang . ' ' . $barang->lokasi) }}').includes(search.toLowerCase())"
                                 @click="selectedId = '{{ $barang->id_barang }}'; selectedText = '{{ $barang->nama_barang }} — (Lokasi: {{ $barang->lokasi }})'; open = false;"
                                 class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-700 cursor-pointer text-slate-700 border-b border-slate-100 last:border-none">
                                {{ $barang->nama_barang }} — (Lokasi: {{ $barang->lokasi }})
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pilihan Tingkat Prioritas Kerusakan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Tingkat Prioritas Kerusakan <span class="text-red-500">*</span>
                </label>
                <select name="prioritas" class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none font-medium text-slate-800 shadow-sm cursor-pointer" required>
                    <option value="Rendah" {{ old('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah (Kerusakan ringan, tidak mengganggu operasional)</option>
                    <option value="Sedang" {{ old('prioritas', 'Sedang') == 'Sedang' ? 'selected' : '' }}>Sedang (Cukup mengganggu, perlu penanganan segera)</option>
                    <option value="Darurat" {{ old('prioritas') == 'Darurat' ? 'selected' : '' }}>Darurat / Tinggi (Bahaya / Sangat mengganggu aktivitas kantor)</option>
                </select>
                <p class="text-[11px] text-slate-500 mt-1.5">Pilih tingkat urgensi agar Admin dapat memprioritaskan penanganan dengan tepat.</p>
            </div>

            <!-- Upload Foto (Pilihan: Galeri atau Kamera Interaktif) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Upload Foto Bukti Kendala / Masalah</label>
                
                <!-- Pilihan Tombol Mode -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <label class="py-2.5 px-3 text-xs font-semibold rounded-xl border transition flex items-center justify-center gap-2 bg-indigo-600 text-white border-indigo-600 cursor-pointer hover:bg-indigo-700 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Pilih dari Galeri</span>
                        <input type="file" name="foto" id="inputFileGaleri" accept="image/png, image/jpeg, image/jpg" class="hidden" onchange="tampilkanNamaFile(this)">
                    </label>

                    <button type="button" onclick="bukaKameraModal()" class="py-2.5 px-3 text-xs font-semibold rounded-xl border transition flex items-center justify-center gap-2 bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700 cursor-pointer shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Foto Langsung (Kamera)</span>
                    </button>
                </div>

                <!-- Informasi File Terpilih / Hasil Foto -->
                <div class="text-xs text-slate-600 bg-white border border-slate-300 rounded-xl p-3 flex items-center justify-between shadow-sm">
                    <span id="teksNamaFile" class="font-medium">Tidak ada file yang dipilih atau foto diambil</span>
                    <span id="badgeStatusFoto" class="hidden px-2.5 py-1 bg-emerald-100 text-emerald-700 font-bold rounded-lg text-[10px] uppercase tracking-wider">Foto Siap</span>
                </div>

                <p class="text-[11px] text-slate-500 mt-1.5">Format yang didukung: JPG, JPEG, PNG (Maksimal 2MB).</p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Deskripsi Pengaduan <span class="text-red-500">*</span></label>
                <textarea name="deskripsi_kerusakan" rows="4" required placeholder="Jelaskan secara detail kendala atau kerusakan fasilitas yang ditemui..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition resize-none shadow-sm">{{ old('deskripsi_kerusakan') }}</textarea>
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

<!-- ================= MODAL KAMERA INTERAKTIF ================= -->
<div id="modalKamera" class="fixed inset-0 bg-slate-900/80 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-5 rounded-3xl shadow-2xl border border-slate-100 space-y-4 text-center">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-800">Ambil Foto Kendala Langsung</h3>
            <button type="button" onclick="tutupKameraModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
        </div>

        <!-- Area Pratinjau Kamera / Hasil Jepretan -->
        <div class="relative w-full bg-slate-900 rounded-2xl overflow-hidden aspect-video flex items-center justify-center">
            <video id="videoKamera" autoplay playsinline class="w-full h-full object-cover"></video>
            <canvas id="canvasFoto" class="hidden w-full h-full object-cover"></canvas>
        </div>

        <!-- Tombol Kontrol Kamera -->
        <div class="flex items-center justify-center gap-3 pt-2">
            <button type="button" id="btnAmbilFoto" onclick="ambilFoto()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-md cursor-pointer flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                <span>Ambil Foto Sekarang</span>
            </button>

            <!-- Tombol Konfirmasi (Muncul setelah foto dijepret) -->
            <div id="panelKonfirmasi" class="hidden flex gap-2 w-full">
                <button type="button" onclick="ulangiFoto()" class="flex-1 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer">
                    Ulangi Foto
                </button>
                <button type="button" onclick="simpanFotoKamera()" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer">
                    Gunakan Foto (OK)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript -->
<script>
let mediaStream = null;
let blobFileFoto = null;

function tampilkanNamaFile(input) {
    if (input.files && input.files[0]) {
        document.getElementById('teksNamaFile').innerText = "File galeri: " + input.files[0].name;
        document.getElementById('badgeStatusFoto').classList.remove('hidden');
    }
}

async function bukaKameraModal() {
    const modal = document.getElementById('modalKamera');
    const video = document.getElementById('videoKamera');
    const canvas = document.getElementById('canvasFoto');
    const btnAmbil = document.getElementById('btnAmbilFoto');
    const panelKonfirm = document.getElementById('panelKonfirmasi');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    canvas.classList.add('hidden');
    video.classList.remove('hidden');
    btnAmbil.classList.remove('hidden');
    panelKonfirm.classList.add('hidden');

    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
        video.srcObject = mediaStream;
    } catch (err) {
        alert("Gagal mengaktifkan kamera. Pastikan izin akses kamera diizinkan pada browser Anda.");
        tutupKameraModal();
    }
}

function tutupKameraModal() {
    const modal = document.getElementById('modalKamera');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
}

function ambilFoto() {
    const video = document.getElementById('videoKamera');
    const canvas = document.getElementById('canvasFoto');
    const btnAmbil = document.getElementById('btnAmbilFoto');
    const panelKonfirm = document.getElementById('panelKonfirmasi');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    video.classList.add('hidden');
    canvas.classList.remove('hidden');
    btnAmbil.classList.add('hidden');
    panelKonfirm.classList.remove('hidden');

    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
    }

    canvas.toBlob((blob) => {
        blobFileFoto = blob;
    }, 'image/jpeg', 0.9);
}

function ulangiFoto() {
    bukaKameraModal();
}

function simpanFotoKamera() {
    if (!blobFileFoto) return;

    const file = new File([blobFileFoto], "kamera_laporan_" + Date.now() + ".jpg", { type: "image/jpeg" });
    const container = new DataTransfer();
    container.items.add(file);

    const inputFile = document.getElementById('inputFileGaleri');
    inputFile.files = container.files;

    document.getElementById('teksNamaFile').innerText = "Foto Kamera Berhasil Diambil (" + file.name + ")";
    document.getElementById('badgeStatusFoto').classList.remove('hidden');

    tutupKameraModal();
}
</script>
@endsection