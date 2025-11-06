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
        // Update existing data: convert 0 to 1, and 1 to 3 (middle value)
        DB::table('mitra')->update([
            'honor' => DB::raw('CASE WHEN honor = 0 THEN 1 WHEN honor = 1 THEN 3 ELSE honor END'),
            'fasilitas' => DB::raw('CASE WHEN fasilitas = 0 THEN 1 WHEN fasilitas = 1 THEN 3 ELSE fasilitas END'),
            'kesesuaian_jurusan' => DB::raw('CASE WHEN kesesuaian_jurusan = 0 THEN 1 WHEN kesesuaian_jurusan = 1 THEN 3 ELSE kesesuaian_jurusan END'),
            'tingkat_kebersihan' => DB::raw('CASE WHEN tingkat_kebersihan = 0 THEN 1 WHEN tingkat_kebersihan = 1 THEN 3 ELSE tingkat_kebersihan END'),
        ]);

        // Modify columns to accept 1-5 range with default 1
        Schema::table('mitra', function (Blueprint $table) {
            $table->tinyInteger('honor')->default(1)->change();
            $table->tinyInteger('fasilitas')->default(1)->change();
            $table->tinyInteger('kesesuaian_jurusan')->default(1)->change();
            $table->tinyInteger('tingkat_kebersihan')->default(1)->change();
            // jarak tetap tidak berubah (sudah integer untuk km)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to boolean system: 1-2 = 0, 3-5 = 1
        DB::table('mitra')->update([
            'honor' => DB::raw('CASE WHEN honor <= 2 THEN 0 ELSE 1 END'),
            'fasilitas' => DB::raw('CASE WHEN fasilitas <= 2 THEN 0 ELSE 1 END'),
            'kesesuaian_jurusan' => DB::raw('CASE WHEN kesesuaian_jurusan <= 2 THEN 0 ELSE 1 END'),
            'tingkat_kebersihan' => DB::raw('CASE WHEN tingkat_kebersihan <= 2 THEN 0 ELSE 1 END'),
        ]);

        Schema::table('mitra', function (Blueprint $table) {
            $table->tinyInteger('honor')->default(0)->change();
            $table->tinyInteger('fasilitas')->default(0)->change();
            $table->tinyInteger('kesesuaian_jurusan')->default(0)->change();
            $table->tinyInteger('tingkat_kebersihan')->default(0)->change();
        });
    }
};
