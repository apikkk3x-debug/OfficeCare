@extends('layouts.app')

@section('content')
<!-- Fixed inset-0 mengambil alih 100% layar HP & Desktop tanpa melepas layouts.app -->
<div class="fixed inset-0 z-50 w-full h-full bg-slate-100 overflow-y-auto">
    
    <div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12 bg-slate-100">
        
        <!-- SISI KIRI: Panel Ilustrasi & Branding (Tampil Hanya di Desktop) -->
        <div class="hidden lg:flex lg:col-span-7 relative p-12 lg:p-16 text-white flex-col justify-between min-h-screen bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80')">
            
            <!-- Overlay Gelap -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-indigo-950/85 to-slate-900/90 backdrop-blur-[1px]"></div>

            <!-- Header Logo Desktop -->
            <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/logo.svg') }}" alt="OfficeCare Logo" class="w-14 h-14 object-contain drop-shadow-md">
                <span class="text-xl font-extrabold tracking-wider uppercase">
                    <span class="text-white">Office</span><span class="text-indigo-600">Care</span>
                </span>
            </div>

            <!-- Heading & Top Badge -->
            <div class="relative z-10 max-w-xl space-y-7 my-auto">
                <span class="inline-block px-3 py-1 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold tracking-widest uppercase rounded-lg">
                    Executive Command Center
                </span>
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                    Optimalkan Produktivitas & Manajemen Aset Kantor Anda.
                </h1>
                <p class="text-xs lg:text-sm text-indigo-100/90 leading-relaxed">
                    Masuk ke akun Anda untuk mengelola pengadaan barang kantor, memantau aset, dan persetujuan manajemen secara terpadu.
                </p>

                <!-- Kotak Fitur / Status -->
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center gap-3 shadow-lg mt-6">
                    <p class="text-xs text-slate-200 font-medium">Sistem Manajemen Kantor Aman & Terintegrasi untuk Operasional Perusahaan.</p>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="relative z-10 text-xs text-slate-300 pt-3 flex justify-between items-center max-w-xl border-t border-white/10">
                <span>Kantor OfficeCare Indonesia</span>
                <span>© 2026</span>
            </div>
        </div>

        <!-- SISI KANAN: Form Login (Presisi di Tengah Layar HP & Desktop) -->
        <div class="w-full lg:col-span-5 min-h-screen flex items-center justify-center bg-slate-100 p-4 sm:p-6 lg:p-10">
            
            <!-- Kartu White Card Login -->
            <div class="w-full max-w-md bg-white border border-slate-200/90 p-6 sm:p-8 rounded-3xl shadow-xl space-y-5">
                
                <!-- Mobile Brand Header (HP Only) -->
                <div class="flex lg:hidden items-center justify-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 p-1 bg-slate-900 rounded-lg shadow-sm border border-slate-800 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo.svg') }}" alt="OfficeCare Logo" class="w-full h-full object-contain">
                    </div>
                    
                    <span class="text-lg font-extrabold uppercase tracking-wider">
                        <span class="text-slate-900">Office</span><span class="text-blue-600">Care</span>
                    </span>
                </div>
                
                <div class="space-y-1 text-center sm:text-left">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">OfficeCare Login</h2>
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

                    <!-- Password Field -->
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
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer active:scale-[0.99]">
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