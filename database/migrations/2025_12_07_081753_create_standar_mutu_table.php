<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standar_mutu', function (Blueprint $table) {
            $table->id('standar_id');
            $table->string('kode_standar', 20)->unique();
            $table->string('nama_standar', 150);
            $table->text('deskripsi')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users', 'user_id');
            $table->dateTime('tanggal_dibuat')->useCurrent();
            $table->date('tanggal_disetujui')->nullable();
            $table->string('status', 20)->default('Draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standar_mutu');
    }
};
