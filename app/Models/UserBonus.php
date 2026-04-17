<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBonus extends Model
{
    protected $fillable = [
        'user_id',
        'game_bonus_id',
        'is_active',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameBonus(): BelongsTo
    {
        return $this->belongsTo(GameBonus::class);
    }

    /**
     * Проверить, истёк ли бонус
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false; // Бессрочный
        }

        return $this->expires_at->isPast();
    }

    /**
     * Деактивировать истекшие бонусы
     */
    public static function deactivateExpired(): int
    {
        return self::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);
    }
}
