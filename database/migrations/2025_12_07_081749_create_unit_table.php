<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id('unit_id');
            $table->string('nama_unit', 100);
            $table->string('tipe_unit', 50);
            $table->string('pimpinan', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit');
    }
};
