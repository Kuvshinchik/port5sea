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
        Schema::create('teriberika_mini_game_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Результаты игры
            $table->integer('score')->default(0);
            $table->integer('mollusks_collected')->default(0);
            $table->integer('far_zone_collected')->default(0);
            $table->integer('middle_zone_collected')->default(0);
            $table->integer('near_zone_collected')->default(0);
            $table->integer('crabs_clicked')->default(0);
            $table->integer('time_played')->default(0); // в секундах
            
            // Бонусы и статус
            $table->boolean('has_seagull_bonus')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_success')->default(false);
            
            // Временные метки
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Индексы для быстрого поиска
            $table->index(['user_id', 'is_completed']);
            $table->index(['game_progress_id', 'is_success']);
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teriberika_mini_game_results');
    }
};
