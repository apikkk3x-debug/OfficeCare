@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
        
        <!-- SISI KIRI: Panel Ilustrasi & Branding OfficeCare -->
        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-8 lg:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Efek Cahaya Background -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Badge -->
            <div class="relative z-10">
                <span class="inline-block px-2.5 py-1 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold tracking-widest uppercase rounded-lg mb-4">
                    OfficeCare System
                </span>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-wide leading-tight">
                    Kelola Fasilitas & Pengadaan Kantor Lebih Mudah.
                </h1>
                <p class="text-xs text-indigo-100/80 mt-2.5 leading-relaxed">
                    Daftarkan akun karyawan Anda untuk mengajukan permohonan pengadaan barang baru serta memantau status persetujuan secara real-time.
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
                        Sistem Verifikasi Admin Aktif
                    </span>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="relative z-10 text-[11px] text-slate-400 border-t border-slate-800 pt-4 flex justify-between items-center">
                <span>© 2026 OfficeCare</span>
                <a href="{{ route('login') }}" class="text-indigo-300 hover:text-white transition font-medium">Sudah punya akun? Login &rarr;</a>
            </div>
        </div>

        <!-- SISI KANAN: Formulir Register -->
        <div class="lg:col-span-7 p-8 lg:p-10 flex flex-col justify-center bg-white">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Pendaftaran Karyawan Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Lengkapi data diri di bawah ini. Akun memerlukan verifikasi dari Admin sebelum dapat digunakan.</p>
            </div>

            <!-- Notifikasi Error Validasi Global -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                    <span class="font-bold block">Terjadi kesalahan input:</span>
                    <ul class="list-disc list-inside text-[11px]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-rose-500 @enderror">
                </div>

                <!-- Grid NIK & Divisi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nomor Induk Karyawan (NIK) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required 
                               placeholder="Contoh: KRY-2026-001"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nik') border-rose-500 @enderror">
                    </div>

                    <div>
                        <label for="divisi" class="block text-xs font-semibold text-slate-700 mb-1">
                            Divisi / Unit Kerja <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="divisi" id="divisi" value="{{ old('divisi') }}" required 
                               placeholder="Contoh: Operasional / IT"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('divisi') border-rose-500 @enderror">
                    </div>
                </div>

                <!-- Alamat Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Email<span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           placeholder="budi@gmail.com"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-rose-500 @enderror">
                </div>

                <!-- Grid Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required 
                               placeholder="Minimal 8 karakter"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-rose-500 @enderror">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                               placeholder="Ulangi password"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                        Kirim Pendaftaran Akun
                    </button>
                </div>

                <div class="text-center pt-2 lg:hidden">
                    <p class="text-xs text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Masuk di sini</a></p>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection