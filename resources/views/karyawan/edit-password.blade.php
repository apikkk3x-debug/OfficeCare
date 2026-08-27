@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    
    <!-- Header Banner Ubah Password (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 border border-indigo-700/50 p-6 rounded-2xl shadow-md text-white flex justify-between items-center">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Keamanan Akun
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Ubah Password</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Amankan akun kamu dengan memperbarui password secara berkala.</p>
        </div>
        
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold transition-opacity duration-500 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- CARD UTAMA: FORM UBAH PASSWORD -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90">
        <form action="{{ route('karyawan.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Password Lama -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password Lama</label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm pr-10" required>
                    <button type="button" onclick="togglePassword('current_password', 'icon_current')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg id="icon_current" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('current_password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm pr-10" required>
                    <button type="button" onclick="togglePassword('password', 'icon_password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg id="icon_password" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm pr-10" required>
                    <button type="button" onclick="togglePassword('password_confirmation', 'icon_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg id="icon_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20">
                    Simpan Password Baru
                </button>
                <a href="{{ route('karyawan.profile') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-sm border border-slate-300"">
                    <span>←</span>
                    <span>Kembali</span>
                </a>
            </div>
            <div class="text-[10px] text-slate-500 mt-1.5">
                <span class="font-semibold">Catatan:</span> Pastikan password baru berbeda dari password lama dan memiliki kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik.
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Toggle SVG Mata & Auto-Hide Notifikasi -->
<script>
function togglePassword(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.012-3.488m3.56-2.56A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
        `;
    } else {
        input.type = "password";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

// Script agar notifikasi sukses hilang otomatis setelah 3 detik
document.addEventListener("DOMContentLoaded", function() {
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        setTimeout(function() {
            alertBox.style.opacity = '0';
            setTimeout(function() {
                alertBox.remove();
            }, 500);
        }, 3000);
    }
});
</script>
@endsection