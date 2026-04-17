<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobOffer extends Model
{
    protected $fillable = [
        'code',
        'title',
        'icon',
        'description',
        'reward',
        'risk_level',
        'risk_value',
        'requirement',
        'cargo_weight',
        'duration',
        'destination_stop_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function destinationStop(): BelongsTo
    {
        return $this->belongsTo(RouteStop::class, 'destination_stop_id');
    }

    /**
     * Получить все подработки для игры
     */
    public static function getAllForGame(): array
    {
        return self::where('is_active', true)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->code,
                    'title' => $job->title,
                    'icon' => $job->icon,
                    'description' => $job->description,
                    'reward' => $job->reward,
                    'risk' => $job->risk_level === 'low' ? 'Низкий' : ($job->risk_level === 'medium' ? 'Средний' : 'Высокий'),
                    'riskLevel' => $job->risk_value,
                    'requirement' => $job->requirement,
                    'cargoWeight' => $job->cargo_weight,
                    'duration' => $job->duration,
                ];
            })
            ->toArray();
    }
}
