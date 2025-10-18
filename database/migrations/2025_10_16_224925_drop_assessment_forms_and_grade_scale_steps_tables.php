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
        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop the tables
        Schema::dropIfExists('assessment_response_items');
        Schema::dropIfExists('assessment_responses');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessment_form_items');
        Schema::dropIfExists('assessment_forms');
        Schema::dropIfExists('grade_scale_steps');
        
        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration cannot be reversed as we don't have the original table structures
        // If you need to restore these tables, you would need to recreate them from the original SQL dump
    }
};
