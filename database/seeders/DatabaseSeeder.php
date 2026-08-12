<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BarangFasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Contoh User untuk 3 Role
        User::create([
            'nama' => 'Admin Sarpras',
            'email' => 'admin@officecare.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Ahmad Karyawan',
            'email' => 'karyawan@officecare.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
        ]);

        User::create([
            'nama' => 'Bapak Pimpinan',
            'email' => 'pimpinan@officecare.com',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);

        // 2. Buat Contoh Data Fasilitas Kantor
        BarangFasilitas::create([
            'nama_barang' => 'AC Daikin 1.5 PK',
            'kategori_barang' => 'Elektronik',
            'lokasi' => 'Ruang Rapat Utama Lantai 2',
            'kondisi' => 'Baik',
        ]);

        BarangFasilitas::create([
            'nama_barang' => 'Proyektor Epson EB-X500',
            'kategori_barang' => 'Elektronik',
            'lokasi' => 'Ruang Presentasi A',
            'kondisi' => 'Rusak Ringan',
        ]);

        BarangFasilitas::create([
            'nama_barang' => 'Kursi Kerja Ergonomis',
            'kategori_barang' => 'Furnitur',
            'lokasi' => 'Divisi IT Lantai 3',
            'kondisi' => 'Baik',
        ]);

        BarangFasilitas::create([
            'nama_barang' => 'Printer HP LaserJet Pro',
            'kategori_barang' => 'Elektronik',
            'lokasi' => 'Ruang Administrasi',
            'kondisi' => 'Baik',
        ]);
    }
}