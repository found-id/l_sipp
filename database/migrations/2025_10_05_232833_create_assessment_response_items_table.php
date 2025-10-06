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
        Schema::create('assessment_response_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('value_numeric', 5, 2)->nullable();
            $table->boolean('value_bool')->nullable();
            $table->text('value_text')->nullable();
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('assessment_responses')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('assessment_form_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_response_items');
    }
};
