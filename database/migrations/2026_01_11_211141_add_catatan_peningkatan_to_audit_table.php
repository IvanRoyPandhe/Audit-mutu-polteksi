<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->text('catatan_peningkatan')->nullable()->after('evaluasi_kesesuaian');
            $table->timestamp('tanggal_review_direktur')->nullable()->after('catatan_peningkatan');
            $table->integer('direview_oleh')->nullable()->after('tanggal_review_direktur');
        });
    }

    public function down(): void
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->dropColumn(['catatan_peningkatan', 'tanggal_review_direktur', 'direview_oleh']);
        });
    }
};
