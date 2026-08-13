<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;

class BarangController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input dengan pesan error kustom (Poin 1 & 2)
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string',
            'lokasi'      => 'required|string|max:255',
            'kondisi'     => 'required|in:Baik,Perbaikan Ringan,Rusak',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required'    => 'Pilih kategori barang terlebih dahulu.',
            'lokasi.required'      => 'Lokasi ruangan wajib diisi.',
        ]);

        // Generate Kode Barang Otomatis (Poin 3)
        // Contoh hasil: BRG-20260813-001
        $tanggalHariIni = date('Ymd');
        $jumlahBarangHariIni = BarangFasilitas::whereDate('created_at', today())->count() + 1;
        $kodeUnik = 'BRG-' . $tanggalHariIni . '-' . str_pad($jumlahBarangHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan ke database
        BarangFasilitas::create([
            'kode_barang' => $kodeUnik,
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori,
            'lokasi'      => $request->lokasi,
            'kondisi'     => $request->kondisi,
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