<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameCrew extends Model
{
    protected $table = 'game_crew';

    protected $fillable = [
        'game_progress_id',
        'toy_character_id',
        'default_crew_code',
        'crew_role_id',
        'current_salary',
        'health',
        'morale',
        'is_from_toy',
    ];

    protected $casts = [
        'is_from_toy' => 'boolean',
    ];

    public function gameProgress(): BelongsTo
    {
        return $this->belongsTo(GameProgress::class);
    }

    public function toyCharacter(): BelongsTo
    {
        return $this->belongsTo(ToyCharacter::class);
    }

    public function crewRole(): BelongsTo
    {
        return $this->belongsTo(CrewRole::class);
    }

    /**
     * Получить данные для JS
     */
    public function toCrewData(): array
    {
        if ($this->is_from_toy && $this->toyCharacter) {
            $data = $this->toyCharacter->toCrewMemberData();
        } else {
            $defaultMember = DefaultCrewMember::where('code', $this->default_crew_code)->first();
            $data = $defaultMember ? $defaultMember->toCrewMemberData() : [];
        }

        $data['gameCrewId'] = $this->id;
        $data['health'] = $this->health;
        $data['morale'] = $this->morale;
        $data['currentSalary'] = $this->current_salary;
        $data['hired'] = true;

        return $data;
    }
}
