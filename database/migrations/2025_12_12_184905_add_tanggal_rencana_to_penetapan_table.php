<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penetapan', function (Blueprint $table) {
            $table->date('tanggal_rencana_mulai')->nullable()->after('pic');
            $table->date('tanggal_rencana_selesai')->nullable()->after('tanggal_rencana_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penetapan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_rencana_mulai', 'tanggal_rencana_selesai']);
        });
    }
};
