<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameJob extends Model
{
    protected $table = 'game_jobs';

    protected $fillable = [
        'game_progress_id',
        'job_offer_id',
        'status',
        'accepted_at',
        'completed_at',
        'actual_reward',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    /**
     * Получить данные для JS
     */
    public function toJobData(): array
    {
        $job = $this->jobOffer;

        return [
            'id' => $job->code,
            'gameJobId' => $this->id,
            'title' => $job->title,
            'icon' => $job->icon,
            'description' => $job->description,
            'reward' => $job->reward,
            'risk' => $job->risk_level === 'low' ? 'Низкий' : ($job->risk_level === 'medium' ? 'Средний' : 'Высокий'),
            'riskLevel' => $job->risk_value,
            'requirement' => $job->requirement,
            'cargoWeight' => $job->cargo_weight,
            'duration' => $job->duration,
            'status' => $this->status,
            'accepted' => true,
        ];
    }
}
