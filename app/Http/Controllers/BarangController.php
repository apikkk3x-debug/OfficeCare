<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;

class BarangController extends Controller
{
    // Menampilkan daftar seluruh barang fasilitas
    public function index()
    {
        $barangs = BarangFasilitas::latest()->get();
        return view('barang.index', compact('barangs'));
    }

    // Menyimpan data barang baru (Biasanya diakses oleh Admin)
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:100',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
        ]);

        BarangFasilitas::create($request->all());

        return redirect()->back()->with('success', 'Data barang fasilitas berhasil ditambahkan!');
    }

    // Menghapus data barang
    public function destroy($id)
    {
        $barang = BarangFasilitas::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data barang berhasil dihapus!');
    }
}