<?php

namespace App\Services\ShipGame;

use App\Models\GameProgress;
use App\Models\UserToy;
use App\Models\ToyCharacter;
use Illuminate\Support\Facades\DB;

/**
 * Базовый сервис для мини-игр на остановках
 */
class MiniGameService
{
    /**
     * Проверить, есть ли у игрока определённый бонусный персонаж
     */
    public function hasCharacterBonus(int $userId, string $characterNamePattern): bool
    {
        return UserToy::where('user_id', $userId)
            ->whereHas('toyCharacter', function ($query) use ($characterNamePattern) {
                $query->where('character_name', 'like', "%{$characterNamePattern}%");
            })
            ->exists();
    }

    /**
     * Проверить бонус по роли персонажа
     */
    public function hasRoleBonus(int $userId, string $roleCode): bool
    {
        return UserToy::where('user_id', $userId)
            ->whereHas('toyCharacter.crewRole', function ($query) use ($roleCode) {
                $query->where('code', $roleCode);
            })
            ->exists();
    }

    /**
     * Получить все активные бонусы персонажей для мини-игры
     */
    public function getActiveBonuses(int $userId, string $miniGameKey): array
    {
        $bonuses = [];

        $userToys = UserToy::where('user_id', $userId)
            ->with('toyCharacter')
            ->get();

        foreach ($userToys as $userToy) {
            $character = $userToy->toyCharacter;
            if (!$character || !$character->special_abilities) {
                continue;
            }

            $abilities = is_array($character->special_abilities) 
                ? $character->special_abilities 
                : json_decode($character->special_abilities, true);

            if (isset($abilities[$miniGameKey])) {
                $bonuses[] = [
                    'character_name' => $character->character_name,
                    'ability' => $abilities[$miniGameKey],
                ];
            }
        }

        return $bonuses;
    }

    /**
     * Начислить награды игроку
     */
    public function grantRewards(GameProgress $progress, array $rewards): array
    {
        $granted = [];

        DB::transaction(function () use ($progress, $rewards, &$granted) {
            if (isset($rewards['money']) && $rewards['money'] > 0) {
                $progress->earnMoney($rewards['money'], 'Награда за мини-игру');
                $granted['money'] = $rewards['money'];
            }

            if (isset($rewards['food_days']) && $rewards['food_days'] > 0) {
                $progress->addFoodDays($rewards['food_days']);
                $granted['food_days'] = $rewards['food_days'];
            }

            if (isset($rewards['fuel_percent']) && $rewards['fuel_percent'] > 0) {
                $progress->fuel_percent = min(100, $progress->fuel_percent + $rewards['fuel_percent']);
                $progress->save();
                $granted['fuel_percent'] = $rewards['fuel_percent'];
            }

            if (isset($rewards['morale']) && $rewards['morale'] !== 0) {
                $progress->morale = max(0, min(100, $progress->morale + $rewards['morale']));
                $progress->save();
                $granted['morale'] = $rewards['morale'];
            }
        });

        return $granted;
    }

    /**
     * Валидация результата мини-игры (базовая защита от читов)
     */
    public function validateResult(array $result, array $constraints): array
    {
        $errors = [];

        // Проверка времени игры
        if (isset($constraints['min_time']) && $result['time_played'] < $constraints['min_time']) {
            $errors[] = 'Слишком быстрое прохождение';
        }

        if (isset($constraints['max_time']) && $result['time_played'] > $constraints['max_time']) {
            $errors[] = 'Превышено максимальное время';
        }

        // Проверка очков
        if (isset($constraints['max_score']) && $result['score'] > $constraints['max_score']) {
            $errors[] = 'Недопустимое количество очков';
        }

        // Проверка скорости набора очков
        if (isset($constraints['max_score_per_second']) && $result['time_played'] > 0) {
            $scorePerSecond = $result['score'] / $result['time_played'];
            if ($scorePerSecond > $constraints['max_score_per_second']) {
                $errors[] = 'Подозрительная скорость набора очков';
            }
        }

        return $errors;
    }

    /**
     * Записать результат в лог
     */
    public function logResult(GameProgress $progress, string $miniGameName, array $result, bool $success): void
    {
        $progress->logEvent(
            $success ? 'mini_game_success' : 'mini_game_failed',
            ($success ? 'Успешно пройдена' : 'Не пройдена') . " мини-игра: {$miniGameName}",
            [
                'mini_game' => $miniGameName,
                'score' => $result['score'] ?? 0,
                'time_played' => $result['time_played'] ?? 0,
                'details' => $result,
            ]
        );
    }

    /**
     * Получить конфигурацию сложности на основе прогресса игрока
     */
    public function getDifficultyConfig(GameProgress $progress): array
    {
        // Базовая сложность увеличивается с каждой остановкой
        $stopIndex = $progress->current_point_index;
        
        return [
            'spawn_rate_multiplier' => 1 + ($stopIndex * 0.1),
            'enemy_speed_multiplier' => 1 + ($stopIndex * 0.05),
            'time_bonus' => max(0, 10 - $stopIndex), // Дополнительное время для начала
            'score_multiplier' => 1 + ($stopIndex * 0.15),
        ];
    }
}
