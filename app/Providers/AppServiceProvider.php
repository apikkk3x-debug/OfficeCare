<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Pengumuman;
use App\Models\LaporanKerusakan;
use App\Models\LaporanLog;
use App\Models\PengadaanBarang;
use App\Models\LaporanKomentar;
use App\Models\User;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View Composer untuk membagikan data notifikasi dinamis sesuai role pengguna
        View::composer(['layouts.app', 'layouts.admin', 'layouts.karyawan', 'components.navbar'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $userId = $user->id_user ?? $user->id;
                $userRole = strtolower($user->role ?? 'karyawan');

                // Ambil daftar ID notifikasi yang sudah dibaca oleh user ini dari database
                $readNotificationIds = [];
                if (Schema::hasTable('notification_reads')) {
                    $readNotificationIds = DB::table('notification_reads')
                        ->where('id_user', $userId)
                        ->pluck('notification_id')
                        ->toArray();
                }

                $allNotifications = collect();

                // ==========================================
                // 1. NOTIFIKASI KHUSUS KARYAWAN
                // ==========================================
                if ($userRole === 'karyawan') {
                    // A. Pengumuman Aktif untuk Karyawan
                    if (class_exists(Pengumuman::class)) {
                        $pengumuman = Pengumuman::where('is_active', true)
                            ->whereIn('target_role', ['karyawan', 'semua'])
                            ->latest()
                            ->take(3)
                            ->get()
                            ->map(function ($p) {
                                return [
                                    'id'          => 'p-' . $p->id,
                                    'type'        => 'pengumuman',
                                    'title'       => 'Pengumuman: ' . $p->judul,
                                    'desc'        => Str::limit($p->pesan, 55),
                                    'time'        => $p->created_at,
                                    'time_ago'    => $p->created_at ? $p->created_at->diffForHumans() : 'Baru saja',
                                    'url'         => route('karyawan.dashboard') . '?highlight_pengumuman=' . $p->id,
                                    'badge'       => 'Pengumuman',
                                    'badge_style' => 'bg-amber-100 text-amber-700 border-amber-200',
                                ];
                            });
                        $allNotifications = $allNotifications->concat($pengumuman);
                    }

                    // B. Pembaruan Status Laporan Kerusakan Milik Karyawan
                    $logs = LaporanLog::with(['laporan.barang'])
                        ->whereHas('laporan', function ($q) use ($userId) {
                            $q->where('id_user', $userId);
                        })
                        ->where('status_sekarang', '!=', 'Menunggu')
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($l) {
                            $status = $l->status_sekarang ?? 'Pembaruan';
                            $namaBarang = $l->laporan->barang->nama_barang ?? 'Fasilitas Kantor';
                            $title = match ($status) {
                                'Selesai'  => 'Laporan Selesai Diperbaiki',
                                'Diproses' => 'Laporan Sedang Diproses',
                                'Ditolak'  => 'Laporan Ditolak',
                                default    => 'Pembaruan Status Laporan',
                            };

                            $badgeStyle = match (strtolower($status)) {
                                'selesai'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'diproses' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                'ditolak'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                default    => 'bg-amber-100 text-amber-700 border-amber-200',
                            };

                            $desc = "Status pengaduan '{$namaBarang}' kini: {$status}.";
                            if (!empty($l->keterangan)) {
                                $desc .= ' ' . Str::limit($l->keterangan, 50);
                            }

                            return [
                                'id'          => 'l-' . $l->id,
                                'type'        => 'laporan',
                                'title'       => $title,
                                'desc'        => $desc,
                                'time'        => $l->created_at,
                                'time_ago'    => $l->created_at ? $l->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('laporan.show', $l->id_laporan) . '?highlight_log=' . $l->id,
                                'badge'       => ucfirst($status),
                                'badge_style' => $badgeStyle,
                            ];
                        });
                    $allNotifications = $allNotifications->concat($logs);

                    // C. Status Pengadaan Barang Milik Karyawan (Setelah diverifikasi Pimpinan/Admin)
                    $pengadaan = PengadaanBarang::where('id_user', $userId)
                        ->whereNotIn('status_approval', ['Pending', 'pending', 'menunggu', 'Menunggu'])
                        ->latest()
                        ->take(4)
                        ->get()
                        ->map(function ($pr) {
                            $status = $pr->status_approval ?? 'Diproses';
                            $namaBarang = $pr->nama_barang_baru ?? 'Barang';
                            $idPengadaan = $pr->id_pengadaan ?? $pr->id;

                            $title = match (strtolower($status)) {
                                'disetujui' => 'Pengadaan Disetujui Pimpinan',
                                'ditolak'   => 'Pengadaan Ditolak Pimpinan',
                                'selesai'   => 'Pengadaan Selesai (Barang Tersedia)',
                                default     => 'Status Pengadaan: ' . ucfirst($status),
                            };

                            $badgeStyle = match (strtolower($status)) {
                                'disetujui', 'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'ditolak'              => 'bg-rose-100 text-rose-700 border-rose-200',
                                default                => 'bg-blue-100 text-blue-700 border-blue-200',
                            };

                            $desc = "Pengajuan '{$namaBarang}' ({$pr->jumlah} unit) telah " . strtolower($status) . ".";
                            if (!empty($pr->catatan_pimpinan)) {
                                $desc .= " Catatan: '" . Str::limit($pr->catatan_pimpinan, 40) . "'";
                            }

                            return [
                                'id'          => 'pr-' . $idPengadaan,
                                'type'        => 'pengadaan',
                                'title'       => $title,
                                'desc'        => $desc,
                                'time'        => $pr->updated_at ?? $pr->created_at,
                                'time_ago'    => ($pr->updated_at ?? $pr->created_at)?->diffForHumans() ?? 'Baru saja',
                                'url'         => route('karyawan.pengadaan.show', $idPengadaan) . '?highlight_pengadaan=' . $idPengadaan,
                                'badge'       => ucfirst($status),
                                'badge_style' => $badgeStyle,
                            ];
                        });
                    $allNotifications = $allNotifications->concat($pengadaan);

                    // D. Tanggapan / Balasan Chat pada Laporan Karyawan
                    $komentars = LaporanKomentar::with(['laporan.barang', 'user'])
                        ->whereHas('laporan', function ($q) use ($userId) {
                            $q->where('id_user', $userId);
                        })
                        ->where('id_user', '!=', $userId)
                        ->latest()
                        ->take(4)
                        ->get()
                        ->map(function ($k) {
                            $namaPengirim = $k->user->nama ?? $k->user->name ?? 'Admin';
                            $namaBarang = $k->laporan->barang->nama_barang ?? 'Laporan';

                            return [
                                'id'          => 'k-' . $k->id,
                                'type'        => 'komentar',
                                'title'       => 'Tanggapan dari ' . $namaPengirim,
                                'desc'        => "Pada '{$namaBarang}': \"" . Str::limit($k->pesan, 45) . '"',
                                'time'        => $k->created_at,
                                'time_ago'    => $k->created_at ? $k->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('laporan.show', $k->id_laporan) . '?highlight_komentar=' . $k->id,
                                'badge'       => 'Pesan Baru',
                                'badge_style' => 'bg-purple-100 text-purple-700 border-purple-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($komentars);

                // ==========================================
                // 2. NOTIFIKASI KHUSUS ADMIN
                // ==========================================
                } elseif ($userRole === 'admin') {
                    // A. Laporan Kerusakan Baru Masuk & Darurat
                    $laporans = LaporanKerusakan::with(['barang', 'user'])
                        ->whereIn('status_laporan', ['Menunggu', 'Diproses'])
                        ->latest()
                        ->take(6)
                        ->get()
                        ->map(function ($l) {
                            $prioritas = $l->prioritas ?? 'Sedang';
                            $namaPelapor = $l->user->nama ?? $l->user->name ?? 'Karyawan';
                            $namaBarang = $l->barang->nama_barang ?? 'Fasilitas Kantor';
                            $idLaporan = $l->id_laporan ?? $l->id;

                            $isDarurat = strtolower($prioritas) === 'darurat';
                            $title = $isDarurat ? '⚡ Pengaduan Darurat: ' . $namaBarang : 'Laporan Baru: ' . $namaBarang;
                            $badge = $isDarurat ? '⚡ Darurat' : ucfirst($l->status_laporan);
                            $badgeStyle = $isDarurat
                                ? 'bg-rose-100 text-rose-700 border-rose-200'
                                : 'bg-amber-100 text-amber-700 border-amber-200';

                            return [
                                'id'          => 'l-' . $idLaporan,
                                'type'        => 'laporan',
                                'title'       => $title,
                                'desc'        => "{$namaPelapor} melaporkan kerusakan (Prioritas {$prioritas}): \"" . Str::limit($l->deskripsi_kerusakan, 50) . '"',
                                'time'        => $l->created_at,
                                'time_ago'    => $l->created_at ? $l->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('admin.laporan.show', $idLaporan),
                                'badge'       => $badge,
                                'badge_style' => $badgeStyle,
                            ];
                        });
                    $allNotifications = $allNotifications->concat($laporans);

                    // B. Pengadaan yang Sudah Disetujui Pimpinan (Admin Siap Membeli / Memproses ke Aset)
                    $pengadaanDisetujui = PengadaanBarang::with('pemohon')
                        ->whereIn('status_approval', ['disetujui', 'Disetujui'])
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($pr) {
                            $namaPemohon = $pr->pemohon->nama ?? $pr->pemohon->name ?? 'Karyawan';
                            $namaBarang = $pr->nama_barang_baru ?? 'Barang';
                            $idPengadaan = $pr->id_pengadaan ?? $pr->id;

                            return [
                                'id'          => 'pr-' . $idPengadaan,
                                'type'        => 'pengadaan',
                                'title'       => 'Pengadaan Siap Dibeli: ' . $namaBarang,
                                'desc'        => "Pengajuan {$pr->jumlah} unit oleh {$namaPemohon} telah disetujui Pimpinan. Silakan lakukan pembelian dan masukkan ke aset.",
                                'time'        => $pr->tanggal_approval ?? $pr->updated_at ?? $pr->created_at,
                                'time_ago'    => ($pr->updated_at ?? $pr->created_at)?->diffForHumans() ?? 'Baru saja',
                                'url'         => route('admin.pengadaan.index') . '?highlight_pengadaan=' . $idPengadaan,
                                'badge'       => 'Siap Beli',
                                'badge_style' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($pengadaanDisetujui);

                    // C. Pesan / Diskusi Baru dari Karyawan pada Seluruh Laporan
                    $komentars = LaporanKomentar::with(['laporan.barang', 'user'])
                        ->where('id_user', '!=', $userId)
                        ->latest()
                        ->take(4)
                        ->get()
                        ->map(function ($k) {
                            $namaPengirim = $k->user->nama ?? $k->user->name ?? 'Karyawan';
                            $namaBarang = $k->laporan->barang->nama_barang ?? 'Laporan';

                            return [
                                'id'          => 'k-' . $k->id,
                                'type'        => 'komentar',
                                'title'       => 'Pesan dari ' . $namaPengirim,
                                'desc'        => "Laporan '{$namaBarang}': \"" . Str::limit($k->pesan, 45) . '"',
                                'time'        => $k->created_at,
                                'time_ago'    => $k->created_at ? $k->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('admin.laporan.show', $k->id_laporan) . '?highlight_komentar=' . $k->id,
                                'badge'       => 'Diskusi',
                                'badge_style' => 'bg-purple-100 text-purple-700 border-purple-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($komentars);

                    // D. Pendaftaran Pengguna Baru Menunggu Verifikasi Akun
                    $pendingUsers = User::where('status', 'pending')
                        ->latest()
                        ->take(3)
                        ->get()
                        ->map(function ($u) {
                            return [
                                'id'          => 'u-' . $u->id_user,
                                'type'        => 'user',
                                'title'       => 'Registrasi Akun: ' . ($u->nama ?? 'Pengguna Baru'),
                                'desc'        => "{$u->nama} ({$u->email}, Divisi: {$u->divisi}) mendaftar dan menunggu verifikasi.",
                                'time'        => $u->created_at,
                                'time_ago'    => $u->created_at ? $u->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('admin.users'),
                                'badge'       => 'Verifikasi User',
                                'badge_style' => 'bg-blue-100 text-blue-700 border-blue-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($pendingUsers);

                    // E. Pengumuman Aktif untuk Admin
                    if (class_exists(Pengumuman::class)) {
                        $pengumumanAdmin = Pengumuman::where('is_active', true)
                            ->whereIn('target_role', ['admin', 'semua'])
                            ->latest()
                            ->take(2)
                            ->get()
                            ->map(function ($p) {
                                return [
                                    'id'          => 'p-' . $p->id,
                                    'type'        => 'pengumuman',
                                    'title'       => 'Pengumuman: ' . $p->judul,
                                    'desc'        => Str::limit($p->pesan, 50),
                                    'time'        => $p->created_at,
                                    'time_ago'    => $p->created_at ? $p->created_at->diffForHumans() : 'Baru saja',
                                    'url'         => route('admin.pengumuman.index'),
                                    'badge'       => 'Info',
                                    'badge_style' => 'bg-amber-100 text-amber-700 border-amber-200',
                                ];
                            });
                        $allNotifications = $allNotifications->concat($pengumumanAdmin);
                    }

                // ==========================================
                // 3. NOTIFIKASI KHUSUS PIMPINAN
                // ==========================================
                } elseif ($userRole === 'pimpinan') {
                    // A. Permohonan Pengadaan Barang Masuk (Menunggu Persetujuan / Approval)
                    $pengadaanPimpinan = PengadaanBarang::with('pemohon')
                        ->whereIn('status_approval', ['pending', 'Pending', 'menunggu', 'Menunggu'])
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($pr) {
                            $namaPemohon = $pr->pemohon->nama ?? $pr->pemohon->name ?? 'Karyawan';
                            $namaBarang = $pr->nama_barang_baru ?? 'Barang';
                            $harga = number_format($pr->estimasi_harga ?? 0, 0, ',', '.');
                            $idPengadaan = $pr->id_pengadaan ?? $pr->id;

                            return [
                                'id'          => 'pr-' . $idPengadaan,
                                'type'        => 'pengadaan',
                                'title'       => 'Permohonan Pengadaan Masuk',
                                'desc'        => "{$namaPemohon} mengajukan {$namaBarang} ({$pr->jumlah} unit, Rp {$harga}). Menunggu persetujuan Anda.",
                                'time'        => $pr->created_at,
                                'time_ago'    => $pr->created_at ? $pr->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('pimpinan.pengadaan.index') . '?highlight_pengadaan=' . $idPengadaan,
                                'badge'       => 'Menunggu Persetujuan',
                                'badge_style' => 'bg-purple-100 text-purple-700 border-purple-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($pengadaanPimpinan);

                    // B. Laporan Kerusakan Darurat (Urgent Incident Watch)
                    $laporanDarurat = LaporanKerusakan::with(['barang', 'user'])
                        ->where('prioritas', 'Darurat')
                        ->where('status_laporan', '!=', 'Selesai')
                        ->latest()
                        ->take(4)
                        ->get()
                        ->map(function ($l) {
                            $namaPelapor = $l->user->nama ?? $l->user->name ?? 'Karyawan';
                            $namaBarang = $l->barang->nama_barang ?? 'Fasilitas Kantor';
                            $lokasi = $l->barang->lokasi ?? 'Kantor';
                            $idLaporan = $l->id_laporan ?? $l->id;

                            return [
                                'id'          => 'l-' . $idLaporan,
                                'type'        => 'laporan',
                                'title'       => '⚠️ Kerusakan Fasilitas Darurat',
                                'desc'        => "{$namaBarang} di {$lokasi} rusak darurat (Pelapor: {$namaPelapor}). Pantau penanganan sarpras.",
                                'time'        => $l->created_at,
                                'time_ago'    => $l->created_at ? $l->created_at->diffForHumans() : 'Baru saja',
                                'url'         => route('pimpinan.rekap', ['status' => $l->status_laporan ?? 'Diproses']),
                                'badge'       => '⚡ Darurat',
                                'badge_style' => 'bg-rose-100 text-rose-700 border-rose-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($laporanDarurat);

                    // C. Pengadaan yang Selesai Direalisasikan oleh Admin
                    $pengadaanSelesai = PengadaanBarang::where('status_approval', 'selesai')
                        ->latest()
                        ->take(3)
                        ->get()
                        ->map(function ($pr) {
                            $namaBarang = $pr->nama_barang_baru ?? 'Barang';
                            $idPengadaan = $pr->id_pengadaan ?? $pr->id;

                            return [
                                'id'          => 'pr-' . $idPengadaan,
                                'type'        => 'pengadaan',
                                'title'       => 'Pengadaan Selesai Dilaksanakan',
                                'desc'        => "Pengadaan '{$namaBarang}' telah dibeli tim sarpras dan resmi masuk inventaris aset.",
                                'time'        => $pr->updated_at ?? $pr->created_at,
                                'time_ago'    => ($pr->updated_at ?? $pr->created_at)?->diffForHumans() ?? 'Baru saja',
                                'url'         => route('pimpinan.pengadaan.index') . '?highlight_pengadaan=' . $idPengadaan,
                                'badge'       => 'Selesai Dibeli',
                                'badge_style' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            ];
                        });
                    $allNotifications = $allNotifications->concat($pengadaanSelesai);

                    // D. Pengumuman / Info Eksekutif Aktif
                    if (class_exists(Pengumuman::class)) {
                        $pengumumanPimpinan = Pengumuman::where('is_active', true)
                            ->whereIn('target_role', ['pimpinan', 'semua'])
                            ->latest()
                            ->take(2)
                            ->get()
                            ->map(function ($p) {
                                return [
                                    'id'          => 'p-' . $p->id,
                                    'type'        => 'pengumuman',
                                    'title'       => 'Info Eksekutif: ' . $p->judul,
                                    'desc'        => Str::limit($p->pesan, 55),
                                    'time'        => $p->created_at,
                                    'time_ago'    => $p->created_at ? $p->created_at->diffForHumans() : 'Baru saja',
                                    'url'         => route('pimpinan.dashboard'),
                                    'badge'       => 'Eksekutif',
                                    'badge_style' => 'bg-amber-100 text-amber-700 border-amber-200',
                                ];
                            });
                        $allNotifications = $allNotifications->concat($pengumumanPimpinan);
                    }
                }

                // Saring notifikasi yang sudah pernah dibaca oleh user ini di database
                if (!empty($readNotificationIds)) {
                    $allNotifications = $allNotifications->reject(function ($notif) use ($readNotificationIds) {
                        return in_array($notif['id'], $readNotificationIds);
                    });
                }

                // Urutkan berdasarkan waktu terbaru dan ambil maksimal 10 data
                $sortedNotifications = $allNotifications
                    ->sortByDesc('time')
                    ->take(10)
                    ->values();

                $view->with('globalNotifications', $sortedNotifications);
            }
        });
    }
}