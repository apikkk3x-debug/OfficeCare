<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    // Halaman form pengajuan untuk Karyawan
    public function create()
    {
        return view('karyawan.pengadaan.create');
    }

    // Proses menyimpan pengajuan dari Karyawan
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang_baru' => 'required|string|max:255',
            'jumlah'           => 'required|numeric|min:1',
            'estimasi_harga'   => 'nullable|numeric|min:0',
            'alasan_pengadaan' => 'required|string',
        ]);

        PengadaanBarang::create([
            'id_user'          => auth()->id(), // Mengambil ID karyawan yang sedang login
            'nama_barang_baru' => $request->nama_barang_baru,
            'jumlah'           => $request->jumlah,
            'estimasi_harga'   => $request->estimasi_harga,
            'alasan_pengadaan' => $request->alasan_pengadaan,
            'status_approval'  => 'pending',
        ]);

        return redirect()->route('karyawan.dashboard')->with('success', 'Pengajuan barang baru berhasil dikirim ke Pimpinan!');
    }
    public function index()
    {
        $pengadaanku = \App\Models\PengadaanBarang::where('id_user', auth()->id())->latest()->get();
        return view('karyawan.pengadaan.index', compact('pengadaanku'));
    }
}