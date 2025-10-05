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
        Schema::create('respon_penilaian', function (Blueprint $table) {
            $table->id('id_respon_penilaian');
            $table->unsignedBigInteger('id_formulir_penilaian');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_dosen');
            $table->boolean('status_final')->default(false);
            $table->timestamp('tanggal_diserahkan')->nullable();
            $table->timestamp('tanggal_dibuat')->useCurrent();
            
            $table->unique(['id_formulir_penilaian', 'id_mahasiswa', 'id_dosen'], 'uq_respon');
            $table->foreign('id_formulir_penilaian')->references('id_formulir_penilaian')->on('formulir_penilaian')->onDelete('cascade');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_penilaian');
    }
};
