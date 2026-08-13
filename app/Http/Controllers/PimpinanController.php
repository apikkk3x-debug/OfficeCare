<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengadaanBarang;
use App\Models\LaporanKerusakan;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        // Pimpinan memantau pengajuan pengadaan barang dan rekap laporan
        $pengadaanPending = PengadaanBarang::with('pemohon')
                            ->where('status_approval', 'Pending')
                            ->get();

        return view('pimpinan.dashboard', compact('pengadaanPending'));
    }
}