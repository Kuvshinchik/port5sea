<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StopTask extends Model
{
    protected $fillable = [
        'route_stop_id',
        'task_type',
        'title',
        'description',
        'order_in_stop',
        'reward_money',
        'reward_food_days',
        'reward_fuel_percent',
        'reward_items',
        'requirements',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'reward_items' => 'array',
        'requirements' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function routeStop(): BelongsTo
    {
        return $this->belongsTo(RouteStop::class);
    }
}
