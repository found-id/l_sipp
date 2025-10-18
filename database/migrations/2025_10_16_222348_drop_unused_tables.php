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
        // Drop foreign key constraints first, then drop tables
        Schema::table('detail_jawaban_penilaian', function (Blueprint $table) {
            $table->dropForeign(['id_item']);
            $table->dropForeign(['id_respon']);
        });
        
        Schema::table('hasil_penilaian', function (Blueprint $table) {
            $table->dropForeign(['id_formulir_penilaian']);
            $table->dropForeign(['id_mahasiswa']);
            $table->dropForeign(['diputuskan_oleh']);
        });
        
        Schema::table('respon_penilaian', function (Blueprint $table) {
            $table->dropForeign(['id_formulir_penilaian']);
            $table->dropForeign(['id_mahasiswa']);
            $table->dropForeign(['id_dosen']);
        });
        
        Schema::table('butir_pertanyaan_formulir', function (Blueprint $table) {
            $table->dropForeign(['id_formulir_penilaian']);
        });
        
        Schema::table('jadwal_seminar', function (Blueprint $table) {
            $table->dropForeign(['dibuat_oleh']);
        });
        
        // Now drop the unused tables
        Schema::dropIfExists('detail_jawaban_penilaian');
        Schema::dropIfExists('hasil_penilaian');
        Schema::dropIfExists('respon_penilaian');
        Schema::dropIfExists('butir_pertanyaan_formulir');
        Schema::dropIfExists('formulir_penilaian');
        Schema::dropIfExists('jadwal_seminar'); // Duplicate of jadwal_seminar_management
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration cannot be reversed as we don't have the original table structures
        // If you need to restore these tables, you would need to recreate them from the original SQL dump
    }
};
