<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditor_unit_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditor_id')->constrained('users', 'user_id');
            $table->foreignId('unit_id')->constrained('unit', 'unit_id');
            $table->foreignId('assigned_by')->constrained('users', 'user_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->unique(['auditor_id', 'unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditor_unit_assignments');
    }
};