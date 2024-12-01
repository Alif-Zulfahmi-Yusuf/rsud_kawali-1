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
        Schema::create('skps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('atasan_id')->nullable()->constrained('atasans')->onDelete('cascade');
            $table->string('unit_kerja');
            $table->integer('tahun'); // Tahun SKP
            $table->string('module'); // Module (kuantitatif/kualitatif)
            $table->date('tanggal_skp'); // Tanggal awal SKP
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status', ['pending', 'approve', 'revisi'])->default('pending'); // Tanggal akhir otomatis akhir tahun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skps');
    }
};