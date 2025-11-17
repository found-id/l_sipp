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
            $table->string('status_pkl')->default('siap')->after('gdrive_more');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('status_pkl');
        });
    }
};
