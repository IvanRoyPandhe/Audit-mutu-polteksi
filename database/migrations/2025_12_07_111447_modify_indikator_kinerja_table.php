<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_kinerja', function (Blueprint $table) {
            $table->dropColumn(['metode_pengukuran', 'satuan']);
            $table->text('target')->change();
        });
    }

    public function down(): void
    {
        Schema::table('indikator_kinerja', function (Blueprint $table) {
            $table->string('metode_pengukuran', 100);
            $table->string('satuan', 50);
            $table->decimal('target', 10, 2)->change();
        });
    }
};
