<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Шаг 1: Добавляем временный столбец
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('temp_status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending');
        });

        // Шаг 2: Копируем данные из старого столбца в новый
        DB::table('orders')->update(['temp_status' => DB::raw('status')]);

        // Шаг 3: Удаляем старый столбец
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Шаг 4: Переименовываем временный столбец в статус
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('temp_status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Шаг 1: Добавляем временный столбец
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('temp_status', ['pending', 'completed', 'canceled'])->default('pending');
        });

        // Шаг 2: Копируем данные из старого столбца в новый
        DB::table('orders')->update(['temp_status' => DB::raw('status')]);

        // Шаг 3: Удаляем старый столбец
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Шаг 4: Переименовываем временный столбец в статус
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('temp_status', 'status');
        });
    }
};
