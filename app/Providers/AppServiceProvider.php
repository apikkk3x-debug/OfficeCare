<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;
use App\Models\LaporanLog;
use App\Models\PengadaanBarang;
use App\Models\LaporanKomentar;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $userId = $user->id;
                $userRole = strtolower($user->role ?? 'karyawan');

              // 1. Pengumuman Terbaru (Hanya muncul sebagai notifikasi untuk Karyawan)
                $pengumumanNotif = collect();
                if ($userRole === 'karyawan') {
                    $pengumumanNotif = Pengumuman::latest()->take(2)->get()->map(function($p) {
                        return [
                            'id'          => 'p-' . $p->id,
                            'type'        => 'pengumuman',
                            'title'       => $p->judul,
                            'desc'        => Str::limit($p->pesan, 50),
                            'time'        => $p->created_at,
                            'url'         => route('karyawan.dashboard') . '?highlight_pengumuman=' . $p->id,
                            'badge'       => 'Info',
                            'badge_style' => 'bg-amber-100 text-amber-700 border-amber-200',
                        ];
                    });
                }
                // 2. Log Status Laporan Kerusakan (Berdasarkan Role)
                $logQuery = LaporanLog::with('laporan.barang')->latest();
                if ($userRole === 'karyawan') {
                    $logQuery->whereHas('laporan', function($q) use ($userId) {
                        $q->where('id_user', $userId);
                    });
                } elseif ($userRole === 'admin') {
                    // Admin hanya perlu tahu laporan baru yang masih "Menunggu" penanganan
                    $logQuery->where('status_sekarang', 'Menunggu');
                } else {
                    $logQuery->whereRaw('0 = 1'); // Pimpinan tidak perlu notifikasi log perbaikan
                }

                $logNotif = $logQuery->take(3)->get()->map(function($l) {
                    $status = $l->status_sekarang ?? 'Pembaruan';
                    return [
                        'id'          => 'l-' . $l->id,
                        'type'        => 'laporan',
                        'title'       => 'Laporan ' . ($l->laporan->barang->nama_barang ?? 'Fasilitas'),
                        'desc'        => 'Status: ' . ucfirst($status),
                        'time'        => $l->created_at,
                        'url'         => route('laporan.show', $l->id_laporan ?? 1) . '?highlight_log=' . $l->id,
                        'badge'       => ucfirst($status),
                        'badge_style' => 'bg-amber-100 text-amber-700 border-amber-200',
                    ];
                });

                // 3. Status Pengadaan Barang (Pemisahan Tugas yang Tegas)
                $pengadaanBaseUrl = match($userRole) {
                    'admin'    => route('admin.pengadaan.index'),
                    'pimpinan' => route('pimpinan.pengadaan.index'),
                    default    => route('karyawan.pengadaan.index'),
                };

                $pengadaanQuery = PengadaanBarang::latest();
                if ($userRole === 'pimpinan') {
                    // Pimpinan hanya melihat pengajuan baru dari karyawan yang butuh ACC (Pending)
                    $pengadaanQuery->where('status_approval', 'Pending');
                } elseif ($userRole === 'admin') {
                    // Admin hanya melihat pengadaan yang SUDAH DISETUJUI pimpinan untuk dieksekusi/dibeli
                    $pengadaanQuery->where('status_approval', 'Disetujui');
                } else {
                    // Karyawan melihat pengajuannya sendiri setelah diproses pimpinan
                    $pengadaanQuery->where('id_user', $userId)
                                   ->where('status_approval', '!=', 'Pending');
                }

                $pengadaanNotif = $pengadaanQuery->take(3)->get()->map(function($pr) use ($pengadaanBaseUrl) {
                    $status = $pr->status_approval ?? 'Pending'; 
                    $badgeStyle = match(strtolower($status)) {
                        'disetujui', 'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'ditolak'              => 'bg-rose-100 text-rose-700 border-rose-200',
                        default                => 'bg-blue-100 text-blue-700 border-blue-200',
                    };

                    $idPengadaan = $pr->id_pengadaan ?? $pr->id;

                    return [
                        'id'          => 'pr-' . $idPengadaan,
                        'type'        => 'pengadaan',
                        'title'       => 'Pengadaan: ' . ($pr->nama_barang_baru ?? 'Barang'),
                        'desc'        => 'Status: ' . ucfirst($status),
                        'time'        => $pr->created_at,
                        'url'         => $pengadaanBaseUrl . '?highlight_pengadaan=' . $idPengadaan,
                        'badge'       => ucfirst($status),
                        'badge_style' => $badgeStyle,
                    ];
                });

             // 4. Komentar Baru pada Laporan (Hanya untuk Admin & Karyawan)
$komentarNotif = collect(); // Inisialisasi kosong terlebih dahulu

if ($userRole !== 'pimpinan') {
    $komentarNotif = LaporanKomentar::with('laporan')->latest()->take(2)->get()->map(function($k) use ($userRole) {
        
        // Tentukan URL tujuan berdasarkan role yang sedang login
        $komentarUrl = match($userRole) {
            'admin'    => route('admin.laporan.show', $k->id_laporan ?? 1) . '?highlight_komentar=' . $k->id,
            default    => route('laporan.show', $k->id_laporan ?? 1) . '?highlight_komentar=' . $k->id,
        };

        return [
            'id'          => 'k-' . $k->id,
            'type'        => 'komentar',
            'title'       => 'Komentar pada Laporan',
            'desc'        => Str::limit($k->komentar ?? 'Ada tanggapan baru', 45),
            'time'        => $k->created_at,
            'url'         => $komentarUrl, // Menggunakan URL dinamis
            'badge'       => 'Pesan',
            'badge_style' => 'bg-purple-100 text-purple-700 border-purple-200',
        ];
    });
}

                // Gabungkan seluruh jenis notifikasi, urutkan, dan reset array
                $allNotifications = $pengumumanNotif
                    ->concat($logNotif)
                    ->concat($pengadaanNotif)
                    ->concat($komentarNotif)
                    ->sortByDesc('time')
                    ->take(8)
                    ->values();

                $view->with('globalNotifications', $allNotifications);
            }
        });
    }
}