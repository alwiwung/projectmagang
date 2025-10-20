<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_warkah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_warkah')->constrained('master_warkah')->onDelete('cascade');
            $table->string('nama_peminjam');
            $table->string('no_hp');
            $table->string('email');
            $table->date('tanggal_pinjam');
            $table->text('tujuan_pinjam');
            $table->date('batas_peminjaman');
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('status');
            $table->index('tanggal_pinjam');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_warkah');
    }
};
