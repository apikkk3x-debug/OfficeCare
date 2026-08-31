<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\BarangFasilitas;
use App\Models\PengadaanBarang;

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

    public function indexPengadaan()
    {
        // Mengambil seluruh data pengajuan barang baru
        $daftarPengadaan = PengadaanBarang::with('pemohon')->latest()->get();
        return view('pimpinan.pengadaan.index', compact('daftarPengadaan'));
    }

    public function setujuiPengadaan($id)
    {
        $pengadaan = PengadaanBarang::findOrFail($id);
        $pengadaan->update([
            'status_approval'  => 'disetujui',
            'id_pimpinan'      => auth()->id(),
            'tanggal_approval' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan barang telah disetujui!');
    }

    public function tolakPengadaan($id)
    {
        $pengadaan = PengadaanBarang::findOrFail($id);
        $pengadaan->update([
            'status_approval'  => 'ditolak',
            'id_pimpinan'      => auth()->id(),
            'tanggal_approval' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan barang telah ditolak!');
    }

    public function rekapLaporan()
    {
        // Mengambil data laporan kerusakan beserta relasi pelapor dan barangnya
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();

        // Mengarahkan ke file resources/views/pimpinan/cetak.blade.php
        return view('pimpinan.cetak', compact('laporan'));
    }
}