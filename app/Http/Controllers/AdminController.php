<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;

class AdminController extends Controller
{
    public function dashboard()
    {
        $laporanMasuk = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        $barangFasilitas = BarangFasilitas::all();

        return view('admin.dashboard', compact('laporanMasuk', 'barangFasilitas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);
        $laporan->update([
            'status_laporan' => $request->status_laporan,
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function cetakLaporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('admin.cetak', compact('laporan'));
    }
}