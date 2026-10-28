<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\LaporanKomentar;
use App\Models\LaporanLog;
use App\Models\User;
use App\Models\PengadaanBarang;
use App\Models\Pengingat;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $laporanMasuk = LaporanKerusakan::with(['user', 'barang'])->latest()->get();
        $barangFasilitas = BarangFasilitas::where('kondisi', '!=', 'Diapkir')->get();

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

        $pengingatMasuk = Pengingat::with(['laporan.user', 'laporan.barang', 'pimpinan'])
            ->where('is_read', false)
            ->latest()
            ->get();

        // Ambil Pengumuman Terbaru
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
            'pengumumanTerbaru',
            'pengingatMasuk'
        ));
    }

    /**
     * Private Helper: Konsistensi Query Filtering Laporan Kerusakan (Web & PDF)
     */
    private function getFilteredLaporan(Request $request)
    {
        $query = LaporanKerusakan::with(['user', 'barang']);

        // 1. Pencarian Kata Kunci Teks (Search: Nama Barang, Pelapor, Email, Deskripsi, Lokasi)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($sub) use ($search) {
                $sub->where('deskripsi_kerusakan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('nama', 'like', "%{$search}%")
                          ->orWhere('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('barang', function ($b) use ($search) {
                        $b->where('nama_barang', 'like', "%{$search}%")
                          ->orWhere('lokasi', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Filter Status Laporan
        $status = $request->input('status') ?? $request->input('status_laporan');
        if ($status && $status !== 'semua') {
            $query->where('status_laporan', $status);
        }

        // 3. Filter Prioritas
        if ($request->filled('prioritas') && $request->prioritas !== 'semua') {
            $query->where('prioritas', $request->prioritas);
        }

        // 4. Filter Tanggal Mulai & Tanggal Selesai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        return $query->latest();
    }

    /**
     * Menampilkan Halaman Data Laporan Pengaduan (Lengkap dengan Filter & Search)
     */
    public function laporan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $laporan = $this->getFilteredLaporan($request)->paginate($perPage)->withQueryString();

        return view('admin.laporan', compact('laporan'));
    }

    /**
     * Memperbarui Status Laporan
     */
   public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status_laporan'   => 'required|in:Menunggu,Diproses,Dialihkan ke Pengadaan,Selesai,Ditolak',
        'keterangan'       => 'nullable|string|max:500',
        'nama_barang_baru' => 'nullable|string|max:255',
        'jumlah'           => 'nullable|integer|min:1',
        'harga_satuan'     => 'nullable|numeric|min:0',
        'link_referensi'   => 'nullable|url|max:255',
    ]);

    $laporan = LaporanKerusakan::with('barang')->findOrFail($id);

    $statusLama = $laporan->status_laporan;
    $statusBaru = $request->status_laporan;

    $transisiDiizinkan = [
        'Menunggu'               => ['Diproses', 'Ditolak', 'Dialihkan ke Pengadaan'],
        'Diproses'               => ['Selesai', 'Dialihkan ke Pengadaan'],
        'Dialihkan ke Pengadaan' => ['Selesai', 'Ditolak'],
        'Selesai'                => [],
        'Ditolak'                => [],
    ];

    if (!in_array($statusBaru, $transisiDiizinkan[$statusLama] ?? [], true)) {
        return redirect()->back()->with(
            'error',
            "Status tidak dapat diubah dari '{$statusLama}' menjadi '{$statusBaru}'."
        );
    }

    $user = Auth::user();
    $namaAdmin = $user->nama ?? $user->name ?? $user->username ?? 'Admin';

    $keteranganLog = $request->filled('keterangan')
        ? $request->keterangan . " (Oleh Admin: {$namaAdmin})"
        : "Status diperbarui menjadi '{$statusBaru}' oleh Admin ({$namaAdmin})";

    DB::transaction(function () use (
        $laporan,
        $statusLama,
        $statusBaru,
        $keteranganLog,
        $request
    ) {
        $idLaporan = $laporan->id_laporan ?? $laporan->id;

        $laporan->update([
            'status_laporan' => $statusBaru,
        ]);

        LaporanLog::create([
            'id_laporan'        => $idLaporan,
            'status_sebelumnya' => $statusLama,
            'status_sekarang'   => $statusBaru,
            'keterangan'        => $keteranganLog,
        ]);

        // Tandai pengingat dibaca
        Pengingat::where('laporan_id', $idLaporan)->update([
            'is_read' => true,
        ]);

        // =========================================================================
        // 1. BUAT RECORD PERBAIKAN OTOMATIS JIKA STATUS DIUBAH KE "DIPROSES"
        // =========================================================================
        if ($statusBaru === 'Diproses') {
            $perbaikanEksis = Perbaikan::where('id_laporan', $idLaporan)->exists();

            if (!$perbaikanEksis) {
                Perbaikan::create([
                    'id_laporan'      => $idLaporan,
                    'id_admin'        => Auth::id(),
                    'tanggal_mulai'   => now(),
                    'tanggal_selesai' => null,
                    'catatan_teknisi' => $request->input('keterangan') ?: 'Laporan dalam penanganan teknisi/servis.',
                    'biaya_perbaikan' => 0,
                ]);
            }
        }

        // =========================================================================
        // 2. BUAT DRAF PENGADAAN JIKA DIALIHKAN KE PENGADAAN
        // =========================================================================
        if ($statusBaru === 'Dialihkan ke Pengadaan') {
            $pengadaanEksis = PengadaanBarang::where('id_laporan', $idLaporan)->exists();

            if (!$pengadaanEksis) {
                $jumlah = (int) $request->input('jumlah', 1);
                $hargaSatuan = (float) $request->input('harga_satuan', 0);
                $totalEstimasi = $jumlah * $hargaSatuan;

                $namaBarang = $request->input('nama_barang_baru')
                    ?: ($laporan->barang->nama_barang ?? 'Barang Pengganti');

                PengadaanBarang::create([
                    'id_laporan'       => $idLaporan,
                    'id_user'          => Auth::id(),
                    'nama_barang_baru' => $namaBarang,
                    'jumlah'           => $jumlah,
                    'alasan_pengadaan' => "Pengalihan dari Laporan Kerusakan #" . $idLaporan . ": " . $laporan->deskripsi_kerusakan,
                    'estimasi_harga'   => $totalEstimasi,
                    'link_referensi'   => $request->input('link_referensi'),
                    'status_approval'  => 'Pending',
                ]);
            }
        }
    });

    return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
}

    /**
     * Menampilkan Halaman Cetak Laporan PDF / Print
     */
    public function cetakLaporan(Request $request)
    {
        $query = $this->getFilteredLaporan($request);

        $limit = $request->input('per_page') ?? $request->input('limit');
        if ($limit && $limit !== 'semua') {
            $laporan = $query->take((int)$limit)->get();
        } else {
            $laporan = $query->get();
        }

        return view('admin.cetak', compact('laporan'));
    }

    /**
     * Menampilkan Halaman Manajemen Pengguna (Dilengkapi Filter & Search)
     */
    public function manajemenUser(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = User::query();

        // Filter Pencarian Teks User
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('divisi', 'like', "%{$search}%");
            });
        });

        // Filter Role User
        $query->when($request->filled('role'), function ($q) use ($request) {
            $q->where('role', $request->role);
        });

        $users = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Menyimpan Pengguna Baru
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

        $year = date('Y');
        $latestUser = User::whereYear('created_at', $year)->latest('id_user')->first();
        
        $nextNumber = $latestUser ? intval(substr($latestUser->nik, -4)) + 1 : 1;
        $nik = 'GC-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'nik'      => $nik,
            'divisi'   => $request->divisi,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil ditambahkan dengan NIK ' . $nik);
    }

    /**
     * Menghapus Akun Pengguna
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
     * Menampilkan Detail Laporan Kerusakan
     */
    public function showLaporan($id)
    {
        $laporan = LaporanKerusakan::with(['user', 'barang', 'komentars.user', 'logs'])->findOrFail($id);

        return view('admin.laporan.show', compact('laporan'));
    }

    /**
     * Mengirim Tanggapan / Chat
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
     * Menampilkan Data Manajemen Aset
     */
    public function barangIndex()
    {
        $barangs = BarangFasilitas::latest()->get();
        return view('admin.barang.index', compact('barangs'));
    }
    
    /**
     * Menampilkan Daftar Pengadaan Barang (Dilengkapi Filter & Search)
     */
    public function indexPengadaan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = PengadaanBarang::with(['pemohon', 'laporan.barang']);

        // Filter Pencarian Teks Pengadaan
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_barang_baru', 'like', "%{$search}%")
                    ->orWhere('alasan_pengadaan', 'like', "%{$search}%")
                    ->orWhereHas('pemohon', fn($p) => $p->where('nama', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"));
            });
        });

        // Filter Status Approval Pengadaan
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status_approval', $request->status);
        });

        $daftarPengadaan = $query->latest()->paginate($perPage)->withQueryString();

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
     * Menghapus Data Barang
     */
    public function hapusBarang($id)
    {
        $barang = BarangFasilitas::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data barang berhasil dihapus dari inventaris!');
    }

    /**
     * Finalisasi Pengadaan (Terbitkan Aset & Apkir Barang Lama)
     */
    public function selesaikanPengadaan(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'lokasi'   => 'required|string|max:255',
            'kondisi'  => 'required|in:Baik,Perbaikan Ringan,Rusak,Diapkir',
        ]);

        $pengadaan = PengadaanBarang::findOrFail($id);
        $status = strtolower($pengadaan->status_approval ?? '');

        if ($status !== 'disetujui') {
            return redirect()->back()->with(
                'error',
                'Pengadaan hanya dapat difinalisasi setelah disetujui oleh Pimpinan.'
            );
        }

        DB::transaction(function () use ($pengadaan, $request) {
            $pengadaan->update([
                'status_approval' => 'selesai',
            ]);

            $jumlahUnit = max(1, (int) ($pengadaan->jumlah ?? 1));

            for ($i = 0; $i < $jumlahUnit; $i++) {
                do {
                    $kodeBarang = 'BRG-' . date('Ymd') . '-' . rand(1000, 9999);
                } while (BarangFasilitas::where('kode_barang', $kodeBarang)->exists());

                BarangFasilitas::create([
                    'kode_barang'     => $kodeBarang,
                    'nama_barang'     => $pengadaan->nama_barang_baru ?? $pengadaan->nama_barang,
                    'kategori_barang' => $request->kategori,
                    'lokasi'          => $request->lokasi,
                    'kondisi'         => $request->kondisi,
                ]);
            }

            if (!empty($pengadaan->id_laporan)) {
                $laporan = LaporanKerusakan::with('barang')->find($pengadaan->id_laporan);

                if ($laporan) {
                    if ($laporan->barang) {
                        $laporan->barang->update([
                            'kondisi' => 'Diapkir',
                        ]);
                    }

                    if ($laporan->status_laporan !== 'Selesai') {
                        $statusAwal = $laporan->status_laporan;

                        $laporan->update([
                            'status_laporan' => 'Selesai',
                        ]);

                        LaporanLog::create([
                            'id_laporan'        => $laporan->id_laporan ?? $laporan->id,
                            'status_sebelumnya' => $statusAwal,
                            'status_sekarang'   => 'Selesai',
                            'keterangan'        => "Laporan diselesaikan secara otomatis. Barang lama telah diapkir dan {$jumlahUnit} unit barang baru pengganti telah didaftarkan ke Master Aset.",
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with(
            'success',
            'Barang pengadaan telah dibeli dan resmi terdaftar di Master Aset kantor! Barang lama berhasil diapkir.'
        );
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

    public function markPengingatRead($id)
    {
        $pengingat = Pengingat::findOrFail($id);
        $pengingat->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pengingat berhasil ditandai selesai.');
    }
}