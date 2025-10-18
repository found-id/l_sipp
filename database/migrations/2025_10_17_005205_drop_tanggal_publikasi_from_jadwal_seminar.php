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
        if (Schema::hasColumn('jadwal_seminar', 'tanggal_publikasi')) {
            Schema::table('jadwal_seminar', function (Blueprint $table) {
                $table->dropColumn('tanggal_publikasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('jadwal_seminar', 'tanggal_publikasi')) {
            Schema::table('jadwal_seminar', function (Blueprint $table) {
                $table->timestamp('tanggal_publikasi')->nullable();
            });
        }
    }
};
