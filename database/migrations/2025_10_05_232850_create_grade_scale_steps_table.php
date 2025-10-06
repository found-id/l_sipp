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
        Schema::create('grade_scale_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scale_id')->default(1);
            $table->string('letter');
            $table->decimal('gpa_point', 3, 2);
            $table->decimal('min_score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_scale_steps');
    }
};
