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
        Schema::table('mitra', function (Blueprint $table) {
            // Ubah kolom jarak dari integer ke decimal untuk mendukung nilai desimal seperti 6.6
            $table->decimal('jarak', 8, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            // Kembalikan ke integer jika rollback
            $table->integer('jarak')->default(0)->change();
        });
    }
};
