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
        // Create simplified assessment_responses table
        Schema::create('assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_user_id');
            $table->unsignedBigInteger('dosen_user_id');
            $table->boolean('is_final')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('mahasiswa_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dosen_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create simplified assessment_response_items table
        Schema::create('assessment_response_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('value_numeric', 5, 2)->nullable();
            $table->boolean('value_bool')->nullable();
            $table->text('value_text')->nullable();
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('assessment_responses')->onDelete('cascade');
        });

        // Create simplified assessment_results table
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_user_id');
            $table->decimal('total_percent', 5, 2);
            $table->string('letter_grade')->nullable();
            $table->decimal('gpa_point', 3, 2)->nullable();
            $table->timestamp('decided_at')->useCurrent();
            $table->unsignedBigInteger('decided_by');
            $table->timestamps();
            
            $table->foreign('mahasiswa_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('decided_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessment_response_items');
        Schema::dropIfExists('assessment_responses');
    }
};
