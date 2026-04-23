<?php

namespace App\Http\Controllers\ShipGame\MiniGames\Stops\KaninCape;

use App\Http\Controllers\Controller;
use App\Models\GameProgress;
use App\Services\ShipGame\Stops\KaninCapeProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaninCapeController extends Controller
{
    public function __construct(private readonly KaninCapeProgressService $progressService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        $this->progressService->ensureCurrentStop($progress);

        return view('ship_game.stops.kanin_cape', [
            'progress' => $progress->fresh(),
            'hasMechanicRat' => $this->progressService->hasMechanicRatToy($user->id),
            'gameState' => $progress->fresh()->toGameState(),
        ]);
    }

    public function saveStageProgress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stage_reached' => 'required|integer|min:1|max:3',
            'score' => 'nullable|integer|min:0',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $this->progressService->ensureCurrentStop($progress);

        $result = $this->progressService->saveStageProgress(
            $progress,
            $validated['stage_reached'],
            $validated['score'] ?? null,
            $validated['time_spent'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Промежуточный прогресс сохранён',
            'data' => $result,
            'game_state' => $progress->fresh()->toGameState(),
        ]);
    }

    public function complete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stage_reached' => 'required|integer|min:1|max:3',
            'score' => 'nullable|integer|min:0',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $this->progressService->ensureCurrentStop($progress);

        $result = $this->progressService->complete(
            $progress,
            $validated['stage_reached'],
            $validated['score'] ?? null,
            $validated['time_spent'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Остановка Канин мыс завершена',
            'data' => $result,
            'game_state' => $progress->fresh()->toGameState(),
        ]);
    }
}
