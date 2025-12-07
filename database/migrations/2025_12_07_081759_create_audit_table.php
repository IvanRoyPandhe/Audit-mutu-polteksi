<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit', function (Blueprint $table) {
            $table->id('audit_id');
            $table->foreignId('pelaksanaan_id')->constrained('pelaksanaan', 'pelaksanaan_id');
            $table->foreignId('auditor_id')->constrained('users', 'user_id');
            $table->date('tanggal_audit');
            $table->string('evaluasi_kesesuaian', 20);
            $table->text('rekomendasi_perbaikan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users', 'user_id');
            $table->dateTime('tanggal_dibuat')->useCurrent();
            $table->date('tanggal_disetujui')->nullable();
            $table->string('status', 20)->default('Draft');
            $table->text('catatan_penutupan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit');
    }
};
