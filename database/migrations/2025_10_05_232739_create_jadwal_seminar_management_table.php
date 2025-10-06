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
        Schema::create('jadwal_seminar_management', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('subjudul')->nullable();
            $table->enum('jenis', ['file', 'link']);
            $table->string('lokasi_file')->nullable();
            $table->string('url_eksternal')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('tanggal_publikasi')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamps();
            
            $table->foreign('dibuat_oleh')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_seminar_management');
    }
};
