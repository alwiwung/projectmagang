<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('master_warkah', function (Blueprint $table) {
            $table->id();

            // 🗂️ Kolom utama sesuai struktur Excel
            $table->string('kurun_waktu_berkas')->nullable(); // Tahun / periode
            $table->string('lokasi')->nullable();
            $table->string('kode_klasifikasi')->nullable();
            $table->string('jenis_arsip_vital')->nullable();

            // Tambahan agar sesuai Excel & import
            $table->string('nomor_item_arsip')->nullable(); // Nomor arsip unik
            $table->string('media')->nullable(); // Media simpan (kertas, digital, dsb)
            $table->string('jangka_simpan_aktif')->nullable();
            $table->string('jangka_simpan_inaktif')->nullable();

            $table->text('uraian_informasi_arsip')->nullable();
            $table->string('jumlah')->nullable(); // Bisa diubah ke integer jika hanya angka
            $table->string('tingkat_perkembangan')->nullable();
            $table->string('ruang_penyimpanan_rak')->nullable();
            $table->string('no_boks_definitif')->nullable();
            $table->string('no_folder')->nullable();
            $table->string('metode_perlindungan')->nullable();
            $table->text('keterangan')->nullable();

            // Status arsip
            $table->string('status')->default('Tersedia'); // Tersedia / Dipinjam

            // Kolom soft delete manual (jika digunakan di model)
            $table->boolean('is_deleted')->default(false);

            // 🔍 Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            // 🕒 Timestamps & Soft Delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_warkah');
    }
};
