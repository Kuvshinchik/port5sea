<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameEventLog extends Model
{
    protected $fillable = [
        'game_progress_id',
        'event_type',
        'description',
        'event_data',
        'money_change',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }
}
