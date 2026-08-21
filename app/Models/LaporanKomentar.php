<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKomentar extends Model
{
    use HasFactory;

    protected $table = 'laporan_komentars';

    protected $fillable = [
        'id_laporan',
        'id_user',
        'pesan',
    ];

    // Relasi: Komentar ini ditulis oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Komentar ini milik 1 Laporan Kerusakan
    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan', 'id_laporan');
    }
}