<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        // Mengambil riwayat laporan yang dibuat oleh karyawan yang sedang login
        $laporanku = LaporanKerusakan::with('barang')
                        ->where('id_user', $userId)
                        ->latest()
                        ->get();

        $barangFasilitas = BarangFasilitas::all();

        return view('karyawan.dashboard', compact('laporanku', 'barangFasilitas'));
    }

    // Tambahkan fungsi ini tepat di bawah fungsi dashboard
    public function storeLaporan(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang_fasilitas,id_barang',
            'deskripsi_kerusakan' => 'required|string',
        ]);

        LaporanKerusakan::create([
            'id_user' => Auth::id(),
            'id_barang' => $request->id_barang,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'status_laporan' => 'Menunggu',
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan berhasil dikirim ke Admin Sarpras!');
    }
}