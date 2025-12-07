<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penetapan', function (Blueprint $table) {
            $table->id('penetapan_id');
            $table->foreignId('indikator_id')->constrained('indikator_kinerja', 'indikator_id');
            $table->integer('tahun');
            $table->text('target_capaian');
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users', 'user_id');
            $table->dateTime('tanggal_dibuat')->useCurrent();
            $table->date('tanggal_disetujui')->nullable();
            $table->string('status', 20)->default('Draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penetapan');
    }
};
