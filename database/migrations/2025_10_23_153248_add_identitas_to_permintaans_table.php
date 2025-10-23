<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->string('nomor_identitas')->nullable()->after('instansi');
            $table->text('alamat_lengkap')->nullable()->after('nomor_identitas');
            $table->string('nomor_telepon')->nullable()->after('alamat_lengkap');
            $table->string('email')->nullable()->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_identitas',
                'alamat_lengkap',
                'nomor_telepon',
                'email',
            ]);
        });
    }
};
