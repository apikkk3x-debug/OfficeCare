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
        $pengadaanMenunggu = PengadaanBarang::whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu'])->count();

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

    public function setujuiPengadaan(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'catatan_pimpinan' => 'nullable|string|max:1000',
        ]);

        $status = strtolower($pengadaan->status_approval ?? '');

        if ($status !== 'pending') {
            return redirect()->back()->with(
                'error',
                'Pengajuan ini sudah diproses dan tidak dapat disetujui kembali.'
            );
        }

        $pengadaan->update([
            'status_approval'  => 'Disetujui',
            'id_pimpinan'      => Auth::id(),
            'tanggal_approval' => now(),
            'catatan_pimpinan' => $request->catatan_pimpinan,
        ]);

        return redirect()->back()->with(
            'success',
            'Pengajuan barang telah disetujui!'
        );
    }
        public function tolakPengadaan(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'catatan_pimpinan' => 'nullable|string|max:1000',
        ]);

        $status = strtolower($pengadaan->status_approval ?? '');

        if ($status !== 'pending') {
            return redirect()->back()->with(
                'error',
                'Pengajuan ini sudah diproses dan tidak dapat ditolak kembali.'
            );
        }

        $pengadaan->update([
            'status_approval'  => 'Ditolak',
            'id_pimpinan'      => Auth::id(),
            'tanggal_approval' => now(),
            'catatan_pimpinan' => $request->catatan_pimpinan,
        ]);

        return redirect()->back()->with(
            'success',
            'Pengajuan barang telah ditolak!'
        );
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