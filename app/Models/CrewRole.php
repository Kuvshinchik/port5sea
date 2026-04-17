<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrewRole extends Model
{
    protected $fillable = [
        'code', 'name', 'icon', 'is_required', 'base_salary', 'max_count'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function toyCharacters(): HasMany
    {
        return $this->hasMany(ToyCharacter::class);
    }

    public function defaultCrewMembers(): HasMany
    {
        return $this->hasMany(DefaultCrewMember::class);
    }
}
