<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kebijakan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('link');
            $table->enum('tipe', ['kebijakan', 'manual', 'formulir']);
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kebijakan');
    }
};