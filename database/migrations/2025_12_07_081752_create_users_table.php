<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->text('password_hash');
            $table->foreignId('role_id')->constrained('role', 'role_id');
            $table->foreignId('unit_id')->constrained('unit', 'unit_id');
            $table->string('status', 20)->default('Aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
