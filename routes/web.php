<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\Karyawan\ProfileController;
use App\Http\Controllers\PengadaanController;

// ==========================================
// Rute Login & Logout (Publik)
// ==========================================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk Registrasi Karyawan Baru
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// ==========================================
// Rute Terproteksi (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------
    // A. Khusus Role Karyawan
    // ------------------------------------------
    Route::middleware(['role:karyawan'])->prefix('karyawan')->group(function () {
        Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
        
        // Form & Tindakan Laporan Kerusakan
        Route::get('/laporan/tambah', [KaryawanController::class, 'createLaporan'])->name('laporan.create');
        Route::get('/laporan', function () {
            return redirect()->route('laporan.create');
        });
        Route::post('/laporan', [KaryawanController::class, 'storeLaporan'])->name('laporan.store');
        Route::get('/laporan/{id}/edit', [KaryawanController::class, 'editLaporan'])->name('laporan.edit');
        Route::put('/laporan/{id}', [KaryawanController::class, 'updateLaporan'])->name('laporan.update');
        Route::delete('/laporan/{id}', [KaryawanController::class, 'destroyLaporan'])->name('laporan.destroy');
        
        // Riwayat & Komentar Laporan
        Route::get('/laporan-riwayat', [KaryawanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}', [KaryawanController::class, 'showLaporan'])->name('laporan.show');
        Route::post('/laporan/{id}/komentar', [KomentarController::class, 'store'])->name('laporan.komentar.store');
        
        // Pengadaan Barang Baru oleh Karyawan
        Route::get('/pengadaan/tambah', [PengadaanController::class, 'create'])->name('karyawan.pengadaan.create');
        Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('karyawan.pengadaan.store');
        Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('karyawan.pengadaan.index');
        Route::get('/pengadaan/{id}', [PengadaanController::class, 'show'])->name('karyawan.pengadaan.show');
Route::get('/pengadaan/{id}/edit', [PengadaanController::class, 'edit'])->name('karyawan.pengadaan.edit');
Route::put('/pengadaan/{id}', [PengadaanController::class, 'update'])->name('karyawan.pengadaan.update');
Route::delete('/pengadaan/{id}', [PengadaanController::class, 'destroy'])->name('karyawan.pengadaan.destroy');
        
    });

    // Profile & Ganti Password Karyawan
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/profil', [ProfileController::class, 'index'])->name('karyawan.profile');
        Route::put('/profil/update', [ProfileController::class, 'update'])->name('karyawan.profile.update');
        Route::get('/profil/update', function () {
            return redirect()->route('karyawan.profile');
        });

        Route::get('/profil/ganti-password', [ProfileController::class, 'editPassword'])->name('karyawan.password.edit');
        Route::put('/profil/ganti-password', [ProfileController::class, 'updatePassword'])->name('karyawan.password.update');
        Route::get('/profil/ganti-password/update', function () {
            return redirect()->route('karyawan.password.edit');
        });

        // Route AJAX OTP Ubah Password
        Route::post('/profil/ganti-password/send-otp', [ProfileController::class, 'sendOtp'])->name('karyawan.password.sendOtp');
        Route::post('/profil/ganti-password/verify-update', [ProfileController::class, 'verifyAndUpdatePassword'])->name('karyawan.password.verifyUpdate');
    });

  // ------------------------------------------
    // B. Khusus Role Admin
    // ------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Profile & Ganti Password Admin
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::get('/profile/ganti-password', [ProfileController::class, 'editPassword'])->name('admin.password.edit');
        Route::put('/profile/ganti-password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
        Route::post('/profile/ganti-password/send-otp', [ProfileController::class, 'sendOtp'])->name('admin.password.sendOtp');
        Route::post('/profile/ganti-password/verify-update', [ProfileController::class, 'verifyAndUpdatePassword'])->name('admin.password.verifyUpdate');
        
        // Kelola & Status Laporan Admin
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan.index');
        Route::get('/laporan/cetak', [AdminController::class, 'cetakLaporan'])->name('admin.cetakLaporan');
        Route::get('/laporan/{id}', [AdminController::class, 'showLaporan'])->name('admin.laporan.show');
        Route::put('/laporan/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.laporan.updateStatus');
        Route::post('/laporan/{id}/tanggapi', [AdminController::class, 'tanggapiLaporan'])->name('admin.laporan.tanggapi');
        
        // Pengadaan
        Route::put('/pengadaan/{id}/selesai', [AdminController::class, 'selesaikanPengadaan'])->name('admin.pengadaan.selesai');
        Route::get('/pengadaan', [AdminController::class, 'indexPengadaan'])->name('admin.pengadaan.index');

        // Manajemen Pengguna (Users)
        Route::get('/users', [AdminController::class, 'manajemenUser'])->name('admin.users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/users/{id}', [AdminController::class, 'hapusUser'])->name('admin.users.hapus');
        Route::put('/users/{id}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.updateStatus');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

        // Manajemen Aset / Barang
        Route::get('/barang', [BarangController::class, 'index'])->name('admin.barang.index');
        Route::post('/barang/store', [BarangController::class, 'store'])->name('admin.barang.store');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('admin.barang.destroy');
    });

  // ------------------------------------------
    // C. Khusus Role Pimpinan
    // ------------------------------------------
    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->group(function () {
        Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/laporan/cetak', [PimpinanController::class, 'cetakLaporan'])->name('pimpinan.laporan.cetak');

        // Rekap Laporan Kerusakan
        Route::get('/rekap', [PimpinanController::class, 'rekapLaporan'])->name('pimpinan.rekap');

        // Persetujuan (Approval) Pengadaan Barang oleh Pimpinan
        Route::get('/pengadaan', [PimpinanController::class, 'indexPengadaan'])->name('pimpinan.pengadaan.index');
        Route::put('/pengadaan/{pengadaan}/setujui', [PimpinanController::class, 'setujuiPengadaan'])->name('pimpinan.pengadaan.setujui');
        Route::put('/pengadaan/{pengadaan}/tolak', [PimpinanController::class, 'tolakPengadaan'])->name('pimpinan.pengadaan.tolak');

        // Profil & Ganti Password Pimpinan (Lengkap dengan OTP)
        Route::get('/profil', [ProfileController::class, 'index'])->name('pimpinan.profile');
        Route::put('/profil/update', [ProfileController::class, 'update'])->name('pimpinan.profile.update');
        Route::get('/profil/ganti-password', [ProfileController::class, 'editPassword'])->name('pimpinan.password.edit');
        Route::put('/profil/ganti-password', [ProfileController::class, 'updatePassword'])->name('pimpinan.password.update');
        Route::post('/profil/ganti-password/send-otp', [ProfileController::class, 'sendOtp'])->name('pimpinan.password.sendOtp');
        Route::post('/profil/ganti-password/verify-update', [ProfileController::class, 'verifyAndUpdatePassword'])->name('pimpinan.password.verifyUpdate');
    });

});