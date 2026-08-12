<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengadaan_barang';
    protected $primaryKey = 'id_pengadaan';

    protected $fillable = [
        'id_user',
        'id_pimpinan',
        'nama_barang_baru',
        'jumlah',
        'alasan_pengadaan',
        'estimasi_harga',
        'status_approval',
        'tanggal_approval',
    ];

    // Relasi: Pengajuan ini dibuat oleh 1 User (Pemohon)
    public function pemohon()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Pengajuan ini disetujui/ditolak oleh 1 Pimpinan
    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'id_pimpinan', 'id_user');
    }
}