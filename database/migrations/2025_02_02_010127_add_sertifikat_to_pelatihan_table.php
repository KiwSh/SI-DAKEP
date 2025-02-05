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
    Schema::table('pelatihan', function (Blueprint $table) {
        $table->string('sertifikat')->nullable()->after('nomor_surat');
    });
}

public function down()
{
    Schema::table('pelatihan', function (Blueprint $table) {
        $table->dropColumn('sertifikat');
    });
}

};
