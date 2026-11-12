<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('nik', 16);
            $table->string('nidn')->nullable();
            $table->string('nama');
            $table->string('hp');
            $table->string('email');
            $table->string('tipe')->default('Dosen UNSRI'); // 'Dosen UNSRI', 'Dosen Luar'
            $table->string('status')->default('Menunggu Verifikasi'); // 'Menunggu Verifikasi', 'Terverifikasi', 'Ditolak'
            $table->string('bukti')->nullable();
            $table->text('alasan')->nullable();
            $table->string('el_username')->nullable();
            $table->string('el_password')->nullable();
            $table->string('wa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
