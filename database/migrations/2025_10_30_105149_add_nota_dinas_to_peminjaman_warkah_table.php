<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_warkah', 'nomor_nota_dinas')) {
                $table->string('nomor_nota_dinas')->after('batas_peminjaman');
            }
            if (!Schema::hasColumn('peminjaman_warkah', 'file_nota_dinas')) {
                $table->string('file_nota_dinas')->nullable()->after('nomor_nota_dinas');
            }
            if (!Schema::hasColumn('peminjaman_warkah', 'uraian_nota_dinas')) {
                $table->text('uraian_nota_dinas')->nullable()->after('file_nota_dinas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_warkah', 'nomor_nota_dinas')) {
                $table->dropColumn('nomor_nota_dinas');
            }
            if (Schema::hasColumn('peminjaman_warkah', 'file_nota_dinas')) {
                $table->dropColumn('file_nota_dinas');
            }
            if (Schema::hasColumn('peminjaman_warkah', 'uraian_nota_dinas')) {
                $table->dropColumn('uraian_nota_dinas');
            }
        });
    }
};