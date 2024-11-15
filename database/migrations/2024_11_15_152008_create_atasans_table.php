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
        Schema::create('atasans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nip');
            $table->string('name');
            $table->string('jabatan');
            $table->foreignId('pangkat_id')->nullable()->constrained('pangkats')->onDelete('set null');
            $table->string('unit_kerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atasans');
    }
};