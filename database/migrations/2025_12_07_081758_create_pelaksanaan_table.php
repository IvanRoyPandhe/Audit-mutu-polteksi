<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaksanaan', function (Blueprint $table) {
            $table->id('pelaksanaan_id');
            $table->foreignId('penetapan_id')->constrained('penetapan', 'penetapan_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('dokumen_link')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status', 30)->default('Belum Dimulai');
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->dateTime('tanggal_dibuat')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaksanaan');
    }
};
