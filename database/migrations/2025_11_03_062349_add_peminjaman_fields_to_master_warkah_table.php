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
    Schema::table('master_warkah', function (Blueprint $table) {
        $table->string('nama_peminjam')->nullable();
        $table->string('nomor_nota_dinas')->nullable();
        $table->string('file_nota_dinas')->nullable();
    });
}

public function down()
{
    Schema::table('master_warkah', function (Blueprint $table) {
        $table->dropColumn(['nama_peminjam', 'nomor_nota_dinas', 'file_nota_dinas']);
    });
}
};
