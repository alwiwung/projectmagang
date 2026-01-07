<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            // ✅ Tambahkan ->nullable() langsung saat membuat kolom
            $table->string('nomor_nota_dinas')->nullable()->after('catatan');
            $table->string('file_nota_dinas')->nullable()->after('nomor_nota_dinas');
            $table->text('uraian')->nullable()->after('file_nota_dinas');
        });
    }

    public function down()
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            $table->dropColumn(['nomor_nota_dinas', 'file_nota_dinas', 'uraian']);
        });
    }
};