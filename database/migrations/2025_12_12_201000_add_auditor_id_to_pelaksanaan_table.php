<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelaksanaan', function (Blueprint $table) {
            $table->foreignId('auditor_id')->nullable()->constrained('users', 'user_id');
        });
    }

    public function down(): void
    {
        Schema::table('pelaksanaan', function (Blueprint $table) {
            $table->dropForeign(['auditor_id']);
            $table->dropColumn('auditor_id');
        });
    }
};