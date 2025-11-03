<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('peminjaman_warkah', function (Blueprint $table) {
        // Pastikan hanya tambahkan kolom yang belum ada
        if (!Schema::hasColumn('peminjaman_warkah', 'nomor_nota_dinas')) {
            $table->string('nomor_nota_dinas')->nullable()->after('catatan');
        }
        if (!Schema::hasColumn('peminjaman_warkah', 'file_nota_dinas')) {
            $table->string('file_nota_dinas')->nullable()->after('nomor_nota_dinas');
        }
        if (!Schema::hasColumn('peminjaman_warkah', 'uraian')) {
            $table->text('uraian')->nullable()->after('file_nota_dinas');
        }
    });
}

    public function down()
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            $table->dropColumn(['nomor_nota_dinas', 'file_nota_dinas', 'uraian']);
        });
    }
};