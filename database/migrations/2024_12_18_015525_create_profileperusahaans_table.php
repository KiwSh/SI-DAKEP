<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('profil_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('bidang')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_perusahaans');
    }
};
