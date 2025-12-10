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
        // Use raw SQL to avoid doctrine/dbal dependency
        DB::statement('ALTER TABLE mitra MODIFY COLUMN jarak DECIMAL(8, 2) NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN honor TINYINT NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN fasilitas TINYINT NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN kesesuaian_jurusan TINYINT NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN tingkat_kebersihan TINYINT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL with default values
        DB::statement('UPDATE mitra SET jarak = 0 WHERE jarak IS NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN jarak DECIMAL(8, 2) NOT NULL DEFAULT 0');
        
        DB::statement('UPDATE mitra SET honor = 1 WHERE honor IS NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN honor TINYINT NOT NULL DEFAULT 1');
        
        DB::statement('UPDATE mitra SET fasilitas = 1 WHERE fasilitas IS NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN fasilitas TINYINT NOT NULL DEFAULT 1');
        
        DB::statement('UPDATE mitra SET kesesuaian_jurusan = 1 WHERE kesesuaian_jurusan IS NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN kesesuaian_jurusan TINYINT NOT NULL DEFAULT 1');
        
        DB::statement('UPDATE mitra SET tingkat_kebersihan = 1 WHERE tingkat_kebersihan IS NULL');
        DB::statement('ALTER TABLE mitra MODIFY COLUMN tingkat_kebersihan TINYINT NOT NULL DEFAULT 1');
    }
};
