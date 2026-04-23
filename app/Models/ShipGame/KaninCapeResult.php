<?php

namespace App\Models\ShipGame;

use App\Models\GameProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaninCapeResult extends Model
{
    protected $table = 'kanin_cape_results';

    protected $fillable = [
        'game_progress_id',
        'user_id',
        'stage_reached',
        'score',
        'time_spent',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
