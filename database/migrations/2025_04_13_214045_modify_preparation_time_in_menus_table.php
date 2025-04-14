<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Удаляем старый столбец
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('preparation_time');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->integer('preparation_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем новый столбец
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('preparation_time');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->datetime('preparation_time')->nullable();
        });
    }
};
