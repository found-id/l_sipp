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
        Schema::create('hasil_penilaian', function (Blueprint $table) {
            $table->id('id_hasil_penilaian');
            $table->unsignedBigInteger('id_formulir_penilaian');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->decimal('total_persen', 7, 2);
            $table->string('nilai_huruf', 5)->nullable();
            $table->decimal('poin_ipk', 4, 2)->nullable();
            $table->timestamp('tanggal_diputuskan')->useCurrent();
            $table->unsignedBigInteger('diputuskan_oleh');
            
            $table->unique(['id_formulir_penilaian', 'id_mahasiswa'], 'uq_hasil');
            $table->foreign('id_formulir_penilaian')->references('id_formulir_penilaian')->on('formulir_penilaian')->onDelete('cascade');
            $table->foreign('id_mahasiswa')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diputuskan_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_penilaian');
    }
};
