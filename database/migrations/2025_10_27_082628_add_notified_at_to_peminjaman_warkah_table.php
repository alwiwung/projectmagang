<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('peminjaman_warkah', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
    }
};
