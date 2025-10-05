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
        Schema::create('profil_mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nim', 50)->nullable()->unique();
            $table->string('prodi', 100);
            $table->unsignedTinyInteger('semester')->default(5);
            $table->string('no_whatsapp', 30)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->boolean('cek_min_semester')->default(false);
            $table->boolean('cek_ipk_nilaisks')->default(false);
            $table->boolean('cek_valid_biodata')->default(false);
            $table->unsignedBigInteger('id_dospem')->nullable();
            
            $table->foreign('id_dospem')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_mahasiswa');
    }
};
