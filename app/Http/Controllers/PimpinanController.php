<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKerusakan;
use App\Models\BarangFasilitas;
use App\Models\PengadaanBarang;
use App\Models\Pengumuman;
use App\Models\Pengingat;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        // Mengambil rekapitulasi data laporan untuk pimpinan
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        
        // Ambil list laporan spesifik untuk modal rincian di dashboard
        $laporanMenungguList = LaporanKerusakan::with(['user', 'barang'])
            ->where('status_laporan', 'Menunggu')
            ->latest()
            ->get();

        $laporanDiprosesList = LaporanKerusakan::with(['user', 'barang'])
            ->where('status_laporan', 'Diproses')
            ->latest()
            ->get();

        // Statistik ringkas untuk pimpinan
        $totalLaporan = LaporanKerusakan::count();
        $laporanMenunggu = $laporanMenungguList->count();
        $laporanDiproses = $laporanDiprosesList->count();
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
            'pengadaanMenunggu',
            'laporanMenungguList',
            'laporanDiprosesList'
        ));
    }

    /**
     * Helper Private Method untuk Menangani Logika Filter Data
     */
    private function getFilteredLaporan(Request $request)
    {
        $query = LaporanKerusakan::with(['user', 'barang']);

        // 1. Filter Tanggal Mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        // 2. Filter Tanggal Selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // 3. Filter Status Laporan (Menangani 'status_laporan' atau 'status')
        $status = $request->input('status_laporan') ?? $request->input('status');
        if ($status && $status !== 'semua') {
            $query->where('status_laporan', $status);
        }

        // 4. Urutkan Terbaru
        $query->latest();

        // 5. Limit Data
        $limit = $request->input('limit', '100');
        if ($limit && $limit !== 'semua') {
            return $query->take((int)$limit)->get();
        }

        return $query->get();
    }

    /**
     * Halaman Web Rekap Laporan Kerusakan (View: pimpinan.cetak)
     */
    public function rekapLaporan(Request $request)
    {
        $laporan = $this->getFilteredLaporan($request);
        return view('pimpinan.cetak', compact('laporan'));
    }

    /**
     * Halaman Cetak Dokumen PDF (View: pimpinan.print)
     */
    public function cetakLaporan(Request $request)
    {
        $laporan = $this->getFilteredLaporan($request);
        return view('pimpinan.print', compact('laporan'));
    }

    public function indexPengadaan(Request $request)
    {
        $query = PengadaanBarang::with('pemohon');

        // 1. Filter Status Approval
        $query->when($request->filled('status'), function ($q) use ($request) {
            $status = strtolower($request->status);
            if (in_array($status, ['pending', 'menunggu'])) {
                $q->whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu']);
            } else {
                $q->where('status_approval', 'like', "%{$request->status}%");
            }
        });

        // 2. Pencarian Kata Kunci (Menggunakan kolom 'nama' pada tabel users)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = trim($request->search);
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_barang_baru', 'like', "%{$search}%")
                    ->orWhere('alasan_pengadaan', 'like', "%{$search}%")
                    ->orWhereHas('pemohon', function ($p) use ($search) {
                        $p->where('nama', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        });

        // 3. Paginasi & Query String
        $perPage = $request->input('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $daftarPengadaan = $query->latest()->paginate($perPage)->withQueryString();

        return view('pimpinan.pengadaan.index', compact('daftarPengadaan', 'perPage'));
    }

    public function setujuiPengadaan(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'catatan_pimpinan' => 'nullable|string|max:1000',
        ]);

        $status = strtolower($pengadaan->status_approval ?? '');

        if ($status !== 'pending' && $status !== 'menunggu') {
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

        if ($status !== 'pending' && $status !== 'menunggu') {
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

    public function kirimPengingat(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan_kerusakan,id_laporan',
            'pesan'      => 'required|string',
        ]);

        Pengingat::updateOrCreate(
            [
                'laporan_id' => $request->laporan_id,
                'is_read'    => false,
            ],
            [
                'pimpinan_id' => Auth::id(),
                'pesan'       => $request->pesan,
                'updated_at'  => now(),
            ]
        );

        return redirect()->back()->with('success', 'Pengingat berhasil dikirim/diperbarui ke Admin!');
    }
}