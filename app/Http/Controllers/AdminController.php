<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\LaporanKomentar;
use App\Models\LaporanLog;
use App\Models\User;
use App\Models\PengadaanBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Admin
     */
    public function dashboard()
    {
        $laporanMasuk = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        $barangFasilitas = BarangFasilitas::all();

        return view('admin.dashboard', compact('laporanMasuk', 'barangFasilitas'));
    }

    /**
     * Menampilkan Halaman Data Laporan Pengaduan
     */
    public function laporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('admin.laporan', compact('laporan'));
    }

    /**
     * Memperbarui Status Laporan (Menunggu, Diproses, Selesai, Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);
        
        $statusLama = $laporan->status_laporan;
        $statusBaru = $request->status_laporan;

        $laporan->update([
            'status_laporan' => $statusBaru,
        ]);

        $user = Auth::user();
        $namaAdmin = $user->name ?? $user->nama ?? $user->username ?? 'Admin';

        LaporanLog::create([
            'id_laporan'         => $laporan->id_laporan ?? $laporan->id,
            'status_sebelumnya' => $statusLama,
            'status_sekarang'   => $statusBaru,
            'keterangan'        => "Status diperbarui menjadi '{$statusBaru}' oleh Admin ({$namaAdmin})"
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Menampilkan Halaman Cetak Laporan PDF / Print
     */
    public function cetakLaporan()
    {
        $laporan = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        return view('admin.cetak', compact('laporan'));
    }

    /**
     * Menampilkan Halaman Manajemen Pengguna
     */
    public function manajemenUser()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Menghapus Akun Pengguna dari Sistem
     */
    public function hapusUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus dari sistem.');
    }

    /**
     * Menampilkan Detail Laporan Kerusakan & Riwayat Obrolan / Chat
     */
    public function showLaporan($id)
    {
        $laporan = LaporanKerusakan::with(['user', 'barang', 'komentars.user'])->findOrFail($id);

        return view('admin.laporan.show', compact('laporan'));
    }

    /**
     * Mengirimkan Tanggapan / Chat pada Laporan Kerusakan
     */
    public function tanggapiLaporan(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        LaporanKomentar::create([
            'id_laporan' => $id,
            'id_user'    => Auth::id(),
            'pesan'      => $request->pesan,
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    /**
     * Menampilkan Data Manajemen Aset / Barang
     */
    public function barangIndex()
    {
        $barangs = BarangFasilitas::latest()->get();
        dd($barangs);
        return view('admin.barang.index', compact('barangs'));
    }
    
    public function indexPengadaan()
    {
        $daftarPengadaan = PengadaanBarang::with('pemohon')->latest()->get();

        return view('admin.pengadaan.index', compact('daftarPengadaan'));
    }

    /**
     * Menyimpan Barang Baru
     */
    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barang_fasilitas', 'nama_barang'),
            ],
            'kategori'  => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'kondisi'   => 'nullable|string|max:255',
        ], [
            'nama_barang.unique' => 'Barang / Fasilitas dengan nama tersebut sudah ada di sistem!',
        ]);

        BarangFasilitas::create([
            'kode_barang'     => 'BRG-' . date('Ymd') . '-' . rand(100, 999),
            'nama_barang'     => $request->nama_barang,
            'kategori_barang' => $request->kategori,
            'lokasi'          => $request->lokasi,
            'kondisi'         => $request->kondisi ?? 'Baik',
        ]);

        return redirect()->back()->with('success', 'Barang fasilitas baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui Data Barang
     */
    public function updateBarang(Request $request, $id)
    {
        $barang = BarangFasilitas::findOrFail($id);

        $request->validate([
            'nama_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barang_fasilitas', 'nama_barang')->ignore($id, 'id_barang'),
            ],
            'kategori'  => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'kondisi'   => 'nullable|string|max:255',
        ], [
            'nama_barang.unique' => 'Nama barang tersebut sudah digunakan oleh aset lain!',
        ]);

        $barang->update([
            'nama_barang'     => $request->nama_barang,
            'kategori_barang' => $request->kategori,
            'lokasi'          => $request->lokasi,
            'kondisi'         => $request->kondisi ?? $barang->kondisi,
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus Data Barang dari Inventaris
     */
    public function hapusBarang($id)
    {
        $barang = BarangFasilitas::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data barang berhasil dihapus dari inventaris!');
    }

    public function selesaikanPengadaan($id)
    {
        $pengadaan = PengadaanBarang::findOrFail($id);

        $pengadaan->update([
            'status_approval' => 'selesai',
        ]);

        BarangFasilitas::create([
            'kode_barang'     => 'BRG-' . date('Ymd') . '-' . rand(100, 999),
            'nama_barang'     => $pengadaan->nama_barang_baru ?? $pengadaan->nama_barang,
            'kategori_barang' => 'Peralatan Kantor',
            'lokasi'          => 'Gudang Sarpras / Siap Pakai',
            'kondisi'         => 'Baik',
        ]);

        return redirect()->back()->with('success', 'Barang pengadaan telah dibeli dan resmi terdaftar di Master Aset kantor!');
    }

    public function editUser($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id_user',
            'role'  => 'required|in:admin,pimpinan,karyawan',
        ]);

        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function updateUserStatus(Request $request, $id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $user->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status akun berhasil diperbarui!');
    }
}