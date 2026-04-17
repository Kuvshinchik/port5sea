<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameProgress extends Model
{
    use HasFactory;

    protected $table = 'game_progress';

    protected $fillable = [
        'user_id',
        'route_segment',
        'current_stop_id',
        'current_point_index',
        'game_phase',
        'money',
        'food_days',
        'fuel_percent',
        'cargo_used',
        'cargo_capacity',
        'morale',
        'total_earned',
        'total_spent',
        'days_traveled',
        'is_active',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ═══════════════════════════════════════════════════════════════════
    // ОТНОШЕНИЯ
    // ═══════════════════════════════════════════════════════════════════

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentStop(): BelongsTo
    {
        return $this->belongsTo(RouteStop::class, 'current_stop_id');
    }

    public function crew(): HasMany
    {
        return $this->hasMany(GameCrew::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(GameEquipment::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(GameJob::class);
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(GameCompletedTask::class);
    }

    public function eventLogs(): HasMany
    {
        return $this->hasMany(GameEventLog::class);
    }

    // ═══════════════════════════════════════════════════════════════════
    // СКОУПЫ
    // ═══════════════════════════════════════════════════════════════════

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ═══════════════════════════════════════════════════════════════════
    // ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Получить активную игру пользователя или создать новую
     */
    public static function getOrCreateForUser(int $userId, string $routeSegment = 'murmansk_anadyr'): self
    {
        $progress = self::where('user_id', $userId)
            ->where('route_segment', $routeSegment)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            $firstStop = RouteStop::where('route_segment', $routeSegment)
                ->orderBy('order_index')
                ->first();

            $progress = self::create([
                'user_id' => $userId,
                'route_segment' => $routeSegment,
                'current_stop_id' => $firstStop?->id,
                'current_point_index' => 0,
                'game_phase' => 'preparation',
                'money' => 250000,
                'started_at' => now(),
            ]);
        }

        return $progress;
    }

    /**
     * Проверить, достаточно ли денег
     */
    public function canAfford(int $amount): bool
    {
        return $this->money >= $amount;
    }

    /**
     * Списать деньги
     */
    public function spendMoney(int $amount, string $description = ''): bool
    {
        if (!$this->canAfford($amount)) {
            return false;
        }

        $this->money -= $amount;
        $this->total_spent += $amount;
        $this->save();

        if ($description) {
            $this->logEvent('spend', $description, ['amount' => $amount], -$amount);
        }

        return true;
    }

    /**
     * Добавить деньги
     */
    public function earnMoney(int $amount, string $description = ''): void
    {
        $this->money += $amount;
        $this->total_earned += $amount;
        $this->save();

        if ($description) {
            $this->logEvent('earn', $description, ['amount' => $amount], $amount);
        }
    }

    /**
     * Проверить, достаточно ли места в трюме
     */
    public function hasCargoSpace(int $weight): bool
    {
        return ($this->cargo_used + $weight) <= $this->cargo_capacity;
    }

    /**
     * Добавить груз
     */
    public function addCargo(int $weight): bool
    {
        if (!$this->hasCargoSpace($weight)) {
            return false;
        }

        $this->cargo_used += $weight;
        $this->save();

        return true;
    }

    /**
     * Убрать груз
     */
    public function removeCargo(int $weight): void
    {
        $this->cargo_used = max(0, $this->cargo_used - $weight);
        $this->save();
    }

    /**
     * Добавить дни еды
     */
    public function addFoodDays(int $days): void
    {
        $this->food_days += $days;
        $this->save();
    }

    /**
     * Списать зарплату команды
     */
    public function paySalaries(): int
    {
        $totalSalary = $this->crew->sum('current_salary');
        
        if ($totalSalary > 0) {
            $this->spendMoney($totalSalary, 'Выплата зарплаты экипажу');
        }

        return $totalSalary;
    }

    /**
     * Получить количество нанятых членов команды
     */
    public function getCrewCount(): int
    {
        return $this->crew()->count();
    }

    /**
     * Проверить, готов ли корабль к отплытию
     */
    public function isReadyToDepart(): array
    {
        $problems = [];

        // Проверяем обязательные роли
        $requiredRoles = CrewRole::where('is_required', true)->get();
        foreach ($requiredRoles as $role) {
            $hasRole = $this->crew()
                ->where('crew_role_id', $role->id)
                ->exists();
            
            if (!$hasRole) {
                $problems[] = "Нужен {$role->name}";
            }
        }

        // Минимум 6 человек в команде
        if ($this->getCrewCount() < 6) {
            $problems[] = 'Мало людей в команде (минимум 6)';
        }

        // Минимум 10 дней еды
        if ($this->food_days < 10) {
            $problems[] = 'Мало продовольствия (минимум на 10 дней)';
        }

        return $problems;
    }

    /**
     * Записать событие в лог
     */
    public function logEvent(string $type, string $description, array $data = [], int $moneyChange = 0): void
    {
        GameEventLog::create([
            'game_progress_id' => $this->id,
            'event_type' => $type,
            'description' => $description,
            'event_data' => $data,
            'money_change' => $moneyChange,
        ]);
    }

    /**
     * Перейти к следующей точке маршрута
     */
    public function moveToNextPoint(): ?RouteStop
    {
        $nextIndex = $this->current_point_index + 1;
        
        $nextStop = RouteStop::where('route_segment', $this->route_segment)
            ->where('order_index', $nextIndex)
            ->first();

        if ($nextStop) {
            $this->current_point_index = $nextIndex;
            $this->current_stop_id = $nextStop->id;
            
            if ($nextStop->is_stop) {
                $this->game_phase = 'at_stop';
                
                // Списываем зарплату
                if ($nextStop->salary_days > 0) {
                    $this->days_traveled += $nextStop->salary_days;
                    for ($i = 0; $i < $nextStop->salary_days; $i++) {
                        $this->paySalaries();
                    }
                }
            } else {
                $this->game_phase = 'traveling';
            }
            
            $this->save();
        } else {
            // Маршрут завершён
            $this->game_phase = 'completed';
            $this->completed_at = now();
            $this->save();
        }

        return $nextStop;
    }

    /**
     * Получить данные для API (полное состояние игры)
     */
    public function toGameState(): array
    {
        $user = $this->user;
        $userToys = UserToy::where('user_id', $user->id)->with('toyCharacter.crewRole')->get();
        $userBonuses = UserBonus::where('user_id', $user->id)->where('is_active', true)->with('gameBonus')->get();

        return [
            'progress' => [
                'id' => $this->id,
                'money' => $this->money,
                'food_days' => $this->food_days,
                'fuel_percent' => $this->fuel_percent,
                'cargo_used' => $this->cargo_used,
                'cargo_capacity' => $this->cargo_capacity,
                'morale' => $this->morale,
                'current_point_index' => $this->current_point_index,
                'game_phase' => $this->game_phase,
                'days_traveled' => $this->days_traveled,
            ],
            'current_stop' => $this->currentStop ? [
                'id' => $this->currentStop->id,
                'name' => $this->currentStop->name,
                'level_number' => $this->currentStop->level_number,
                'x' => $this->currentStop->x_coord,
                'y' => $this->currentStop->y_coord,
            ] : null,
            'crew' => $this->crew->map(function ($member) {
                return $member->toCrewData();
            }),
            'equipment' => $this->equipment->map(function ($eq) {
                return $eq->toEquipmentData();
            }),
            'jobs' => $this->jobs->where('status', 'accepted')->map(function ($job) {
                return $job->toJobData();
            }),
            'user_toys' => $userToys->map(function ($ut) {
                return [
                    'id' => $ut->toy_character_id,
                    'character_name' => $ut->toyCharacter->character_name,
                    'role' => $ut->toyCharacter->crewRole->name,
                    'role_code' => $ut->toyCharacter->crewRole->code,
                ];
            }),
            'active_bonuses' => $userBonuses->map(function ($ub) {
                return [
                    'code' => $ub->gameBonus->code,
                    'type' => $ub->gameBonus->type,
                    'resource_type' => $ub->gameBonus->resource_type,
                    'is_permanent' => $ub->gameBonus->is_permanent,
                ];
            }),
            'has_infinite_fuel' => $userBonuses->contains(fn($ub) => $ub->gameBonus->code === 'infinite_fuel'),
            'has_infinite_food' => $userBonuses->contains(fn($ub) => $ub->gameBonus->code === 'infinite_food'),
        ];
    }
}
