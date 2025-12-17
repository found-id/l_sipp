<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update nilai honor: 5 (Ada) -> 1, 1 (Tidak Ada) -> 0
        DB::statement('UPDATE mitra SET honor = CASE WHEN honor = 5 THEN 1 WHEN honor = 1 THEN 0 ELSE honor END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan nilai honor: 1 (Ada) -> 5, 0 (Tidak Ada) -> 1
        DB::statement('UPDATE mitra SET honor = CASE WHEN honor = 1 THEN 5 WHEN honor = 0 THEN 1 ELSE honor END');
    }
};