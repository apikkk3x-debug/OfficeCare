<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
     Schema::create('barang_fasilitas', function (Blueprint $table) {
        $table->id('id_barang');
        $table->string('kode_barang')->unique(); // Tambahan kode unik
        $table->string('nama_barang');
        $table->string('kategori'); // Tambahan kategori
        $table->string('lokasi');
        $table->enum('kondisi', ['Baik', 'Perbaikan Ringan', 'Rusak']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_fasilitas');
    }
};
