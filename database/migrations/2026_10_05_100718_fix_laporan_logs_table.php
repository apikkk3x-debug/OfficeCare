<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_logs', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->change();

            $table->foreign('id_laporan')
                ->references('id_laporan')
                ->on('laporan_kerusakan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_logs', function (Blueprint $table) {
            $table->dropForeign(['id_laporan']);

            $table->string('keterangan', 255)->nullable()->change();
        });
    }
};