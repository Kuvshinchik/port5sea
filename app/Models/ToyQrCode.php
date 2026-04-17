<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ToyQrCode extends Model
{
    protected $table = 'toy_qr_codes';

    protected $fillable = [
        'code',
        'toy_id',
        'user_id',
        'activated_at',
        'is_used',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'activated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Генерация уникального QR-кода
     */
    public static function generateCode(): string
    {
        do {
            $code = 'TOY-' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Активировать QR-код для пользователя
     */
    public function activate(int $userId): bool
    {
        if ($this->is_used) {
            return false;
        }

        $this->user_id = $userId;
        $this->is_used = true;
        $this->activated_at = now();
        $this->save();

        // Создаём связь пользователя с игрушкой
        $toyCharacter = ToyCharacter::where('toy_id', $this->toy_id)->first();
        
        if ($toyCharacter) {
            // Проверяем, нет ли уже этой игрушки у пользователя
            $exists = UserToy::where('user_id', $userId)
                ->where('toy_character_id', $toyCharacter->id)
                ->exists();

            if (!$exists) {
                UserToy::create([
                    'user_id' => $userId,
                    'toy_character_id' => $toyCharacter->id,
                    'qr_code_id' => $this->id,
                    'bonus_money' => 10000, // Бонус за активацию
                ]);

                // Проверяем и активируем бонусы
                self::checkAndActivateBonuses($userId);

                return true;
            }
        }

        return false;
    }

    /**
     * Проверить и активировать бонусы на основе количества игрушек
     */
    public static function checkAndActivateBonuses(int $userId): void
    {
        $toyCount = UserToy::where('user_id', $userId)->count();

        // Получаем бонусы, которые должны быть активированы
        $bonuses = GameBonus::where('required_toys', '<=', $toyCount)
            ->where('is_active', true)
            ->get();

        foreach ($bonuses as $bonus) {
            // Проверяем, не активирован ли уже
            $exists = UserBonus::where('user_id', $userId)
                ->where('game_bonus_id', $bonus->id)
                ->exists();

            if (!$exists) {
                UserBonus::create([
                    'user_id' => $userId,
                    'game_bonus_id' => $bonus->id,
                    'is_active' => true,
                    'activated_at' => now(),
                    'expires_at' => $bonus->is_permanent ? null : now()->addDays(30),
                ]);

                // Если это денежный бонус, добавляем деньги в активную игру
                if ($bonus->type === 'money_bonus') {
                    $progress = GameProgress::where('user_id', $userId)
                        ->where('is_active', true)
                        ->first();

                    if ($progress) {
                        $progress->earnMoney($bonus->value, "Бонус: {$bonus->name}");
                    }
                }
            }
        }
    }
}
