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
        Schema::create('assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('mahasiswa_user_id');
            $table->unsignedBigInteger('dosen_user_id');
            $table->boolean('is_final')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('form_id')->references('id')->on('assessment_forms')->onDelete('cascade');
            $table->foreign('mahasiswa_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dosen_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_responses');
    }
};
