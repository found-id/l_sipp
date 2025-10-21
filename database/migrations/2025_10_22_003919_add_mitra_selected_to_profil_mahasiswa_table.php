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
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('mitra_selected')->nullable()->after('id_dospem');
            $table->foreign('mitra_selected')->references('id')->on('mitra')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['mitra_selected']);
            $table->dropColumn('mitra_selected');
        });
    }
};
