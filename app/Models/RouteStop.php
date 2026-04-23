<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RouteStop extends Model
{
    protected $fillable = [
        'order_index',
        'name',
        'slug',
        'icon_slug',
        'x_coord',
        'y_coord',
        'is_stop',
        'level_number',
        'route_segment',
        'salary_days',
    ];

    protected $casts = [
        'is_stop' => 'boolean',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(StopTask::class);
    }

    /**
     * Получить все точки маршрута для JS
     */
    public static function getRoutePoints(string $segment = 'murmansk_anadyr'): array
    {
        return self::where('route_segment', $segment)
            ->orderBy('order_index')
            ->get()
            ->map(function ($stop) {
                return [
                    'id' => $stop->id,
                    'x' => $stop->x_coord,
                    'y' => $stop->y_coord,
                    'isStop' => $stop->is_stop,
                    'name' => $stop->name,
                    'slug' => $stop->slug,
                    'iconSlug' => $stop->icon_slug,
                    'level' => $stop->level_number,
                    'salaryDays' => $stop->salary_days,
                ];
            })
            ->toArray();
    }
}
