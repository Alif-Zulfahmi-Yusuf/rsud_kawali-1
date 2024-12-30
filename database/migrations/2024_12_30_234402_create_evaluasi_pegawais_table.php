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
        Schema::create('evaluasi_pegawais', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('skp_id')->constrained('skps')->onDelete('cascade');
            $table->foreignId('rencana_pegawai_id')->constrained('rencana_hasil_kerja_pegawai')->onDelete('cascade')->nullable();
            $table->date('bulan')->nullable();
            $table->date('tanggal_capaian')->nullable();
            $table->enum('status', ['review', 'selesai', 'revisi'])->default('review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_pegawais');
    }
};