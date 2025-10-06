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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('mahasiswa_user_id');
            $table->decimal('total_percent', 5, 2);
            $table->string('letter_grade')->nullable();
            $table->decimal('gpa_point', 3, 2)->nullable();
            $table->timestamp('decided_at');
            $table->unsignedBigInteger('decided_by');
            $table->timestamps();
            
            $table->foreign('form_id')->references('id')->on('assessment_forms')->onDelete('cascade');
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
    }
};
