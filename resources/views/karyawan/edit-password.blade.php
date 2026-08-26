@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <div class="bg-[rgb(255,232,157)] p-6 rounded-2xl shadow-sm border border-amber-400/80 mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-stone-800">Ubah Password</h2>
            <p class="text-stone-700 text-sm mt-1">Amankan akun kamu dengan memperbarui password secara berkala.</p>
        </div>
        <a href="{{ route('karyawan.profile') }}" class="inline-flex items-center gap-2 bg-white/80 hover:bg-white text-stone-800 px-4 py-2 rounded-xl text-sm font-semibold transition border border-amber-300 shrink-0">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Tambahkan id="success-alert" dan kelas transisi di sini -->
    @if(session('success'))
        <div id="success-alert" class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-semibold transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('karyawan.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Password Lama -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password Lama</label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none pr-10" required>
                    <button type="button" onclick="togglePassword('current_password', 'icon_current')" class="absolute inset-y-0 right-0 px-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <svg id="icon_current" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('current_password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none pr-10" required>
                    <button type="button" onclick="togglePassword('password', 'icon_password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <svg id="icon_password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none pr-10" required>
                    <button type="button" onclick="togglePassword('password_confirmation', 'icon_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <svg id="icon_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                Simpan Password Baru
            </button>
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