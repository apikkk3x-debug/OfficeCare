@extends('layouts.app')

@section('content')
@php
    $role = strtolower($user->role ?? 'karyawan');
@endphp
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header Banner Profil -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md">
                    Pengaturan Akun
                </span>
                <span class="text-xs text-indigo-300 font-medium">• OfficeCare</span>
            </div>
            <h2 class="text-xl font-bold text-white tracking-wide">Profil Saya</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Kelola informasi data diri, foto profil, dan keamanan akun Anda.</p>
        </div>

        <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 shrink-0 shadow-sm">
            <div>
                <div class="text-[10px] text-indigo-200 capitalize mt-0.5">
                    Role: {{ $user->role ?? 'Karyawan' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Sukses Global (Hasil AJAX OTP/Ubah Sandi) -->
    <div id="global-alert" class="hidden p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold shadow-sm items-center gap-2.5">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span id="global-alert-msg"></span>
    </div>

    @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold transition-opacity duration-500 shadow-sm flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- GRID LAYOUT UTAMA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: BIODATA -->
        <div class="lg:col-span-2 bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Data Diri
                </h3>

                <div class="flex items-center gap-5 pb-6 border-b border-slate-200">
                    <form id="formFotoAuto" action="{{ route($role . '.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="nama" value="{{ $user->nama }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="file" name="foto" id="fotoInputDirect" accept="image/*" onchange="document.getElementById('formFotoAuto').submit();">
                    </form>

                    <div class="relative shrink-0">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-indigo-100 border-2 border-indigo-200 shadow-sm flex items-center justify-center">
                            @if(!empty($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="openPhotoModal('{{ asset('storage/' . $user->foto) }}')" title="Klik untuk memperbesar foto">
                            @else
                                <span class="text-2xl font-bold text-indigo-900">
                                    {{ strtoupper(substr($user->nama ?? $user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <button type="button" onclick="document.getElementById('fotoInputDirect').click();" class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full shadow-md transition transform hover:scale-105 cursor-pointer" title="Ganti Foto Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>

                    <div>
                        <h4 class="text-base font-bold text-slate-800">{{ $user->nama ?? $user->name }}</h4>
                        <p class="text-xs text-slate-500 mb-2.5">{{ $user->email }}</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            {{ ucfirst($user->role ?? 'Karyawan') }} Aktif
                        </span>
                    </div>
                </div>

                <div id="viewInfoSection" class="mt-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Nama Lengkap</span>
                            <span class="text-xs font-semibold text-slate-800">{{ $user->nama ?? $user->name }}</span>
                        </div>

                        <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Alamat Email (Gmail Asli)</span>
                            <span class="text-xs font-semibold text-slate-800">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 pt-3">
                        <button type="button" onclick="toggleEditForm()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-1.5 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Ubah Profil
                        </button>
                    </div>
                </div>

                <form id="editFormSection" action="{{ route($role . '.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden mt-5 space-y-4 pt-4 border-t border-slate-200">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama ?? $user->name) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email (Gmail Asli)</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 shadow-sm" required>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 pt-2">
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md cursor-pointer">Simpan Perubahan</button>
                        <button type="button" onclick="toggleEditForm()" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition cursor-pointer">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: KARTU KEAMANAN -->
        <div class="space-y-6">
            <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Keamanan Sandi</h3>
                    </div>
                    
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun OfficeCare.
                    </p>
                </div>

                <button type="button" onclick="openPasswordModal()" class="w-full px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold rounded-xl text-xs transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Ubah Password Akun
                </button>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Hak Akses Sistem</h3>
                <div class="space-y-2 text-xs text-slate-700">
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'Admin')
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Kelola Data Pengguna & Karyawan
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Verifikasi & Update Status Laporan Masuk
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Manajemen Data Barang & Fasilitas Kantor
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Pengaduan Fasilitas Kantor
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Pengajuan Barang Baru
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 font-medium">
                            <span>✓</span> Pantau Riwayat Real-Time
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= POPUP MODAL UBAH PASSWORD MULTI-MODE ================= -->
<div id="passwordModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-7 transform scale-95 transition-all duration-300 relative" id="modalCard">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base" id="modalTitle">Ubah Password Akun</h3>
                    <p class="text-[11px] text-slate-400" id="modalSubtitle">Perbarui kata sandi Anda</p>
                </div>
            </div>
            <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition cursor-pointer">✕</button>
        </div>

        <div id="modalAlert" class="hidden mt-4 p-3.5 rounded-xl text-xs font-semibold"></div>

        <!-- MODE 1: UBAH SANDI NORMAL (AJAX DENGAN TOGGLE MATA) -->
        <form id="modeDirect" onsubmit="submitDirectPassword(event)" class="mt-5 space-y-4">
            
            <!-- Kata Sandi Saat Ini -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input type="password" id="current_password" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="Masukkan kata sandi lama" required>
                    <button type="button" onclick="togglePassword('current_password', 'eyeCurrent')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Kata Sandi Baru -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="direct_password" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="Minimal 6 karakter" required>
                    <button type="button" onclick="togglePassword('direct_password', 'eyeDirectNew')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeDirectNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Kata Sandi Baru -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="direct_password_confirmation" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="Ulangi kata sandi baru" required>
                    <button type="button" onclick="togglePassword('direct_password_confirmation', 'eyeDirectConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeDirectConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" id="btnSubmitDirect" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                Simpan Kata Sandi Baru
            </button>

            <div class="pt-2 text-center border-t border-slate-100">
                <button type="button" onclick="switchToOtpMode()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline cursor-pointer">
                    Lupa Kata Sandi? Verifikasi via Gmail OTP
                </button>
            </div>
        </form>

        <!-- MODE 2 (STEP A): KIRIM OTP KE GMAIL -->
        <div id="modeOtpStep1" class="hidden mt-5 space-y-4">
            <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100/80 text-center">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Kode verifikasi OTP akan dikirimkan ke alamat email Gmail pribadi Anda:
                </p>
                <div class="font-bold text-indigo-900 text-sm mt-1 mb-2">{{ $user->email }}</div>
                <span class="text-[10px] text-indigo-600 font-medium bg-white px-2.5 py-1 rounded-full border border-indigo-200 inline-block">Reset Sandi via OTP</span>
            </div>

            <button type="button" id="btnSendOtp" onclick="triggerSendOtp()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 cursor-pointer">
                <span>Kirim Kode OTP Ke Gmail</span>
            </button>

            <div class="text-center pt-2">
                <button type="button" onclick="switchToDirectMode()" class="text-xs font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                    &larr; Kembali ke ubah password biasa
                </button>
            </div>
        </div>

        <!-- MODE 2 (STEP B): INPUT OTP & PASSWORD BARU DENGAN TOGGLE MATA -->
        <form id="modeOtpStep2" onsubmit="submitNewPassword(event)" class="hidden mt-5 space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kode OTP Gmail (6 Digit)</label>
                <input type="text" id="inputOtp" maxlength="6" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-center text-lg tracking-widest font-mono text-indigo-700 font-bold focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="123456" required>
            </div>

            <!-- Kata Sandi Baru (OTP) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="inputNewPassword" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="Minimal 8 karakter" required>
                    <button type="button" onclick="togglePassword('inputNewPassword', 'eyeOtpNew')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeOtpNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Kata Sandi Baru (OTP) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="inputConfirmPassword" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:bg-white" placeholder="Ulangi kata sandi baru" required>
                    <button type="button" onclick="togglePassword('inputConfirmPassword', 'eyeOtpConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeOtpConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" id="btnSubmitPassword" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 cursor-pointer">
                <span>Perbarui Kata Sandi</span>
            </button>
        </form>

    </div>
</div>

<!-- ================= MODAL POP-UP FOTO PROFIL ================= -->
<div id="photoModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none transition-all duration-300" onclick="closePhotoModal()">
    <div class="relative max-w-lg w-full bg-white rounded-3xl p-3 shadow-2xl transform scale-95 transition-all duration-300" onclick="event.stopPropagation()">
        <button type="button" onclick="closePhotoModal()" class="absolute -top-3 -right-3 bg-slate-900 hover:bg-slate-800 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition z-10 cursor-pointer">
            ✕
        </button>
        <div class="overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center max-h-[80vh]">
            <img id="modalImageSrc" src="" alt="Foto Profil Besar" class="w-full h-auto object-contain max-h-[75vh]">
        </div>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

document.addEventListener("DOMContentLoaded", function() {
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        alertBox.style.transition = 'opacity 0.5s ease';
        setTimeout(function() {
            alertBox.style.opacity = '0';
            setTimeout(function() { alertBox.remove(); }, 500);
        }, 2000);
    }
});

// Fungsi Toggle Lihat/Sembunyikan Sandi (Ikon Mata)
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordField.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

// Fungsi Pop-Up Foto Profil (Global)
function openPhotoModal(imageUrl) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalImageSrc');
    const modalContent = modal.querySelector('div > div');
    
    modalImg.src = imageUrl;
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modalContent.classList.remove('scale-95');
    modalContent.classList.add('scale-100');
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    const modalContent = modal.querySelector('div > div');
    
    modal.classList.add('opacity-0', 'pointer-events-none');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
}

function showGlobalAlert(message) {
    const globalAlert = document.getElementById('global-alert');
    const alertMsg = document.getElementById('global-alert-msg');
    
    alertMsg.innerText = message;
    globalAlert.classList.remove('hidden');
    globalAlert.classList.add('flex');
    globalAlert.style.opacity = '1';
    globalAlert.style.transition = 'opacity 0.5s ease';

    setTimeout(function() {
        globalAlert.style.opacity = '0';
        setTimeout(function() {
            globalAlert.classList.add('hidden');
            globalAlert.classList.remove('flex');
        }, 500);
    }, 2000);
}

function toggleEditForm() {
    document.getElementById('viewInfoSection').classList.toggle('hidden');
    document.getElementById('editFormSection').classList.toggle('hidden');
}

function openPasswordModal() {
    switchToDirectMode();
    const modal = document.getElementById('passwordModal');
    const modalCard = document.getElementById('modalCard');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modalCard.classList.remove('scale-95');
    modalCard.classList.add('scale-100');
}

function closePasswordModal() {
    const modal = document.getElementById('passwordModal');
    const modalCard = document.getElementById('modalCard');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modalCard.classList.remove('scale-100');
    modalCard.classList.add('scale-95');

    setTimeout(() => {
        switchToDirectMode();
        document.getElementById('modalAlert').classList.add('hidden');
        document.getElementById('modeDirect').reset();
        document.getElementById('modeOtpStep2').reset();
    }, 300);
}

function switchToDirectMode() {
    document.getElementById('modalTitle').innerText = 'Ubah Password Akun';
    document.getElementById('modalSubtitle').innerText = 'Perbarui kata sandi Anda';
    document.getElementById('modeDirect').classList.remove('hidden');
    document.getElementById('modeOtpStep1').classList.add('hidden');
    document.getElementById('modeOtpStep2').classList.add('hidden');
    document.getElementById('modalAlert').classList.add('hidden');
}

function switchToOtpMode() {
    document.getElementById('modalTitle').innerText = 'Reset Password via OTP';
    document.getElementById('modalSubtitle').innerText = 'Verifikasi kode keamanan Gmail';
    document.getElementById('modeDirect').classList.add('hidden');
    document.getElementById('modeOtpStep1').classList.remove('hidden');
    document.getElementById('modeOtpStep2').classList.add('hidden');
    document.getElementById('modalAlert').classList.add('hidden');
}

function showModalAlert(msg, isSuccess = true) {
    const alert = document.getElementById('modalAlert');
    alert.classList.remove('hidden', 'bg-rose-50', 'text-rose-700', 'border-rose-200', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
    if (isSuccess) {
        alert.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
    } else {
        alert.classList.add('bg-rose-50', 'text-rose-700', 'border', 'border-rose-200');
    }
    alert.innerText = msg;
}

// FUNGSI AJAX MODE UBAH PASSWORD BIASA (DIRECT)
function submitDirectPassword(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitDirect');
    const currentPassword = document.getElementById('current_password').value;
    const password = document.getElementById('direct_password').value;
    const passwordConfirm = document.getElementById('direct_password_confirmation').value;

    if (password !== passwordConfirm) {
        showModalAlert('Konfirmasi kata sandi tidak cocok!', false);
        return;
    }

    btn.disabled = true;
    btn.innerHTML = `<span>Memproses...</span>`;

    fetch('{{ route($role . ".password.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify({
            current_password: currentPassword,
            password: password,
            password_confirmation: passwordConfirm
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `Simpan Kata Sandi Baru`;
        if (data.success) {
            closePasswordModal();
            showGlobalAlert(data.message || 'Kata sandi berhasil diperbarui.');
        } else {
            showModalAlert(data.message || 'Gagal mengubah kata sandi.', false);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = `Simpan Kata Sandi Baru`;
        showModalAlert('Terjadi kesalahan koneksi sistem.', false);
    });
}

// FUNGSI KIRIM OTP KE GMAIL ASLI AKUN MASING-MASING
function triggerSendOtp() {
    const btn = document.getElementById('btnSendOtp');
    btn.disabled = true;
    btn.innerHTML = `<span>Mengirim OTP ke Gmail...</span>`;

    fetch('{{ route($role . ".password.sendOtp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `<span>Kirim Kode OTP Ke Gmail</span>`;
        if (data.success) {
            document.getElementById('modeOtpStep1').classList.add('hidden');
            document.getElementById('modeOtpStep2').classList.remove('hidden');
            showModalAlert(data.message, true);
        } else {
            showModalAlert(data.message, false);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = `<span>Kirim Kode OTP Ke Gmail</span>`;
        showModalAlert('Terjadi kesalahan koneksi atau SMTP Gmail.', false);
    });
}

// FUNGSI VERIFIKASI OTP & UPDATE PASSWORD BARU
function submitNewPassword(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitPassword');
    const otp = document.getElementById('inputOtp').value;
    const password = document.getElementById('inputNewPassword').value;
    const passwordConfirm = document.getElementById('inputConfirmPassword').value;

    if (password !== passwordConfirm) {
        showModalAlert('Konfirmasi kata sandi tidak cocok!', false);
        return;
    }

    btn.disabled = true;
    btn.innerHTML = `<span>Memproses...</span>`;

    fetch('{{ route($role . ".password.verifyUpdate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            otp: otp,
            password: password,
            password_confirmation: passwordConfirm
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `<span>Perbarui Kata Sandi</span>`;
        if (data.success) {
            closePasswordModal();
            showGlobalAlert(data.message);
        } else {
            showModalAlert(data.message, false);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = `<span>Perbarui Kata Sandi</span>`;
        showModalAlert('Gagal memperbarui kata sandi.', false);
    });
}
</script>
@endsection