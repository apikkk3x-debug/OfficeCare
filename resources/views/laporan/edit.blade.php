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

            <!-- Pilihan Barang / Fasilitas -->
            <div>
                <label for="select_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Pilih Barang / Fasilitas <span class="text-red-500">*</span>
                </label>
                <select name="id_barang" id="select_barang" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
                    <option value="">-- Pilih Barang / Fasilitas --</option>
                    @foreach($barangFasilitas as $barang)
                        <option value="{{ $barang->id_barang }}" {{ (isset($laporan) && $laporan->id_barang == $barang->id_barang) ? 'selected' : '' }}>
                            {{ $barang->nama_barang }} — (Lokasi: {{ $barang->lokasi }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Upload Foto (Pilihan: Galeri atau Kamera Interaktif) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Foto Bukti Kerusakan (Opsional)</label>
                
                <!-- Pratinjau Foto Saat Ini -->
                @if($laporan->foto_kondisi ?? $laporan->foto)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                        <img src="{{ asset('storage/' . ($laporan->foto_kondisi ?? $laporan->foto)) }}" alt="Foto Lama" class="w-16 h-16 object-cover rounded-lg border border-slate-200">
                        <span class="text-xs text-slate-500">Foto saat ini (akan diganti jika Anda mengunggah atau memotret yang baru).</span>
                    </div>
                @endif

                <!-- Pilihan Tombol Mode Upload -->
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
                    <span id="teksNamaFile" class="font-medium">Tidak ada file baru yang dipilih / diambil</span>
                    <span id="badgeStatusFoto" class="hidden px-2.5 py-1 bg-emerald-100 text-emerald-700 font-bold rounded-lg text-[10px] uppercase tracking-wider">Foto Siap</span>
                </div>

                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
            </div>

            <!-- Deskripsi Kerusakan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                <textarea name="deskripsi_kerusakan" rows="4" class="w-full rounded-xl border-slate-300 border px-4 py-2.5 text-xs bg-white text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm resize-none" required>{{ $laporan->deskripsi_kerusakan }}</textarea>
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

<!-- Script JavaScript Kamera & File -->
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

    const file = new File([blobFileFoto], "kamera_edit_" + Date.now() + ".jpg", { type: "image/jpeg" });
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