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
        Schema::create('laporan_komentars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan');
            $table->unsignedBigInteger('id_user');
            $table->text('pesan');
            $table->timestamps();

            // Foreign keys (sesuaikan dengan primary key tabel kamu)
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_kerusakan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_komentars');
    }
};
