<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanLog extends Model
{
    use HasFactory;

    protected $table = 'laporan_logs'; // Menyesuaikan nama tabel di database

    protected $fillable = [
        'id_laporan',
        'status_sebelumnya',
        'status_sekarang',
        'keterangan',
    ];

    // Relasi balik ke laporan kerusakan
    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan', 'id_laporan');
    }
}