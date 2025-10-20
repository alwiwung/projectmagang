<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('peminjaman_warkah', function (Blueprint $table) {
        // Tambahkan hanya kolom yang belum ada
        if (!Schema::hasColumn('peminjaman_warkah', 'kondisi')) {
            $table->enum('kondisi', ['Baik', 'Rusak', 'Hilang'])->nullable()->after('tanggal_kembali');
        }

        if (!Schema::hasColumn('peminjaman_warkah', 'bukti')) {
            $table->string('bukti')->nullable()->after('kondisi');
        }

        if (!Schema::hasColumn('peminjaman_warkah', 'catatan')) {
            $table->text('catatan')->nullable()->after('bukti');
        }

        if (Schema::hasColumn('peminjaman_warkah', 'status')) {
            $table->enum('status', ['Dipinjam', 'Terlambat', 'Dikembalikan'])
                ->default('Dipinjam')
                ->change();
        }
    });
}

};
