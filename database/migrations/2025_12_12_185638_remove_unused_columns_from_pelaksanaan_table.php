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
        Schema::table('pelaksanaan', function (Blueprint $table) {
            $table->dropColumn(['dokumen_link', 'keterangan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaksanaan', function (Blueprint $table) {
            $table->json('dokumen_link')->nullable();
            $table->text('keterangan')->nullable();
        });
    }
};
