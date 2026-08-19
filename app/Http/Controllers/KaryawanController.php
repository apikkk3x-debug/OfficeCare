<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $laporanku = LaporanKerusakan::with('barang')
                    ->where('id_user', $userId)
                    ->latest()
                    ->get();

        $barangFasilitas = BarangFasilitas::all();

        return view('karyawan.dashboard', compact('laporanku', 'barangFasilitas'));
    }

    // ==========================================
    // FIZUR BARU: HALAMAN RIWAYAT LAPORAN (INDEX)
    // ==========================================
    public function index()
    {
        $userId = Auth::id();
        $laporanku = LaporanKerusakan::with('barang')
                    ->where('id_user', $userId)
                    ->latest()
                    ->get();

        return view('laporan.index', compact('laporanku'));
    }

    public function createLaporan()
    {
        $barangFasilitas = BarangFasilitas::all();
        return view('laporan.create', compact('barangFasilitas'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $idBarang = $request->id_barang;

        // Jika karyawan memilih menambah barang baru
        if ($idBarang === 'tambah_baru') {
            $request->validate([
                'nama_barang_baru' => 'required|string|max:255',
                'lokasi_baru' => 'required|string|max:255',
            ]);

            // Simpan data barang baru ke tabel barang_fasilitas
            $barangBaru = BarangFasilitas::create([
                'kode_barang' => 'BRG-' . rand(1000, 9999),
                'nama_barang' => $request->nama_barang_baru,
                'kategori_barang' => 'Umum',
                'lokasi' => $request->lokasi_baru,
                'kondisi' => 'Rusak',
            ]);

            $idBarang = $barangBaru->id_barang ?? $barangBaru->id;
        }

        // Proses upload foto
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('laporan_kerusakan', 'public');
        }

        // Simpan laporan kerusakan
        LaporanKerusakan::create([
            'id_user' => Auth::id(),
            'id_barang' => $idBarang,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto' => $pathFoto,
            'status_laporan' => 'Menunggu',
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan dan data barang baru berhasil dikirim!');
    }

    // ==========================================
    // FITUR: EDIT, UPDATE, & BATALKAN LAPORAN
    // ==========================================

    public function editLaporan($id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        // Keamanan: Hanya boleh diedit jika status masih 'Menunggu'
        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with('error', 'Laporan yang sudah diproses tidak dapat diubah.');
        }

        $barangFasilitas = BarangFasilitas::all();
        return view('laporan.edit', compact('laporan', 'barangFasilitas'));
    }

    public function updateLaporan(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with('error', 'Laporan yang sudah diproses tidak dapat diubah.');
        }

        // 1. Validasi sesuai dengan input form edit yang baru
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Update nama barang dan lokasi pada tabel barang terkait
        if ($laporan->barang) {
            $laporan->barang->update([
                'nama_barang' => $request->nama_barang,
                'lokasi' => $request->lokasi ?? $laporan->barang->lokasi,
            ]);
        }

        // 3. Proses upload foto baru jika ada
        $pathFoto = $laporan->foto;
        if ($request->hasFile('foto')) {
            if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $pathFoto = $request->file('foto')->store('laporan_kerusakan', 'public');
        }

        // 4. Update deskripsi dan foto pada laporan kerusakan
        $laporan->update([
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto' => $pathFoto,
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroyLaporan($id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with('error', 'Laporan yang sudah diproses tidak dapat dibatalkan.');
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibatalkan.');
    }
}