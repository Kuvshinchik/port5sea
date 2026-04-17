<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameBonus extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'resource_type',
        'value',
        'required_toys',
        'conditions',
        'is_permanent',
        'is_active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_permanent' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function userBonuses(): HasMany
    {
        return $this->hasMany(UserBonus::class);
    }
}
