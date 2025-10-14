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
        Schema::create('khs_manual_transkrip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->integer('semester');
            $table->text('transcript_data'); // Data textfield
            $table->decimal('ips', 3, 2)->nullable(); // IPS yang diekstrak dari transcript_data
            $table->integer('total_sks')->nullable(); // Total SKS yang diekstrak dari transcript_data
            $table->integer('total_sks_d')->default(0); // SKS dengan nilai D
            $table->boolean('has_e')->default(false); // Ada nilai E atau tidak
            $table->boolean('eligible')->default(false); // Layak PKL atau tidak
            $table->timestamps();
            
            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['mahasiswa_id', 'semester']); // Satu mahasiswa hanya bisa punya satu transkrip per semester
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs_manual_transkrip');
    }
};