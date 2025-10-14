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
            $table->integer('jarak')->default(0)->after('kontak');
            $table->integer('honor')->default(0)->after('jarak');
            $table->integer('fasilitas')->default(0)->after('honor');
            $table->integer('kesesuaian_jurusan')->default(0)->after('fasilitas');
            $table->integer('tingkat_kebersihan')->default(0)->after('kesesuaian_jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropColumn([
                'jarak',
                'honor',
                'fasilitas',
                'kesesuaian_jurusan',
                'tingkat_kebersihan',
            ]);
        });
    }
};