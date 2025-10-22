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
            $table->foreignId('warkah_id')->constrained('master_warkah')->onDelete('cascade');

            $table->string('pemohon')->nullable();
            $table->string('instansi')->nullable();
            $table->date('tanggal_permintaan')->nullable();
            $table->string('uraian_informasi_arsip')->nullable();
            $table->integer('jumlah_salinan')->default(1);
            $table->string('status')->default('baru'); // baru, diproses, selesai
            $table->json('tahapan')->nullable(); // tahapan proses (opsional)
            $table->string('barcode_path')->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // <— penting agar tidak error "deleted_at not found"
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
