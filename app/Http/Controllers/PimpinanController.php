<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\BarangFasilitas;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        // Mengambil rekapitulasi data laporan untuk pimpinan
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        
        // Statistik ringkas untuk pimpinan
        $totalLaporan = LaporanKerusakan::count();
        $laporanMenunggu = LaporanKerusakan::where('status_laporan', 'Menunggu')->count();
        $laporanDiproses = LaporanKerusakan::where('status_laporan', 'Diproses')->count();
        $laporanSelesai = LaporanKerusakan::where('status_laporan', 'Selesai')->count();
        $totalBarang = BarangFasilitas::count();

        return view('pimpinan.dashboard', compact(
            'laporan', 
            'totalLaporan', 
            'laporanMenunggu', 
            'laporanDiproses', 
            'laporanSelesai', 
            'totalBarang'
        ));
    }

    public function cetakLaporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('pimpinan.cetak', compact('laporan'));
    }
}