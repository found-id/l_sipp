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
        if (Schema::hasColumn('assessment_results', 'decided_at')) {
            Schema::table('assessment_results', function (Blueprint $table) {
                $table->dropColumn('decided_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('assessment_results', 'decided_at')) {
            Schema::table('assessment_results', function (Blueprint $table) {
                $table->timestamp('decided_at')->nullable();
            });
        }
    }
};
