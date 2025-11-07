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
        Schema::create('riwayat_penggantian_mitra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mitra_lama_id')->nullable();
            $table->unsignedBigInteger('mitra_baru_id');
            $table->enum('jenis_alasan', ['ditolak', 'alasan_tertentu', 'pilihan_pribadi']);
            $table->text('alasan_lengkap')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mitra_lama_id')->references('id')->on('mitra')->onDelete('set null');
            $table->foreign('mitra_baru_id')->references('id')->on('mitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penggantian_mitra');
    }
};
