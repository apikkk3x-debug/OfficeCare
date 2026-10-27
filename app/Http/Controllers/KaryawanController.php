<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangFasilitas;
use App\Models\LaporanKerusakan;
use App\Models\LaporanLog;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $notificationId = (string) $id;
            $now = now();

            $exists = \Illuminate\Support\Facades\DB::table('notification_reads')
                ->where('id_user', $userId)
                ->where('notification_id', $notificationId)
                ->exists();

            if ($exists) {
                \Illuminate\Support\Facades\DB::table('notification_reads')
                    ->where('id_user', $userId)
                    ->where('notification_id', $notificationId)
                    ->update([
                        'read_at' => $now,
                        'updated_at' => $now,
                    ]);
            } else {
                \Illuminate\Support\Facades\DB::table('notification_reads')
                    ->insert([
                        'id_user' => $userId,
                        'notification_id' => $notificationId,
                        'read_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
            }

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
                if (empty($notifId)) {
                    continue;
                }

                $notificationId = (string) $notifId;
                $now = now();

                $exists = \Illuminate\Support\Facades\DB::table('notification_reads')
                    ->where('id_user', $userId)
                    ->where('notification_id', $notificationId)
                    ->exists();

                if ($exists) {
                    \Illuminate\Support\Facades\DB::table('notification_reads')
                        ->where('id_user', $userId)
                        ->where('notification_id', $notificationId)
                        ->update([
                            'read_at' => $now,
                            'updated_at' => $now,
                        ]);
                } else {
                    \Illuminate\Support\Facades\DB::table('notification_reads')
                        ->insert([
                            'id_user' => $userId,
                            'notification_id' => $notificationId,
                            'read_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
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

        // Hanya ambil barang fasilitas yang tidak diapkir
        $barangFasilitas = BarangFasilitas::where('kondisi', '!=', 'Diapkir')->get();

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

        // 1. Filter Quick Card (Status Aktif)
        $query->when($request->filled('filter') && $request->filter == 'aktif', function ($q) {
            $q->where('status_laporan', '!=', 'Selesai');
        });

        // 2. Filter Status Laporan (Dropdown Status)
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status_laporan', $request->status);
        });

        // 3. Filter Prioritas Laporan (Dropdown Prioritas)
        $query->when($request->filled('prioritas'), function ($q) use ($request) {
            $q->where('prioritas', $request->prioritas);
        });

        // 4. Fitur Pencarian Kata Kunci (Deskripsi, Nama Barang, Lokasi, Kode)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = trim($request->search);
            $q->where(function ($sub) use ($search) {
                $sub->where('deskripsi_kerusakan', 'like', "%{$search}%")
                    ->orWhereHas('barang', function ($b) use ($search) {
                        $b->where('nama_barang', 'like', "%{$search}%")
                            ->orWhere('lokasi', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%");
                    });
            });
        });

        // 5. Paginasi & Query String
        $perPage = $request->input('per_page', 10);
        $allowedPerPage = [10, 30, 50, 80, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $laporanku = $query->latest()->paginate($perPage)->withQueryString();

        return view('laporan.index', compact('laporanku', 'perPage'));
    }

    public function createLaporan()
    {
        // Hanya ambil barang yang tidak diapkir
        $barangFasilitas = BarangFasilitas::where('kondisi', '!=', 'Diapkir')->get();
        return view('laporan.create', compact('barangFasilitas'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'prioritas' => 'required|in:Rendah,Sedang,Darurat',
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $idBarang = $request->id_barang;
        $pathFoto = null;

        if ($idBarang !== 'tambah_baru') {
            $request->validate([
                'id_barang' => 'exists:barang_fasilitas,id_barang',
            ]);
        }

        try {
            if ($request->hasFile('foto')) {
                $pathFoto = $request->file('foto')->store(
                    'laporan_kerusakan',
                    'public'
                );
            }

            DB::transaction(function () use ($request, &$idBarang, $pathFoto) {
                if ($idBarang === 'tambah_baru') {
                    $request->validate([
                        'nama_barang_baru' => 'required|string|max:255',
                        'lokasi_baru' => 'required|string|max:255',
                    ]);

                    do {
                        $kodeBarang = 'BRG-' . date('Ymd') . '-' . rand(100, 999);
                    } while (BarangFasilitas::where('kode_barang', $kodeBarang)->exists());

                    $barangBaru = BarangFasilitas::create([
                        'kode_barang' => $kodeBarang,
                        'nama_barang' => $request->nama_barang_baru,
                        'kategori_barang' => 'Umum',
                        'lokasi' => $request->lokasi_baru,
                        'kondisi' => 'Rusak',
                    ]);

                    $idBarang = $barangBaru->id_barang ?? $barangBaru->id;
                }

                $laporan = LaporanKerusakan::create([
                    'id_user' => Auth::id(),
                    'id_barang' => $idBarang,
                    'prioritas' => $request->prioritas,
                    'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
                    'foto_kondisi' => $pathFoto,
                    'status_laporan' => 'Menunggu',
                ]);

                $user = Auth::user();
                $namaUser = $user->nama ?? $user->name ?? 'Karyawan';

                LaporanLog::create([
                    'id_laporan' => $laporan->id_laporan ?? $laporan->getKey(),
                    'status_sebelumnya' => null,
                    'status_sekarang' => 'Menunggu',
                    'keterangan' => 'Laporan pengaduan "' .
                        $request->deskripsi_kerusakan .
                        '" (Prioritas: ' .
                        $request->prioritas .
                        ') berhasil dikirim oleh ' .
                        $namaUser,
                ]);
            });

            return redirect()->route('laporan.index')->with(
                'success',
                'Laporan dan data barang baru berhasil dikirim!'
            );
        } catch (\Throwable $e) {
            if (
                $pathFoto &&
                Storage::disk('public')->exists($pathFoto)
            ) {
                Storage::disk('public')->delete($pathFoto);
            }

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Laporan gagal disimpan. Silakan coba lagi.'
                );
        }
    }

    // ==========================================
    // FITUR: EDIT, UPDATE, & BATALKAN LAPORAN
    // ==========================================

    public function editLaporan($id)
    {
        $laporan = LaporanKerusakan::where('id_laporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with(
                'error',
                'Laporan yang sudah diproses tidak dapat diubah.'
            );
        }

        // Hanya ambil barang yang tidak diapkir
        $barangFasilitas = BarangFasilitas::where('kondisi', '!=', 'Diapkir')->get();

        return view('laporan.edit', compact('laporan', 'barangFasilitas'));
    }

    public function updateLaporan(Request $request, $id)
    {
        $laporan = LaporanKerusakan::where('id_laporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with(
                'error',
                'Laporan yang sudah diproses tidak dapat diubah.'
            );
        }

        $request->validate([
            'id_barang' => 'required|exists:barang_fasilitas,id_barang',
            'deskripsi_kerusakan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pathFoto = $laporan->foto_kondisi;

        if ($request->hasFile('foto')) {
            if (
                $laporan->foto_kondisi &&
                Storage::disk('public')->exists($laporan->foto_kondisi)
            ) {
                Storage::disk('public')->delete($laporan->foto_kondisi);
            }

            $pathFoto = $request->file('foto')->store(
                'laporan_kerusakan',
                'public'
            );
        }

        $laporan->update([
            'id_barang' => $request->id_barang,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kondisi' => $pathFoto,
        ]);

        $user = Auth::user();
        $namaUser = $user ? ($user->nama ?? $user->name) : 'Karyawan';

        LaporanLog::create([
            'id_laporan'        => $laporan->id_laporan ?? $laporan->id,
            'status_sebelumnya' => $laporan->status_laporan,
            'status_sekarang'   => $laporan->status_laporan,
            'keterangan'        => 'Diperbarui oleh ' . $namaUser . ' • "' . $request->deskripsi_kerusakan . '"',
        ]);

        return redirect()->route('laporan.index')->with(
            'success',
            'Laporan berhasil diperbarui.'
        );
    } 

    public function destroyLaporan($id)
    {
        $laporan = LaporanKerusakan::where('id_laporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($laporan->status_laporan != 'Menunggu') {
            return redirect()->route('laporan.index')->with(
                'error',
                'Laporan tidak dapat dibatalkan karena sudah diproses oleh Admin.'
            );
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with(
            'success',
            'Laporan berhasil dibatalkan dan sudah dihapus dari sistem.'
        );
    }

    // ==========================================
    // DETAIL LAPORAN (SHOW)
    // ==========================================
    public function showLaporan($id)
    {
        $laporan = LaporanKerusakan::with([
            'barang',
            'logs',
            'komentars.user'
        ])
            ->where('id_laporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        return view('laporan.show', compact('laporan'));
    }
}