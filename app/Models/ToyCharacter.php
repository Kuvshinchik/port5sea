<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToyCharacter extends Model
{
    protected $fillable = [
        'toy_id',
        'crew_role_id',
        'character_name',
        'skill_level',
        'trait',
        'trait_effect',
        'quote',
        'avatar_path',
        'portrait_color',
        'salary_modifier',
        'special_abilities',
        'is_active',
    ];

    protected $casts = [
        'special_abilities' => 'array',
        'is_active' => 'boolean',
    ];

    public function crewRole(): BelongsTo
    {
        return $this->belongsTo(CrewRole::class);
    }

    public function userToys(): HasMany
    {
        return $this->hasMany(UserToy::class);
    }

    /**
     * Получить данные игрушки из mezon_domik
     */
    public function getToyData(): ?array
    {
        $toy = \DB::table('mezon_domik')->where('id', $this->toy_id)->first();
        
        if (!$toy) {
            return null;
        }

        return [
            'id' => $toy->id,
            'name' => $toy->name,
            'text' => $toy->text,
            'articul' => $toy->articul,
            'price' => $toy->price,
        ];
    }

    /**
     * Получить URL аватара
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        // Fallback - изображение игрушки из магазина
        return asset("images/toys/{$this->toy_id}.png");
    }

    /**
     * Рассчитать зарплату
     */
    public function getSalary(): int
    {
        return $this->crewRole->base_salary + $this->salary_modifier;
    }

    /**
     * Преобразовать в формат для игры
     */
    public function toCrewMemberData(): array
    {
        return [
            'id' => 'toy_' . $this->id,
            'type' => 'toy',
            'toy_character_id' => $this->id,
            'name' => $this->character_name,
            'role' => $this->crewRole->name,
            'role_code' => $this->crewRole->code,
            'roleIcon' => $this->crewRole->icon,
            'salary' => $this->getSalary(),
            'skill' => $this->skill_level,
            'trait' => $this->trait,
            'traitEffect' => $this->trait_effect,
            'quote' => $this->quote,
            'portrait' => $this->portrait_color,
            'avatarUrl' => $this->getAvatarUrl(),
            'required' => $this->crewRole->is_required,
            'isFromToy' => true,
        ];
    }
}
