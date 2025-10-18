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
        if (Schema::hasTable('jadwal_seminar_management') && !Schema::hasTable('jadwal_seminar')) {
            Schema::rename('jadwal_seminar_management', 'jadwal_seminar');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jadwal_seminar') && !Schema::hasTable('jadwal_seminar_management')) {
            Schema::rename('jadwal_seminar', 'jadwal_seminar_management');
        }
    }
};
