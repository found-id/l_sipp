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
        Schema::create('tpk_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('khs_id')->nullable()->constrained()->onDelete('set null');
            
            // Basic information extracted from KHS
            $table->integer('semester')->nullable();
            $table->string('nim')->nullable();
            $table->string('nama')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('dosen_pembimbing')->nullable();
            $table->string('tanggal')->nullable();
            $table->decimal('ips', 3, 2)->nullable();
            
            // Mata kuliah data (JSON)
            $table->json('mata_kuliah_data')->nullable();
            
            // Validation status (JSON)
            $table->json('validation_status')->nullable();
            $table->boolean('is_eligible')->default(false);
            
            // Extraction metadata
            $table->enum('extraction_status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('extraction_errors')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'semester']);
            $table->index('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpk_data');
    }
};
