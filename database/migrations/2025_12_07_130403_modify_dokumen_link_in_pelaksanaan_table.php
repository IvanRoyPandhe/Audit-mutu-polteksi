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
        DB::statement('ALTER TABLE pelaksanaan ALTER COLUMN dokumen_link TYPE json USING dokumen_link::json');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaksanaan', function (Blueprint $table) {
            $table->text('dokumen_link')->nullable()->change();
        });
    }
};
