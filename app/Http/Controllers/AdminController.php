<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\LaporanLog; // 1. Tambahkan import model LaporanLog ini
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Pastikan Auth juga ter-import

class AdminController extends Controller
{
    public function dashboard()
    {
        $laporanMasuk = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        $barangFasilitas = BarangFasilitas::all();

        return view('admin.dashboard', compact('laporanMasuk', 'barangFasilitas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);
        
        // Simpan status lama sebelum diubah
        $statusLama = $laporan->status_laporan;
        $statusBaru = $request->status_laporan;

        // Update status laporan
        $laporan->update([
            'status_laporan' => $statusBaru,
        ]);

        // 2. OTOMATIS CATAT KE TABEL LOG/TIMELINE
        LaporanLog::create([
            'id_laporan' => $laporan->id_laporan, // Sesuaikan jika primary key di tabel kamu bernama 'id_laporan'
            'status_sebelumnya' => $statusLama,
            'status_sekarang' => $statusBaru,
            'keterangan' => 'Status diperbarui oleh Admin (' . Auth::user()->name . ')'
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function cetakLaporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('admin.cetak', compact('laporan'));
    }

    public function manajemenUser()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function hapusUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus dari sistem.');
    }
}   