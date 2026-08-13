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
}