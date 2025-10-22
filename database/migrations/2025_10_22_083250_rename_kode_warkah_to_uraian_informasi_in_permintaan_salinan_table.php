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
    Schema::table('permintaans', function (Blueprint $table) {
        $table->renameColumn('kode_warkah', 'uraian_informasi');
        
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            //
        });
    }
};
