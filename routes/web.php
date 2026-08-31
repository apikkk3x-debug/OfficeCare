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
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// Rute Terproteksi (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------
    // A. Khusus Role Karyawan
    // ------------------------------------------
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
        Route::get('/karyawan/laporan/tambah', [KaryawanController::class, 'createLaporan'])->name('laporan.create');
        Route::get('/karyawan/laporan', function () {
            return redirect()->route('laporan.create');
        });
        Route::post('/karyawan/laporan', [KaryawanController::class, 'storeLaporan'])->name('laporan.store');
        Route::get('/karyawan/laporan/{id}/edit', [KaryawanController::class, 'editLaporan'])->name('laporan.edit');
        Route::put('/karyawan/laporan/{id}', [KaryawanController::class, 'updateLaporan'])->name('laporan.update');
        Route::delete('/karyawan/laporan/{id}', [KaryawanController::class, 'destroyLaporan'])->name('laporan.destroy');
        Route::get('/laporan', [KaryawanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}', [KaryawanController::class, 'showLaporan'])->name('laporan.show');
        Route::post('/laporan/{id}/komentar', [KomentarController::class, 'store'])->name('laporan.komentar.store');
        
        // Rute untuk pengajuan pengadaan barang baru oleh Karyawan
        Route::get('/karyawan/pengadaan/tambah', [PengadaanController::class, 'create'])->name('karyawan.pengadaan.create');
        Route::post('/karyawan/pengadaan', [PengadaanController::class, 'store'])->name('karyawan.pengadaan.store');
        Route::get('/karyawan/pengadaan', [PengadaanController::class, 'index'])->name('karyawan.pengadaan.index');
        // Profil Karyawan
        Route::get('/profil', [ProfileController::class, 'index'])->name('karyawan.profile');
        Route::put('/profil/update', [ProfileController::class, 'update'])->name('karyawan.profile.update');
        Route::get('/profil/update', function () {
            return redirect()->route('karyawan.profile');
        });

        // Ganti Password
        Route::get('/profil/ganti-password', [ProfileController::class, 'editPassword'])->name('karyawan.password.edit');
        Route::put('/profil/ganti-password', [ProfileController::class, 'updatePassword'])->name('karyawan.password.update');
        Route::get('/profil/ganti-password/update', function () {
            return redirect()->route('karyawan.password.edit');
        });
    });

    // ------------------------------------------
    // B. Khusus Role Admin
    // ------------------------------------------
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Laporan Admin
        Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan.index');
        Route::get('/admin/laporan/cetak', [AdminController::class, 'cetakLaporan'])->name('admin.cetakLaporan');
        Route::get('/admin/laporan/{id}', [AdminController::class, 'showLaporan'])->name('admin.laporan.show');
        Route::put('/admin/laporan/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.laporan.updateStatus');
        Route::post('/admin/laporan/{id}/tanggapi', [AdminController::class, 'tanggapiLaporan'])->name('admin.laporan.tanggapi');

        // Manajemen Pengguna oleh Admin
        Route::get('/admin/users', [AdminController::class, 'manajemenUser'])->name('admin.users');
        Route::delete('/admin/users/{id}', [AdminController::class, 'hapusUser'])->name('admin.users.hapus');

        // Manajemen Barang oleh Admin
        Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang.index');
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    });

    // ------------------------------------------
    // C. Khusus Role Pimpinan
    // ------------------------------------------
    Route::middleware(['role:pimpinan'])->group(function () {
        Route::get('/pimpinan/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/pimpinan/laporan/cetak', [PimpinanController::class, 'cetakLaporan'])->name('pimpinan.laporan.cetak');

        // Rute untuk menampilkan rekap laporan kerusakan
        Route::get('/rekap', [PimpinanController::class, 'rekapLaporan'])->name('pimpinan.rekap');

        // Route Approval Pengadaan Barang (Pimpinan)
        Route::get('/pimpinan/pengadaan', [PimpinanController::class, 'indexPengadaan'])->name('pimpinan.pengadaan.index');
        Route::put('/pimpinan/pengadaan/{id}/setujui', [PimpinanController::class, 'setujuiPengadaan'])->name('pimpinan.pengadaan.setujui');
        Route::put('/pimpinan/pengadaan/{id}/tolak', [PimpinanController::class, 'tolakPengadaan'])->name('pimpinan.pengadaan.tolak');
        });

});