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
        Schema::create('rencana_indikator_kinerja', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('rencana_kerja_pegawai_id')->constrained('rencana_hasil_kerja_pegawai')->onDelete('cascade');
            $table->foreignId('rencana_kerja_atasan_id')->constrained('rencana_hasil_kerja')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('skp_id')->constrained('skps')->onDelete('cascade');
            $table->enum('aspek', ['kualitas', 'kuantitas', 'waktu']);
            $table->string('indikator_kinerja');
            $table->enum('tipe_target', ['satu_nilai', 'range_nilai', 'kualitatif'])->nullable();
            $table->string('target_minimum')->nullable();
            $table->string('target_maksimum')->nullable();
            $table->string('satuan')->nullable();
            $table->enum('report', ['bulanan', 'triwulan', 'semesteran', 'tahunan'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_indikator_kinerja');
    }
};