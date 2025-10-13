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
            $table->text('transcript_data')->nullable()->after('semester');
            $table->decimal('ips', 3, 2)->nullable()->after('transcript_data');
            $table->integer('total_sks_d')->default(0)->after('ips');
            $table->boolean('has_e')->default(false)->after('total_sks_d');
            $table->boolean('eligible')->default(false)->after('has_e');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khs', function (Blueprint $table) {
            $table->dropColumn(['transcript_data', 'ips', 'total_sks_d', 'has_e', 'eligible']);
        });
    }
};