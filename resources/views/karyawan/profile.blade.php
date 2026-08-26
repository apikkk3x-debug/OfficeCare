@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <!-- Header Banner Profil -->
    <div class="bg-[rgb(255,232,157)] py-4 px-6 rounded-2xl shadow-sm border border-amber-400/80 mb-6">
        <h2 class="text-xl font-bold text-stone-800">Profil Saya</h2>
        <p class="text-stone-700 text-sm mt-0.5">Kelola informasi akun dan keamanan password kamu di sini.</p>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div id="success-alert" class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-semibold transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    <!-- Card Informasi Akun -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-stone-800 mb-5">Informasi Akun</h3>

        <!-- Form dengan enctype untuk upload file -->
        <form action="{{ route('karyawan.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tampilan Foto Profil Menyamping (Bisa Diklik & Bingkai Pas) -->
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100">
                
                <!-- Input file asli disembunyikan -->
                <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*">

                <!-- Bingkai foto profil yang bisa diklik -->
                <div onclick="document.getElementById('fotoInput').click();" class="w-32 h-32 rounded-full overflow-hidden bg-amber-100 border-2 border-amber-400 shadow-sm flex items-center justify-center shrink-0 cursor-pointer relative group transition hover:border-amber-600" title="Klik untuk ganti foto">
                    <div id="previewContainer" class="w-full h-full flex items-center justify-center">
                        @if(!empty($user->foto))
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-amber-800">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <!-- Efek overlay tulisan "Ubah" saat kursor diarahkan ke foto -->
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                        <span class="text-white text-xs font-semibold">Ubah</span>
                    </div>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Foto Profil</label>
                    <p class="text-xs text-slate-500 mb-1"></p>
                    <span id="fileNameDisplay" class="text-xs font-medium text-amber-700 block"></span>
                    @error('foto') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Input Nama -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none" required>
                @error('nama') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Input Email -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none" required>
                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-xl text-sm transition text-center shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('karyawan.password.edit') }}" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 rounded-xl text-sm transition text-center shadow-sm">
                    Ubah Password
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Interaksi Klik Foto & Live Preview -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto-hide notifikasi sukses
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        setTimeout(function() {
            alertBox.style.opacity = '0';
            setTimeout(function() {
                alertBox.remove();
            }, 500);
        }, 3000);
    }

    // Fungsi Preview Foto saat dipilih
    const fotoInput = document.getElementById('fotoInput');
    const previewContainer = document.getElementById('previewContainer');
    const fileNameDisplay = document.getElementById('fileNameDisplay');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                fileNameDisplay.textContent = "Terpilih: " + file.name;

                const reader = new FileReader();
                reader.onload = function(event) {
                    previewContainer.innerHTML = `<img src="${event.target.result}" alt="Preview Foto" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection