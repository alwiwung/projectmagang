<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_warkah', function (Blueprint $table) {
            $table->id();
            $table->string('no_warkah')->unique();
            $table->year('tahun');
            $table->string('no_sk');
            $table->string('nama');
            $table->string('lokasi');
            $table->string('kode_klasifikasi');
            $table->string('jenis_arsip_vital');
            $table->text('uraian_informasi_arsip');
            $table->integer('jumlah');
            $table->string('tingkat_perkembangan');
            $table->string('ruang_penyimpanan_rak');
            $table->string('no_boks_definitif');
            $table->string('no_folder');
            $table->text('keterangan')->nullable();
            $table->string('metode_perlindungan');
            $table->string('status')->default('Tersedia'); // Tersedia, Dipinjam
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->default(false); // Soft delete
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warkah');
    }
};
