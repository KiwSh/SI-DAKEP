<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_user'); // Kolom nama_user
            $table->string('username')->unique(); // Kolom username
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user'); // Kolom role dengan nilai admin/user
            
            // Tambahan opsional yang disarankan:
            $table->unsignedBigInteger('pegawai_id')->nullable(); // Relasi dengan tabel pegawai
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('pegawais')
                  ->onDelete('set null');
            
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif user
            $table->timestamp('last_login')->nullable(); // Catat terakhir login
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus foreign key terlebih dahulu
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
        });
        
        Schema::dropIfExists('users');
    }
};