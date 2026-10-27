<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengingat extends Model
{
    use HasFactory;

    protected $table = 'pengingats';
    protected $fillable = ['laporan_id', 'pimpinan_id', 'pesan', 'is_read'];

    public function laporan()
    {
        // Ditambahkan 'id_laporan' sebagai parameter ke-3 (ownerKey)
        return $this->belongsTo(LaporanKerusakan::class, 'laporan_id', 'id_laporan');
    }

    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'pimpinan_id', 'id_user');
    }
}