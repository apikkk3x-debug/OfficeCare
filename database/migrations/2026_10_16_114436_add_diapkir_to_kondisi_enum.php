<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE barang_fasilitas MODIFY COLUMN kondisi ENUM('Baik', 'Perbaikan Ringan', 'Rusak', 'Diapkir') DEFAULT 'Baik'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE barang_fasilitas MODIFY COLUMN kondisi ENUM('Baik', 'Perbaikan Ringan', 'Rusak') DEFAULT 'Baik'");
    }
};