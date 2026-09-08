@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
        
        <!-- SISI KIRI: Panel Ilustrasi & Branding OfficeCare -->
        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-8 lg:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Efek Cahaya Background -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Content -->
            <div class="relative z-10">
                <span class="inline-block px-2.5 py-1 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold tracking-widest uppercase rounded-lg mb-4">
                    OfficeCare System
                </span>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-wide leading-tight">
                    Selamat Datang Kembali di OfficeCare.
                </h1>
                <p class="text-xs text-indigo-100/80 mt-2.5 leading-relaxed">
                    Masuk ke akun Anda untuk mengelola pengadaan barang kantor, memantau aset, dan persetujuan manajemen secara terpadu.
                </p>
            </div>

            <!-- Ilustrasi / Gambar Ruang Kerja Kantor Modern -->
            <div class="relative z-10 my-6 rounded-2xl overflow-hidden border border-white/10 shadow-lg group">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" 
                     alt="Office Workspace" 
                     class="w-full h-44 object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                    <span class="text-[11px] font-medium text-slate-200 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Manajemen Kantor Aman & Terintegrasi
                    </span>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="relative z-10 text-[11px] text-slate-400 border-t border-slate-800 pt-4 flex justify-between items-center">
                <span>© 2026 OfficeCare</span>
                <a href="{{ route('register') }}" class="text-indigo-300 hover:text-white transition font-medium">Belum punya akun? Daftar &rarr;</a>
            </div>
        </div>

        <!-- SISI KANAN: Formulir Login -->
        <div class="lg:col-span-7 p-8 lg:p-10 flex flex-col justify-center bg-white">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">OfficeCare Login</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan email dan password terdaftar Anda untuk masuk ke sistem.</p>
            </div>

            <!-- Notifikasi Error / Status Pending dari Controller -->
            @if(session('error'))
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           placeholder="nama@officecare.com"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-rose-500 @enderror">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required 
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-rose-500 @enderror">
                </div>

                <!-- Tombol Masuk -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                        Masuk
                    </button>
                </div>

                <div class="text-center pt-2 lg:hidden">
                    <p class="text-xs text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar di sini</a></p>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection