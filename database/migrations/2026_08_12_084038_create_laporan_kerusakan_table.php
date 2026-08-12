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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barang_fasilitas', 'id_barang')->onDelete('cascade');
            $table->timestamp('tanggal_lapor')->useCurrent();
            $table->text('deskripsi_kerusakan');
            $table->string('foto_kondisi')->nullable();
            $table->enum('status_laporan', ['Menunggu', 'Diproses', 'Selesai', 'Dialihkan ke Pengadaan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};
