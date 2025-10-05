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
        Schema::create('butir_pertanyaan_formulir', function (Blueprint $table) {
            $table->id('id_butir_pertanyaan');
            $table->unsignedBigInteger('id_formulir_penilaian');
            $table->string('label', 200);
            $table->enum('jenis', ['numeric', 'scale_letter', 'boolean', 'text']);
            $table->decimal('bobot', 6, 2)->default(0.00);
            $table->boolean('wajib')->default(true);
            $table->integer('urutan')->default(0);
            
            $table->foreign('id_formulir_penilaian')->references('id_formulir_penilaian')->on('formulir_penilaian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('butir_pertanyaan_formulir');
    }
};
