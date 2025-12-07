<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_kinerja', function (Blueprint $table) {
            $table->id('indikator_id');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'kriteria_id');
            $table->string('kode_indikator', 20)->unique();
            $table->string('nama_indikator', 200);
            $table->string('metode_pengukuran', 100);
            $table->string('satuan', 50);
            $table->decimal('target', 10, 2);
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users', 'user_id');
            $table->dateTime('tanggal_dibuat')->useCurrent();
            $table->date('tanggal_disetujui')->nullable();
            $table->string('status', 20)->default('Draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_kinerja');
    }
};
