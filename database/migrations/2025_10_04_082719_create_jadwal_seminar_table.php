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
        Schema::create('jadwal_seminar', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('subjudul', 300)->nullable();
            $table->enum('jenis', ['file', 'link']);
            $table->string('lokasi_file', 255)->nullable();
            $table->string('url_eksternal', 500)->nullable();
            $table->enum('tingkat_akses', ['public', 'internal'])->default('public');
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('tanggal_publikasi')->useCurrent();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_diperbaharui')->nullable();
            
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_seminar');
    }
};
