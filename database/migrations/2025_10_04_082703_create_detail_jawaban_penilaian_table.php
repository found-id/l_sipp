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
        Schema::create('detail_jawaban_penilaian', function (Blueprint $table) {
            $table->id('id_detail_jawaban');
            $table->unsignedBigInteger('id_respon');
            $table->unsignedBigInteger('id_item');
            $table->decimal('nilai_angka', 7, 2)->nullable();
            $table->string('nilai_huruf', 5)->nullable();
            $table->boolean('kehadiran')->nullable();
            $table->string('jawaban', 500)->nullable();
            
            $table->unique(['id_respon', 'id_item'], 'uq_detail');
            $table->foreign('id_respon')->references('id_respon_penilaian')->on('respon_penilaian')->onDelete('cascade');
            $table->foreign('id_item')->references('id_butir_pertanyaan')->on('butir_pertanyaan_formulir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jawaban_penilaian');
    }
};
