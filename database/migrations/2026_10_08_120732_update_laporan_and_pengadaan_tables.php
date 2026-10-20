<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hubungkan tabel pengadaan_barang ke laporan_kerusakan
        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->foreignId('id_laporan')
                  ->nullable()
                  ->after('id_user')
                  ->constrained('laporan_kerusakan', 'id_laporan')
                  ->nullOnDelete();
        });

        // 2. Perbarui Enum status_laporan di tabel laporan_kerusakan jadi 5 status resmi
        DB::statement("ALTER TABLE laporan_kerusakan MODIFY COLUMN status_laporan ENUM('Menunggu', 'Diproses', 'Dialihkan ke Pengadaan', 'Selesai', 'Ditolak') DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->dropForeign(['id_laporan']);
            $table->dropColumn('id_laporan');
        });

        DB::statement("ALTER TABLE laporan_kerusakan MODIFY COLUMN status_laporan ENUM('Menunggu', 'Diproses', 'Selesai', 'Dialihkan ke Pengadaan') DEFAULT 'Menunggu'");
    }
};