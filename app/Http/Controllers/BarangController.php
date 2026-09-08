<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;

class BarangController extends Controller
{
    public function index()
    {
        // Ubah variabel menjadi $barangs agar sinkron dengan index.blade.php
        $barangs = BarangFasilitas::latest()->get();
        return view('admin.barang.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        // Validasi input dengan pesan error kustom & aturan unique (Poin 1 & 2)
        $request->validate([
            // Aturan unique ditambahkan agar nama barang tidak bisa diinput ganda
            'nama_barang' => 'required|string|max:255|unique:barang_fasilitas,nama_barang',
            'kategori'    => 'required|string',
            'lokasi'      => 'required|string|max:255',
            'kondisi'     => 'required|in:Baik,Perbaikan Ringan,Rusak',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.unique'   => 'Nama fasilitas/barang ini sudah terdaftar di sistem!',
            'kategori.required'    => 'Pilih kategori barang terlebih dahulu.',
            'lokasi.required'      => 'Lokasi ruangan wajib diisi.',
            'kondisi.required'     => 'Kondisi barang wajib dipilih.',
        ]);

        // Generate Kode Barang Otomatis (Poin 3 - Tetap Dipertahankan)
        // Contoh hasil: BRG-20260902-001
        $tanggalHariIni = date('Ymd');
        $jumlahBarangHariIni = BarangFasilitas::whereDate('created_at', today())->count() + 1;
        $kodeUnik = 'BRG-' . $tanggalHariIni . '-' . str_pad($jumlahBarangHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan ke database
        BarangFasilitas::create([
            'kode_barang'     => $kodeUnik,
            'nama_barang'     => $request->nama_barang,
            'kategori_barang' => $request->kategori, // Diubah menjadi kategori_barang sesuai phpMyAdmin
            'lokasi'          => $request->lokasi,
            'kondisi'         => $request->kondisi,
        ]);

        return redirect()->back()->with('success', 'Barang fasilitas baru berhasil ditambahkan dengan kode: ' . $kodeUnik);
    }

    public function destroy($id)
    {
        $barang = BarangFasilitas::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data barang berhasil dihapus.');
    }
}