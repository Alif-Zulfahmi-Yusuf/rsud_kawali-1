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
        Schema::create('realisasi_rencanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_pegawai_id')->references('id')->on('evaluasi_pegawais')->onDelete('cascade');
            $table->foreignId('rencana_pegawai_id')->references('id')->on('rencana_hasil_kerja_pegawai')->onDelete('cascade');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_rencanas');
    }
};