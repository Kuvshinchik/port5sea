<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameCompletedTask extends Model
{
    protected $fillable = [
        'game_progress_id',
        'stop_task_id',
        'score',
        'reward_received',
        'task_data',
        'completed_at',
    ];

    protected $casts = [
        'task_data' => 'array',
        'completed_at' => 'datetime',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    public function stopTask(): BelongsTo
    {
        return $this->belongsTo(StopTask::class);
    }
}
