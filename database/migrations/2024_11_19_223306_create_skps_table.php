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
            $table->foreign('data_skp_id')->references('id')->on('skps')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->timestamp('tanggal_skp');
            $table->date('tanggal_akhir');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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