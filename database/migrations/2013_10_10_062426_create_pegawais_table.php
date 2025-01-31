<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nik')->unique(); // NIK harus unik
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            
            // Ubah menjadi foreign key biasa untuk menghindari masalah
            $table->unsignedBigInteger('jabatans_id');
            $table->foreign('jabatans_id')
                  ->references('id')
                  ->on('jabatans')
                  ->onDelete('cascade');
            
            $table->date('mulai_kerja');
            $table->integer('lama_kerja')->nullable(); // Lama kerja dalam hitungan tahun/bulan (bukan date)
            $table->date('selesai_kerja')->nullable();
            $table->string('foto')->nullable(); // Foto tidak wajib diisi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        // Hapus foreign key terlebih dahulu
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['tb_jabatan_id']);
        });
        
        Schema::dropIfExists('pegawais');
    }
};