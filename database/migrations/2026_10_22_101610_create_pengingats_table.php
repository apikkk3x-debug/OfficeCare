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
        Schema::create('pengingats', function (Blueprint $table) {
            $table->id();
            
            // Disesuaikan ke primary key 'id_laporan' di tabel laporan_kerusakan
            $table->foreignId('laporan_id')->constrained('laporan_kerusakan', 'id_laporan')->onDelete('cascade');
            
            // Primary key 'id_user' di tabel users
            $table->foreignId('pimpinan_id')->constrained('users', 'id_user')->onDelete('cascade');
            
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengingats');
    }
};