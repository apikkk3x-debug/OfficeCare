<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangFasilitas extends Model
{
    use HasFactory;

    protected $table = 'barang_fasilitas';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',     // <-- Tambahkan baris ini agar diizinkan masuk ke database
        'nama_barang',
        'kategori_barang',
        'lokasi',
        'kondisi',
    ];

    // Relasi: 1 Barang bisa memiliki banyak riwayat Laporan Kerusakan
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_barang', 'id_barang');
    }
}