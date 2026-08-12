<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi: 1 User bisa membuat banyak Laporan Kerusakan
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_user', 'id_user');
    }

    // Relasi: 1 User (Admin) bisa menangani banyak Perbaikan
    public function perbaikan()
    {
        return $this->hasMany(Perbaikan::class, 'id_admin', 'id_user');
    }

    // Relasi: 1 User bisa mengajukan banyak Pengadaan Barang
    public function pengadaan()
    {
        return $this->hasMany(PengadaanBarang::class, 'id_user', 'id_user');
    }
}