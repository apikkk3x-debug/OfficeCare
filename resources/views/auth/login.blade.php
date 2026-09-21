@extends('layouts.app')

@section('content')
<!-- Memaksa tinggi kontainer menutupi penuh layar (h-screen) dan menetralkan margin layout utama -->
<div class="h-screen w-full -m-6 lg:-m-8 grid grid-cols-1 lg:grid-cols-12 bg-white overflow-hidden">
    
    <!-- SISI KIRI: Panel Ilustrasi & Branding -->
    <div class="lg:col-span-7 relative p-8 lg:p-16 text-white flex flex-col justify-center space-y-6 overflow-hidden h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80')">
        
        <!-- Lapisan Overlay Gelap Transparan -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/85 via-indigo-950/80 to-slate-900/85 backdrop-blur-[1px]"></div>

        <!-- Heading & Top Badge -->
        <div class="relative z-10 max-w-xl space-y-3">
            <span class="inline-block px-3 py-1 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold tracking-widest uppercase rounded-lg">
                OfficeCare System
            </span>
            <h1 class="text-3xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                Optimalkan Produktivitas & Manajemen Aset Kantor Anda.
            </h1>
            <p class="text-xs lg:text-sm text-indigo-100/90 leading-relaxed">
                Masuk ke akun Anda untuk mengelola pengadaan barang kantor, memantau aset, dan persetujuan manajemen secara terpadu.
            </p>
        </div>

        <!-- Kotak Fitur / Status -->
        <div class="relative z-10 max-w-xl p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center gap-3 shadow-lg">
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <p class="text-xs text-slate-200 font-medium">Sistem Manajemen Kantor Aman & Terintegrasi untuk Operasional Perusahaan.</p>
        </div>

        <!-- Footer Info -->
        <div class="relative z-10 text-xs text-slate-300 pt-3 flex justify-between items-center max-w-xl border-t border-white/10">
            <span>Kantor OfficeCare Indonesia</span>
            <span>© 2026</span>
        </div>
    </div>

    <!-- SISI KANAN: Formulir Login dengan Card Rapi -->
    <div class="lg:col-span-5 p-6 lg:p-10 flex flex-col justify-center items-center bg-slate-50/60 h-full overflow-y-auto">
        <div class="max-w-md w-full bg-white border border-slate-200/90 p-8 rounded-3xl shadow-xl space-y-5">
            
            <div class="space-y-1">
                <h2 class="text-xl font-bold text-slate-900">OfficeCare Login</h2>
                <p class="text-xs text-slate-500">Masukkan email dan password terdaftar Anda.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@officecare.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:ring-indigo-500 @enderror rounded-xl text-xs focus:ring-2 focus:bg-white transition">
                    
                    @error('email')
                        <p class="text-rose-500 text-[11px] mt-1 font-medium flex items-center gap-1">
                            <span>⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field dengan Toggle Show/Hide -->
                <div>
                    <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                               placeholder="••••••••"
                               class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border @error('password') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:ring-indigo-500 @enderror rounded-xl text-xs focus:ring-2 focus:bg-white transition">
                        
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-rose-500 text-[11px] mt-1 font-medium flex items-center gap-1">
                            <span>⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tombol Masuk -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                        Masuk
                    </button>
                </div>

                <!-- Teks Belum Punya Akun -->
                <div class="text-center pt-3 border-t border-slate-100 mt-4">
                    <p class="text-xs text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar &rarr;</a></p>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Script JavaScript untuk Toggle Show/Hide Password -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>
@endsection