@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Header Banner Profil (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 border border-indigo-700/50 p-4 rounded-2xl shadow-md text-white">
        <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
            Pengaturan Akun
        </span>
        <h2 class="text-xl font-bold text-white tracking-wide">Profil Saya</h2>
        <p class="text-xs text-indigo-100/80 mt-1">Kelola informasi akun dan keamanan password kamu di sini.</p>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold transition-opacity duration-500 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- CARD UTAMA: INFORMASI AKUN -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-5">Informasi Akun</h3>

        <!-- Header Foto & Ringkasan User -->
        <div class="flex items-center gap-5 pb-6 border-b border-slate-200">
            <!-- Form Upload Foto Tersembunyi -->
            <form id="formFotoAuto" action="{{ route('karyawan.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PUT')
                <input type="hidden" name="nama" value="{{ $user->nama }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <input type="file" name="foto" id="fotoInputDirect" accept="image/*" onchange="document.getElementById('formFotoAuto').submit();">
            </form>

            <!-- Avatar + Floating Button Kamera -->
            <div class="relative shrink-0">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-indigo-100 border-2 border-indigo-200 shadow-sm flex items-center justify-center">
                    @if(!empty($user->foto))
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-indigo-900">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <!-- Tombol Kamera Melayang -->
                <button type="button" onclick="document.getElementById('fotoInputDirect').click();" class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full shadow-md transition transform hover:scale-105" title="Ganti Foto Profil">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            <!-- Teks Nama & Role -->
            <div>
                <h4 class="text-base font-bold text-slate-800">{{ $user->nama }}</h4>
                <p class="text-xs text-slate-500 mb-2">{{ $user->email }}</p>
                <span class="inline-block px-3 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-full border border-indigo-200">
                    Karyawan
                </span>
            </div>
        </div>

        <!-- INFORMASI STATIS (READ-ONLY) -->
        <div id="viewInfoSection" class="mt-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Nama Lengkap</span>
                    <span class="text-xs font-semibold text-slate-800">{{ $user->nama }}</span>
                </div>

                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Alamat Email</span>
                    <span class="text-xs font-semibold text-slate-800">{{ $user->email }}</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-2.5 pt-3">
                <button type="button" onclick="toggleEditForm()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Ubah Profil
                </button>
                <a href="{{ route('karyawan.password.edit') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Ubah Password
                </a>
            </div>
        </div>

        <!-- FORM EDIT PROFIL -->
        <form id="editFormSection" action="{{ route('karyawan.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden mt-5 space-y-4 pt-4 border-t border-slate-200">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" required>
                    @error('nama') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" required>
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2.5 pt-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="toggleEditForm()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        setTimeout(function() {
            alertBox.style.opacity = '0';
            setTimeout(function() { alertBox.remove(); }, 500);
        }, 3000);
    }
});

function toggleEditForm() {
    const viewSection = document.getElementById('viewInfoSection');
    const editSection = document.getElementById('editFormSection');
    
    viewSection.classList.toggle('hidden');
    editSection.classList.toggle('hidden');
}
</script>
@endsection