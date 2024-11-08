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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('nip')->nullable()->unique();
            $table->enum('pangkat', [
                'I/a-Juru Muda',
                'I/b-Juru Muda tingkat I',
                'I/c-Juru',
                'I/d-Juru tingkat I',
                'II/a-Pengatur Muda',
                'II/b-Pengatur Muda TK.I',
                'II/c-Pengatur',
                'II/d-Pengatur TK.I',
                'III/a-Penata Muda',
                'III/b-Penata Muda TK.I',
                'III/c-Penata',
                'III/d-Penata TK.I',
                'IV/a-Pembina',
                'IV/b-Pembina TK.I',
                'IV/c-Pembina Utama Muda',
                'IV/d-Pembina Utama Madya',
                'IV/e-Pembina Utama',
                'V-V',
                'VII-VII',
                'IX-IX',
                'X-X',
                'VIII-VIII'
            ])->nullable();
            $table->string('unit_kerja')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
