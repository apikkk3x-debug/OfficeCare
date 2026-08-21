<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_user',
        'id_barang',
        'tanggal_lapor',
        'deskripsi_kerusakan',
        'foto_kondisi',
        'status_laporan',
    ];

    // Relasi: Laporan ini dibuat oleh 1 User (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Laporan ini mengarah ke 1 Barang Fasilitas
    public function barang()
    {
        return $this->belongsTo(BarangFasilitas::class, 'id_barang', 'id_barang');
    }

    // Relasi: 1 Laporan bisa memiliki 1 data Perbaikan
    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_laporan', 'id_laporan');
    }

    public function logs()
    {
        return $this->hasMany(LaporanLog::class, 'id_laporan', 'id_laporan');
    }
    public function komentars()
    {
        return $this->hasMany(LaporanKomentar::class, 'id_laporan', 'id_laporan');
    }
}