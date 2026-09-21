@extends('layouts.app')

@section('content')
<!-- Memaksa tinggi kontainer menutupi penuh layar (h-screen) dan menetralkan margin layout utama -->
<div class="h-screen w-full -m-6 lg:-m-8 grid grid-cols-1 lg:grid-cols-12 bg-white overflow-hidden">
    
    <!-- SISI KIRI: Panel Ilustrasi & Branding (Diposisikan di Tengah secara Vertikal agar Tidak Kosong) -->
    <div class="lg:col-span-7 relative p-8 lg:p-16 text-white flex flex-col justify-center space-y-6 overflow-hidden h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80')">
        
        <!-- Lapisan Overlay Gelap Transparan -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/85 via-indigo-950/80 to-slate-900/85 backdrop-blur-[1px]"></div>

        <!-- Heading & Top Badge -->
        <div class="relative z-10 max-w-xl space-y-3">
            <span class="inline-block px-3 py-1 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold tracking-widest uppercase rounded-lg">
                OfficeCare System
            </span>
            <h1 class="text-3xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                Bergabung dengan Ekosistem Kerja Digital OfficeCare.
            </h1>
            <p class="text-xs lg:text-sm text-indigo-100/90 leading-relaxed">
                Daftarkan akun Anda untuk mulai mengajukan kebutuhan inventaris divisi, memantau riwayat pengadaan, dan melaporkan pemeliharaan fasilitas kantor secara terpadu.
            </p>
        </div>

        <!-- Kotak Fitur / Status -->
        <div class="relative z-10 max-w-xl p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center gap-3 shadow-lg">
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <p class="text-xs text-slate-200 font-medium">Sistem Verifikasi Admin Aktif & Terintegrasi untuk Keamanan Aset Kantor.</p>
        </div>

        <!-- Footer Info -->
        <div class="relative z-10 text-xs text-slate-300 pt-3 flex justify-between items-center max-w-xl border-t border-white/10">
            <span>Kantor OfficeCare Indonesia</span>
            <span>© 2026</span>
        </div>
    </div>

    <!-- SISI KANAN: Formulir Register dengan Card Rapi -->
    <div class="lg:col-span-5 p-6 lg:p-10 flex flex-col justify-center items-center bg-slate-50/60 h-full overflow-y-auto">
        <div class="max-w-md w-full bg-white border border-slate-200/90 p-8 rounded-3xl shadow-xl space-y-4">
            
            <div class="space-y-1">
                <h2 class="text-xl font-bold text-slate-900">Registrasi Karyawan Baru</h2>
                <p class="text-xs text-slate-500">Lengkapi data identitas Anda secara akurat untuk verifikasi akun.</p>
            </div>

            <!-- Notifikasi Error Validasi Global -->
            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                    <span class="font-bold block">Terjadi kesalahan input:</span>
                    <ul class="list-disc list-inside text-[11px]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Masukkan nama lengkap beserta gelar"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- Divisi Kerja -->
                <div>
                    <label for="divisi" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Divisi Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="divisi" id="divisi" value="{{ old('divisi') }}" required 
                           placeholder="Operasional / IT"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- Alamat Email -->
                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Email Aktif <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           placeholder="nama@email.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- Grid Password & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required 
                               placeholder="Min. 6 karakter"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Konfirmasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                               placeholder="Ulangi password"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                        Kirim Pendaftaran Akun
                    </button>
                </div>

                <!-- Teks Login -->
                <div class="text-center pt-3 border-t border-slate-100 mt-3">
                    <p class="text-xs text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Login &rarr;</a></p>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection