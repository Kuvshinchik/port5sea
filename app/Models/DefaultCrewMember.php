<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefaultCrewMember extends Model
{
    protected $fillable = [
        'code',
        'crew_role_id',
        'name',
        'skill_level',
        'trait',
        'trait_effect',
        'quote',
        'portrait_color',
        'salary',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function crewRole(): BelongsTo
    {
        return $this->belongsTo(CrewRole::class);
    }

    /**
     * Преобразовать в формат для игры
     */
    public function toCrewMemberData(): array
    {
        return [
            'id' => $this->code,
            'type' => 'default',
            'default_crew_code' => $this->code,
            'name' => $this->name,
            'role' => $this->crewRole->name,
            'role_code' => $this->crewRole->code,
            'roleIcon' => $this->crewRole->icon,
            'salary' => $this->salary,
            'skill' => $this->skill_level,
            'trait' => $this->trait,
            'traitEffect' => $this->trait_effect,
            'quote' => $this->quote,
            'portrait' => $this->portrait_color,
            'required' => $this->is_required,
            'isFromToy' => false,
        ];
    }
}
