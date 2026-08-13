<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\Perbaikan;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik ringkas untuk Admin Sarpras
        $totalUser = User::count();
        $totalBarang = BarangFasilitas::count();
        $laporanMasuk = LaporanKerusakan::where('status_laporan', 'Menunggu')->count();
        
        return view('admin.dashboard', compact('totalUser', 'totalBarang', 'laporanMasuk'));
    }
}