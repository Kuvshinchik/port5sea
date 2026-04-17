<?php

namespace App\Models\ShipGame;

use App\Models\GameProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeriberkaMiniGameResult extends Model
{
    protected $table = 'teriberika_mini_game_results';

    protected $fillable = [
        'game_progress_id',
        'user_id',
        'score',
        'mollusks_collected',
        'far_zone_collected',
        'middle_zone_collected',
        'near_zone_collected',
        'crabs_clicked',
        'time_played',
        'has_seagull_bonus',
        'is_completed',
        'is_success',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'has_seagull_bonus' => 'boolean',
        'is_completed' => 'boolean',
        'is_success' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Связь с прогрессом игры
     */
    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить лучший результат пользователя
     */
    public static function getBestScore(int $userId): ?int
    {
        return self::where('user_id', $userId)
            ->where('is_completed', true)
            ->max('score');
    }

    /**
     * Проверить, проходил ли пользователь эту мини-игру в текущей сессии
     */
    public static function hasCompletedInSession(int $progressId): bool
    {
        return self::where('game_progress_id', $progressId)
            ->where('is_completed', true)
            ->where('is_success', true)
            ->exists();
    }

    /**
     * Получить статистику пользователя
     */
    public static function getUserStats(int $userId): array
    {
        $results = self::where('user_id', $userId)
            ->where('is_completed', true)
            ->get();

        return [
            'total_games' => $results->count(),
            'successful_games' => $results->where('is_success', true)->count(),
            'best_score' => $results->max('score') ?? 0,
            'total_mollusks' => $results->sum('mollusks_collected'),
            'avg_score' => $results->avg('score') ?? 0,
        ];
    }
}
