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
            $table->string('gdrive_pkkmb')->nullable();
            $table->string('gdrive_ecourse')->nullable();
            $table->string('gdrive_more')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['gdrive_pkkmb', 'gdrive_ecourse', 'gdrive_more']);
        });
    }
};
