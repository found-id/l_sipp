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
        Schema::table('transcripts', function (Blueprint $table) {
            $table->string('nama_mahasiswa')->after('id');
            $table->string('nim')->unique()->after('nama_mahasiswa');
            $table->decimal('ipk', 3, 2)->nullable()->after('nim');
            $table->integer('total_sks_d')->default(0)->after('ipk');
            $table->boolean('has_e')->default(false)->after('total_sks_d');
            $table->boolean('eligible')->default(false)->after('has_e');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->dropColumn(['nama_mahasiswa', 'nim', 'ipk', 'total_sks_d', 'has_e', 'eligible']);
        });
    }
};
