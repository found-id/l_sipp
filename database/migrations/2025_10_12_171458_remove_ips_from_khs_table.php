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
        Schema::table('khs', function (Blueprint $table) {
            // Hapus kolom yang terkait dengan analisis transkrip manual
            $table->dropColumn([
                'transcript_data',
                'ips', 
                'total_sks',
                'total_sks_d',
                'has_e',
                'eligible'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khs', function (Blueprint $table) {
            $table->text('transcript_data')->nullable()->after('status_validasi');
            $table->decimal('ips', 3, 2)->nullable()->after('transcript_data');
            $table->integer('total_sks')->default(0)->after('ips');
            $table->integer('total_sks_d')->default(0)->after('total_sks');
            $table->boolean('has_e')->default(false)->after('total_sks_d');
            $table->boolean('eligible')->default(false)->after('has_e');
        });
    }
};