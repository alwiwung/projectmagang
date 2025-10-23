<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_diterima',
                'tanggal_disposisi',
                'tanggal_penyalinan',
                'nota_dinas_balasan_no',
                'file_nota_dinas_balasan',
                'tanggal_selesai',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->date('tanggal_diterima')->nullable();
            $table->date('tanggal_disposisi')->nullable();
            $table->date('tanggal_penyalinan')->nullable();
            $table->string('nota_dinas_balasan_no')->nullable();
            $table->string('file_nota_dinas_balasan')->nullable();
            $table->date('tanggal_selesai')->nullable();
        });
    }
};
