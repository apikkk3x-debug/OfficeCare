<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKerusakan;
use App\Models\BarangFasilitas;
use App\Models\PengadaanBarang;
use App\Models\Pengumuman;

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
        $daftarBarang = BarangFasilitas::latest()->get(); // Ambil data unit untuk modal aset

        // Hitung pengadaan barang yang masih menunggu persetujuan
        $pengadaanMenunggu = PengadaanBarang::where('status_approval', 'menunggu')->count();

        // Ambil pengumuman aktif yang ditujukan untuk pimpinan atau semua role
        $pengumumans = Pengumuman::where('is_active', true)
            ->whereIn('target_role', ['pimpinan', 'semua'])
            ->latest()
            ->get();

        return view('pimpinan.dashboard', compact(
            'laporan', 
            'totalLaporan', 
            'laporanMenunggu', 
            'laporanDiproses', 
            'laporanSelesai', 
            'totalBarang',
            'daftarBarang',
            'pengumumans',
            'pengadaanMenunggu'
        ));
    }

    public function cetakLaporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('pimpinan.print', compact('laporan'));
    }

    public function indexPengadaan(Request $request)
    {
        // Mengambil jumlah baris per halaman dari request (default 10)
        $perPage = $request->input('per_page', 10);
        
        // Mengambil data pengajuan barang baru dengan paginasi dan tetap menggunakan $daftarPengadaan
        $daftarPengadaan = PengadaanBarang::with('pemohon')
                            ->latest()
                            ->paginate($perPage)
                            ->withQueryString();

        return view('pimpinan.pengadaan.index', compact('daftarPengadaan'));
    }

    public function setujuiPengadaan(PengadaanBarang $pengadaan)
    {
        $pengadaan->update([
            'status_approval'  => 'disetujui',
            'id_pimpinan' => Auth::user()->id_user,
            'tanggal_approval' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan barang telah disetujui!');
    }

    public function tolakPengadaan(PengadaanBarang $pengadaan)
    {
        $pengadaan->update([
            'status_approval'  => 'ditolak',
            'id_pimpinan' => Auth::user()->id_user,
            'tanggal_approval' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan barang telah ditolak!');
    }

    public function rekapLaporan(Request $request)
    {
        // Ambil status dari query parameter (jika ada)
        $statusFilter = $request->input('status');

        $query = LaporanKerusakan::with(['user', 'barang']);

        if ($statusFilter) {
            $query->where('status_laporan', $statusFilter);
        }

        $laporan = $query->latest()->get();

        return view('pimpinan.cetak', compact('laporan', 'statusFilter'));
    }
}