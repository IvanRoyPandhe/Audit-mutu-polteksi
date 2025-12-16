<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->integer('mayor')->default(0);
            $table->integer('minor')->default(0);
            $table->integer('observasi')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->dropColumn(['mayor', 'minor', 'observasi']);
        });
    }
};