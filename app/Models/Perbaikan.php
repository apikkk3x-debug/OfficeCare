<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan';
    protected $primaryKey = 'id_perbaikan';

    protected $fillable = [
        'id_laporan',
        'id_admin',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan_teknisi',
        'biaya_perbaikan',
    ];

    // Relasi: Perbaikan ini terkait dengan 1 Laporan Kerusakan
    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan', 'id_laporan');
    }

    // Relasi: Perbaikan ini dikerjakan oleh 1 Admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_user');
    }
}