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
    Schema::table('peminjaman_warkah', function (Blueprint $table) {
        $table->string('nomor_nota_dinas')->after('batas_peminjaman');
        $table->string('file_nota_dinas')->nullable()->after('nomor_nota_dinas');
        $table->text('uraian_nota_dinas')->nullable()->after('file_nota_dinas');
    });
}

public function down()
{
    Schema::table('peminjaman_warkah', function (Blueprint $table) {
        $table->dropColumn(['nomor_nota_dinas', 'file_nota_dinas', 'uraian_nota_dinas']);
    });
}
};
