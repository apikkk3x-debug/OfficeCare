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
        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->id('id_pengadaan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_pimpinan')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            $table->string('nama_barang_baru', 150);
            $table->integer('jumlah');
            $table->text('alasan_pengadaan');
            $table->decimal('estimasi_harga', 12, 2);
            $table->enum('status_approval', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->date('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barang');
    }
};
