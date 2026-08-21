<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KomentarController;

// Rute Login & Logout (Publik)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang Harus Login Dulu
Route::middleware(['auth'])->group(function () {
    
    // A. Dashboard Karyawan & Laporan
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
    Route::get('/karyawan/laporan/tambah', [KaryawanController::class, 'createLaporan'])->name('laporan.create');
    // Route untuk menampilkan formulir tambah laporan
    Route::get('/karyawan/laporan', function () {
        return redirect()->route('laporan.create');
    });
    // Route untuk menyimpan laporan karyawan
    Route::post('/karyawan/laporan', [KaryawanController::class, 'storeLaporan'])->name('laporan.store');
    // Route untuk menampilkan formulir edit laporan
    Route::get('/karyawan/laporan/{id}/edit', [KaryawanController::class, 'editLaporan'])->name('laporan.edit');
    // Route untuk memperbarui laporan karyawan
    Route::put('/karyawan/laporan/{id}', [KaryawanController::class, 'updateLaporan'])->name('laporan.update');
    // Route untuk menghapus laporan karyawan
    Route::delete('/karyawan/laporan/{id}', [KaryawanController::class, 'destroyLaporan'])->name('laporan.destroy');
    // Route untuk menampilkan semua laporan karyawan
    Route::get('/laporan', [KaryawanController::class, 'index'])->name('laporan.index');
    // Route untuk menampilkan detail laporan karyawan
    Route::get('/laporan/{id}', [KaryawanController::class, 'showLaporan'])->name('laporan.show');
    // Route untuk menambahkan komentar pada laporan karyawan
    Route::post('/laporan/{id}/komentar', [KomentarController::class, 'store'])->name('laporan.komentar.store');

    // B. Dashboard Admin Sarpras & Manajemen Status
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/laporan/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.laporan.updateStatus');
    Route::get('/admin/laporan/cetak', [AdminController::class, 'cetakLaporan'])->name('admin.laporan.cetak');

    // Manajemen Pengguna oleh Admin
    Route::get('/admin/users', [AdminController::class, 'manajemenUser'])->name('admin.users');
    Route::delete('/admin/users/{id}', [AdminController::class, 'hapusUser'])->name('admin.users.hapus');

    // C. Manajemen Barang / Aset Fasilitas
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // D. Dashboard Pimpinan & Cetak Laporan Pimpinan
    Route::get('/pimpinan/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
    Route::get('/pimpinan/laporan/cetak', [PimpinanController::class, 'cetakLaporan'])->name('pimpinan.laporan.cetak');
    
});