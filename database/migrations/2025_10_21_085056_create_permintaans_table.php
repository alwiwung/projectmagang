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
    Schema::create('permintaans', function (Blueprint $table) {
        $table->id();
        $table->string('pemohon')->nullable();
        $table->string('instansi')->nullable();
        $table->date('tanggal_permintaan')->nullable();
        $table->string('kode_warkah')->nullable();
        $table->integer('jumlah_salinan')->default(1);
        $table->string('status')->default('baru'); // baru, diproses, selesai
        $table->json('tahapan')->nullable(); // optional: menyimpan tahapan proses (json)
        $table->string('barcode_path')->nullable();
        $table->text('catatan')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
