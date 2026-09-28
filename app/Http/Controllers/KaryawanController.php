<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\LaporanLog;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    // ==========================================
    // MARK NOTIFICATION AS READ (AJAX)
    // ==========================================
    public function markNotificationAsRead($id = null)
    {
        $id = $id ?? request()->route('id') ?? request('id');
        $user = Auth::user();
        $userId = $user->id_user ?? $user->id ?? Auth::id();

        if ($id && $userId) {
            \Illuminate\Support\Facades\DB::table('notification_reads')->updateOrInsert(
                [
                    'id_user'         => $userId,
                    'notification_id' => (string) $id,
                ],
                [
                    'read_at'    => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai telah dibaca.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ID notifikasi tidak valid.'
        ], 400);
    }

    // ==========================================
    // MARK ALL NOTIFICATIONS AS READ (AJAX)
    // ==========================================
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id_user ?? $user->id ?? Auth::id();
        $ids = $request->input('notification_ids', []);

        if ($userId && is_array($ids) && count($ids) > 0) {
            foreach ($ids as $notifId) {
                if (!empty($notifId)) {
                    \Illuminate\Support\Facades\DB::table('notification_reads')->updateOrInsert(
                        [
                            'id_user'         => $userId,
                            'notification_id' => (string) $notifId,
                        ],
                        [
                            'read_at'    => now(),
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil ditandai telah dibaca.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tidak ada notifikasi untuk ditandai.'
        ]);
    }

    // ==========================================
    // DASHBOARD KARYAWAN
    // ==========================================
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Ambil semua laporan milik user yang sedang login
        $laporanku = LaporanKerusakan::with('barang')
                    ->where('id_user', $userId)
                    ->latest()
                    ->get();

        $barangFasilitas = BarangFasilitas::all();

        // Hitung data untuk card ringkasan dashboard
        $totalPengaduan = $laporanku->count();
        
        $perbaikanAktif = LaporanKerusakan::where('id_user', $userId)
                            ->where('status_laporan', '!=', 'Selesai')
                            ->count();

        $pengadaanBarang = \App\Models\PengadaanBarang::where('id_user', $userId)->count();

        $pengadaanDisetujui = \App\Models\PengadaanBarang::where('id_user', $userId)
                                ->where('status_approval', 'selesai') 
                                ->count();

        // 2. Ambil 3-5 log aktivitas terbaru milik user ini untuk dikirim ke view
        $logs = LaporanLog::whereHas('laporan', function($query) use ($userId) {
                        $query->where('id_user', $userId);
                    })
                    ->with(['laporan.barang'])
                    ->latest()
                    ->take(3)
                    ->get();

        // 3. Ambil pengumuman aktif yang ditujukan untuk karyawan atau semua
        $pengumumans = Pengumuman::where('is_active', true)
            ->whereIn('target_role', ['karyawan', 'semua'])
            ->latest()
            ->take(3)
            ->get();

        return view('karyawan.dashboard', compact(
            'laporanku', 
            'barangFasilitas', 
            'logs', 
            'totalPengaduan', 
            'perbaikanAktif', 
            'pengadaanBarang', 
            'pengadaanDisetujui',
            'pengumumans'
        ));
    }
    // ==========================================
    // HALAMAN RIWAYAT LAPORAN (INDEX)
    // ==========================================
        public function index(Request $request)
    {
        $query = LaporanKerusakan::with(['barang'])
                    ->where('id_user', Auth::id());

        // 1. Filter jika diklik card Status Aktif
        if ($request->has('filter') && $request->filter == 'aktif') {
            $query->where('status_laporan', '!=', 'Selesai');
        }

        // 2. Fitur Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deskripsi_kerusakan', 'like', '%' . $search . '%')
                ->orWhereHas('barang', function($barangQuery) use ($search) {
                    $barangQuery->where('nama_barang', 'like', '%' . $search . '%')
                                ->orWhere('lokasi', 'like', '%' . $search . '%');
                });
            });
        }

        // 3. Konfigurasi Paginasi & Batasan Pilihan Per Page
        $perPage = $request->input('per_page', 10);
        $allowedPerPage = [10, 30, 50, 80, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // Menggunakan paginate() menggantikan get()
        $laporanku = $query->latest()->paginate($perPage)->appends($request->all());

        return view('laporan.index', compact('laporanku', 'perPage'));
    }

    public function createLaporan()
    {
        $barangFasilitas = BarangFasilitas::all();
        return view('laporan.create', compact('barangFasilitas'));
    }

    public function storeLaporan(Request $request)
    {
        // 1. Tambahkan validasi prioritas di sini
        $request->validate([
            'id_barang' => 'required',
            'prioritas' => 'required|in:Rendah,Sedang,Darurat', // <-- BARIS BARU
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
        $laporan = LaporanKerusakan::create([
            'id_user' => Auth::id(),
            'id_barang' => $idBarang,
            'prioritas' => $request->prioritas, // <-- BARIS BARU (Simpan ke database)
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kondisi' => $pathFoto,
            'status_laporan' => 'Menunggu',
        ]);

        // Catat log otomatis saat laporan pertama kali dibuat
        $user = Auth::user();
        $namaUser = $user ? $user->name : 'Karyawan';
        
       LaporanLog::create([
    'id_laporan' => $laporan->getKey(), // Mengambil primary key yang valid
    'status_sekarang' => 'Menunggu',
    'keterangan' => 'Laporan pengaduan "' . $request->deskripsi_kerusakan . '" (Prioritas: ' . $request->prioritas . ') berhasil dikirim oleh ' . $namaUser,
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

        // 1. Validasi input form edit disesuaikan dengan data yang dikirim form (id_barang)
        $request->validate([
            'id_barang' => 'required',
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Proses upload foto baru jika ada
        $pathFoto = $laporan->foto_kondisi;
        if ($request->hasFile('foto')) {
            if ($laporan->foto_kondisi && Storage::disk('public')->exists($laporan->foto_kondisi)) {
                Storage::disk('public')->delete($laporan->foto_kondisi);
            }
            $pathFoto = $request->file('foto')->store('laporan_kerusakan', 'public');
        }

        // 3. Update id_barang, deskripsi, dan foto pada laporan kerusakan
        $laporan->update([
            'id_barang' => $request->id_barang,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kondisi' => $pathFoto,
        ]);

        // Catat log update
        $user = Auth::user();
        $namaUser = $user ? ($user->nama ?? $user->name) : 'Karyawan';

        LaporanLog::create([
            'id_laporan'      => $laporan->id_laporan ?? $laporan->id,
            'status_sekarang' => $laporan->status_laporan,
            'keterangan'      => 'Diperbarui oleh ' . $namaUser . ' • "' . $request->deskripsi_kerusakan . '"',
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    } 

    public function destroyLaporan($id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with('error', 'Laporan tidak dapat dibatalkan karena sudah diproses oleh Admin.');
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibatalkan dan sudah dihapus dari sistem.');
    }

    // ==========================================
    // DETAIL LAPORAN (SHOW)
    // ==========================================
    public function showLaporan($id)
    {
        $laporan = LaporanKerusakan::with(['barang', 'logs', 'komentars.user'])->findOrFail($id);

        return view('laporan.show', compact('laporan'));
    }
}