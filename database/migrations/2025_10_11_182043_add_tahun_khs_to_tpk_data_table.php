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
        Schema::table('tpk_data', function (Blueprint $table) {
            $table->string('tahun_khs')->nullable()->after('ips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpk_data', function (Blueprint $table) {
            $table->dropColumn('tahun_khs');
        });
    }
};
