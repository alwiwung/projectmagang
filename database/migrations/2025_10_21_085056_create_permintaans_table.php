<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id();

            // Relasi ke master_warkah
            $table->foreignId('id_warkah')->constrained('master_warkah')->onDelete('cascade');

            // Informasi Pemohon
            $table->string('nama_pemohon');
            $table->string('instansi')->nullable();
            $table->date('tanggal_permintaan')->nullable();
            $table->integer('jumlah_salinan')->default(1);
            $table->text('catatan_tambahan')->nullable();

            // Nota Dinas Permohonan
            $table->string('nota_dinas_masuk_no')->nullable();
            $table->string('nota_dinas_masuk_file')->nullable();

            // Surat Disposisi
            $table->string('nomor_surat_disposisi')->nullable();
            $table->string('file_disposisi')->nullable();

            // Status Tahapan
            $table->enum('status_permintaan', [
                'Diajukan', 'Diterima', 'Disposisi', 'Disalin', 'Selesai'
            ])->default('Diajukan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
