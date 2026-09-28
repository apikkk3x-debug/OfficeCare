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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $laporanMasuk = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        $barangFasilitas = BarangFasilitas::all();

        // Data Statistik Laporan & Prioritas
        $totalLaporan = LaporanKerusakan::count();
        $laporanMenunggu = LaporanKerusakan::where('status_laporan', 'Menunggu')->count();
        $laporanDiproses = LaporanKerusakan::where('status_laporan', 'Diproses')->count();
        $laporanSelesai = LaporanKerusakan::where('status_laporan', 'Selesai')->count();
        
        $prioritasDarurat = LaporanKerusakan::where('prioritas', 'Darurat')->count();
        $prioritasSedang = LaporanKerusakan::where('prioritas', 'Sedang')->count();
        $prioritasRendah = LaporanKerusakan::where('prioritas', 'Rendah')->count();

        // Data Tambahan Aset, Pengadaan, & Pengguna
        $totalAset = BarangFasilitas::count();
        $pengadaanPending = PengadaanBarang::whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu'])->count();
        $totalUsers = User::count();

        // Ambil Pengumuman Terbaru (Aman jika model belum ada)
        $pengumumanTerbaru = null;
        if (class_exists('\App\Models\Pengumuman')) {
            $pengumumanTerbaru = \App\Models\Pengumuman::latest()->first();
        } elseif (class_exists('\App\Models\PengumumanKantor')) {
            $pengumumanTerbaru = \App\Models\PengumumanKantor::latest()->first();
        }

        return view('admin.dashboard', compact(
            'laporanMasuk', 
            'barangFasilitas',
            'totalLaporan',
            'laporanMenunggu',
            'laporanDiproses',
            'laporanSelesai',
            'prioritasDarurat',
            'prioritasSedang',
            'prioritasRendah',
            'totalAset',
            'pengadaanPending',
            'totalUsers',
            'pengumumanTerbaru'
        ));
    }

    /**
     * Menampilkan Halaman Data Laporan Pengaduan
     */
    /**
     * Menampilkan Halaman Data Laporan Pengaduan
     */
    public function laporan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        // Mulai query dengan relasi
        $query = LaporanKerusakan::with(['user', 'barang'])->latest();

        // Jika ada filter status dari URL (contoh: ?status=Menunggu)
        if ($request->has('status') && $request->status != '') {
            $query->where('status_laporan', $request->status);
        }

        // Ambil data dengan paginasi dan pertahankan query string
        $laporan = $query->paginate($perPage)->withQueryString();

        return view('admin.laporan', compact('laporan'));
    }

    /**
     * Memperbarui Status Laporan (Menunggu, Diproses, Selesai, Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);
        
        $statusLama = $laporan->status_laporan;
        $statusBaru = $request->status_laporan;

        $laporan->update([
            'status_laporan' => $statusBaru,
        ]);

        $user = Auth::user();
        $namaAdmin = $user->nama ?? $user->name ?? $user->username ?? 'Admin';

        $keteranganLog = $request->filled('keterangan')
            ? $request->keterangan . " (Oleh Admin: {$namaAdmin})"
            : "Status diperbarui menjadi '{$statusBaru}' oleh Admin ({$namaAdmin})";

        LaporanLog::create([
            'id_laporan'        => $laporan->id_laporan ?? $laporan->id,
            'status_sebelumnya' => $statusLama,
            'status_sekarang'   => $statusBaru,
            'keterangan'        => $keteranganLog,
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
     * Menampilkan Halaman Manajemen Pengguna (Dengan Paginasi)
     */
    public function manajemenUser(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users = User::latest()->paginate($perPage)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Menyimpan Pengguna Baru dari Modal Admin
     */
        public function storeUser(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => 'required|in:admin,pimpinan,karyawan',
            'password' => 'required|string|min:6',
            'divisi'   => 'required|string|max:100',
        ]);

        // 1. Membuat NIK Otomatis Berdasarkan Tahun dan Nomor Urut
        $year = date('Y');
        $latestUser = User::whereYear('created_at', $year)->latest('id_user')->first();
        
        // Ambil 4 digit terakhir dari NIK terakhir tahun ini, lalu tambahkan 1
        $nextNumber = $latestUser ? intval(substr($latestUser->nik, -4)) + 1 : 1;
        $nik = 'GC-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 2. Simpan Data ke Database
        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'nik'      => $nik, // NIK otomatis terisi rapi
            'divisi'   => $request->divisi,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil ditambahkan dengan NIK ' . $nik);
    }

    /**
     * Menghapus Akun Pengguna dari Sistem
     */
    public function hapusUser($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();
        
        if ($user->id_user === Auth::id()) {
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
        $laporan = LaporanKerusakan::with(['user', 'barang', 'komentars.user', 'logs'])->findOrFail($id);

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
        return view('admin.barang.index', compact('barangs'));
    }
    
        public function indexPengadaan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        // Pastikan menggunakan paginate dan withQueryString
        $daftarPengadaan = PengadaanBarang::with('pemohon')
                            ->latest()
                            ->paginate($perPage)
                            ->withQueryString();

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

    public function selesaikanPengadaan(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'lokasi'   => 'required|string|max:255',
            'kondisi'  => 'nullable|string|max:255',
        ]);

        $pengadaan = PengadaanBarang::findOrFail($id);

        $pengadaan->update([
            'status_approval' => 'selesai',
        ]);

        BarangFasilitas::create([
            'kode_barang'     => 'BRG-' . date('Ymd') . '-' . rand(100, 999),
            'nama_barang'     => $pengadaan->nama_barang_baru ?? $pengadaan->nama_barang,
            'kategori_barang' => $request->kategori,
            'lokasi'          => $request->lokasi,
            'kondisi'         => $request->kondisi ?? 'Baik',
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