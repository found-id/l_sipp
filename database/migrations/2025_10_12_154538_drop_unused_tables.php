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
        // Drop unused tables that have no traffic or are useless
        $tablesToDrop = [
            'cache',
            'cache_locks', 
            'failed_jobs',
            'grade_scale_steps',
            'jobs',
            'job_batches',
            'khs_analyses',
            'password_reset_tokens',
            'tpk_data',
            'transcripts'
        ];

        foreach ($tablesToDrop as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
                echo "Dropped table: {$table}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We cannot reverse this migration as we don't know the original structure
        // of the dropped tables. This is intentional as these tables are considered useless.
        echo "Cannot reverse this migration - tables were intentionally dropped as useless.\n";
    }
};