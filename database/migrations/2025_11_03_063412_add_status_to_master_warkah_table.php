<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('master_warkah', function (Blueprint $table) {
            if (!Schema::hasColumn('master_warkah', 'status')) {
                $table->string('status')->default('Tersedia')->after('file_nota_dinas');
            }
        });
    }

    public function down()
    {
        Schema::table('master_warkah', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
