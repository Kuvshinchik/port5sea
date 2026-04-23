<?php

namespace App\Services\ShipGame\Stops;

use App\Models\GameProgress;
use App\Models\RouteStop;
use App\Models\ShipGame\KaninCapeResult;
use App\Models\UserToy;

class KaninCapeProgressService
{
    public const STOP_SLUG = 'kanin-cape';

    public function ensureCurrentStop(GameProgress $progress): void
    {
        $stop = $this->getKaninCapeStop($progress->route_segment);

        if (!$stop) {
            return;
        }

        if ($progress->current_stop_id !== $stop->id) {
            $progress->current_stop_id = $stop->id;
            $progress->current_point_index = $stop->order_index;
            $progress->save();
        }
    }

    public function saveStageProgress(GameProgress $progress, int $stageReached, ?int $score = null, ?int $timeSpent = null): KaninCapeResult
    {
        $result = KaninCapeResult::firstOrNew([
            'game_progress_id' => $progress->id,
            'user_id' => $progress->user_id,
            'is_completed' => false,
        ]);

        $result->stage_reached = max($result->stage_reached ?? 1, $stageReached);

        if ($score !== null) {
            $result->score = max($result->score ?? 0, $score);
        }

        if ($timeSpent !== null) {
            $result->time_spent = max($result->time_spent ?? 0, $timeSpent);
        }

        $result->save();

        return $result;
    }

    public function complete(GameProgress $progress, int $stageReached, ?int $score = null, ?int $timeSpent = null): KaninCapeResult
    {
        $result = $this->saveStageProgress($progress, $stageReached, $score, $timeSpent);
        $result->is_completed = true;
        $result->completed_at = now();
        $result->save();

        return $result;
    }

    public function hasMechanicRatToy(int $userId): bool
    {
        return UserToy::where('user_id', $userId)
            ->whereHas('toyCharacter', function ($query) {
                $query->where('character_name', 'like', '%Крыс%')
                    ->whereHas('crewRole', function ($roleQuery) {
                        $roleQuery->where('code', 'mechanic');
                    });
            })
            ->exists();
    }

    private function getKaninCapeStop(string $routeSegment): ?RouteStop
    {
        return RouteStop::where('route_segment', $routeSegment)
            ->where('slug', self::STOP_SLUG)
            ->first();
    }
}
