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
        Schema::create('kanin_cape_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('stage_reached')->default(1); // 1..3
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('time_spent')->default(0); // в секундах
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_completed']);
            $table->index(['game_progress_id', 'stage_reached']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kanin_cape_results');
    }
};
