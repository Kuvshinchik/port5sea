<?php

namespace App\Http\Controllers\ShipGame\MiniGames;

use App\Http\Controllers\Controller;
use App\Models\GameProgress;
use App\Models\ShipGame\TeriberkaMiniGameResult;
use App\Models\UserToy;
use App\Models\ToyCharacter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeriberkaMiniGameController extends Controller
{
    /**
     * Константы игры
     */
    const GAME_DURATION = 60; // секунд
    const TIDE_INTERVAL = 20; // секунд между волнами
    const MIN_MOLLUSKS_REQUIRED = 15; // минимум для прохождения
    
    // Очки за моллюсков по зонам
    const POINTS_FAR_ZONE = 30;      // Дальняя зона (3x)
    const POINTS_MIDDLE_ZONE = 20;   // Средняя зона (2x)
    const POINTS_NEAR_ZONE = 10;     // Ближняя зона (1x)
    
    // Награды
    const FOOD_DAYS_BONUS = 5;       // Дополнительные дни еды за успех
    const MONEY_BONUS_PER_POINT = 10; // Рублей за каждое очко
    
    /**
     * Страница мини-игры
     */
    public function index()
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        
        // Проверяем, что игрок на правильной остановке
        if ($progress->game_phase !== 'at_stop') {
            return redirect()->route('ship_game.index')
                ->with('error', 'Сначала доберитесь до Териберки!');
        }
        
        // Получаем данные о команде
        $crew = $progress->crew()->with(['toyCharacter.crewRole', 'crewRole'])->get();
        
        // Проверяем наличие Чайки Вперёдсмотрящей
        $hasSeagull = $this->checkSeagullBonus($user->id);
        
        // Проверяем, не пройдена ли уже эта мини-игра
        $existingResult = TeriberkaMiniGameResult::where('game_progress_id', $progress->id)
            ->where('is_completed', true)
            ->first();
        
        return view('ship_game.mini_games.teriberika', [
            'progress' => $progress,
            'gameState' => $progress->toGameState(),
            'crew' => $crew->map(fn($c) => $c->toCrewData()),
            'hasSeagull' => $hasSeagull,
            'alreadyCompleted' => $existingResult !== null,
            'previousScore' => $existingResult?->score ?? 0,
            'config' => [
                'gameDuration' => self::GAME_DURATION,
                'tideInterval' => self::TIDE_INTERVAL,
                'minMollusks' => self::MIN_MOLLUSKS_REQUIRED,
                'pointsFarZone' => self::POINTS_FAR_ZONE,
                'pointsMiddleZone' => self::POINTS_MIDDLE_ZONE,
                'pointsNearZone' => self::POINTS_NEAR_ZONE,
                'seagullSlowdown' => $hasSeagull ? 0.5 : 0, // 50% замедление прилива
            ],
        ]);
    }
    
    /**
     * Начать новую игровую сессию
     */
    public function startGame(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        
        // Создаём запись о начале игры
        $gameResult = TeriberkaMiniGameResult::create([
            'game_progress_id' => $progress->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'has_seagull_bonus' => $this->checkSeagullBonus($user->id),
        ]);
        
        $progress->logEvent('mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', [
            'mini_game_id' => $gameResult->id,
        ]);
        
        return response()->json([
            'success' => true,
            'session_id' => $gameResult->id,
            'message' => 'Игра началась!',
        ]);
    }
    
    /**
     * Сохранить результат игры
     */
    public function submitResult(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|integer',
            'score' => 'required|integer|min:0',
            'mollusks_collected' => 'required|integer|min:0',
            'far_zone_collected' => 'required|integer|min:0',
            'middle_zone_collected' => 'required|integer|min:0',
            'near_zone_collected' => 'required|integer|min:0',
            'crabs_clicked' => 'required|integer|min:0',
            'time_played' => 'required|integer|min:0|max:70', // небольшой запас
        ]);
        
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        
        $gameResult = TeriberkaMiniGameResult::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->where('is_completed', false)
            ->first();
        
        if (!$gameResult) {
            return response()->json([
                'success' => false,
                'message' => 'Игровая сессия не найдена или уже завершена',
            ], 400);
        }
        
        // Валидация времени игры (защита от читов)
        $playTime = now()->diffInSeconds($gameResult->started_at);
        if ($playTime < 10) { // слишком быстро
            Log::warning("Подозрительный результат мини-игры", [
                'user_id' => $user->id,
                'play_time' => $playTime,
                'claimed_time' => $request->time_played,
            ]);
        }
        
        // Валидация очков (базовая защита от читов)
        $maxPossibleScore = $this->calculateMaxPossibleScore($request->time_played, $gameResult->has_seagull_bonus);
        if ($request->score > $maxPossibleScore * 1.2) { // 20% запас на погрешности
            return response()->json([
                'success' => false,
                'message' => 'Недопустимый результат',
            ], 400);
        }
        
        // Определяем успешность
        $isSuccess = $request->mollusks_collected >= self::MIN_MOLLUSKS_REQUIRED;
        
        DB::transaction(function () use ($gameResult, $request, $progress, $isSuccess) {
            // Сохраняем результат
            $gameResult->update([
                'score' => $request->score,
                'mollusks_collected' => $request->mollusks_collected,
                'far_zone_collected' => $request->far_zone_collected,
                'middle_zone_collected' => $request->middle_zone_collected,
                'near_zone_collected' => $request->near_zone_collected,
                'crabs_clicked' => $request->crabs_clicked,
                'time_played' => $request->time_played,
                'is_completed' => true,
                'is_success' => $isSuccess,
                'completed_at' => now(),
            ]);
            
            // Начисляем награды
            if ($isSuccess) {
                // Бонус к еде
                $progress->addFoodDays(self::FOOD_DAYS_BONUS);
                
                // Денежный бонус
                $moneyBonus = $request->score * self::MONEY_BONUS_PER_POINT;
                $progress->earnMoney($moneyBonus, 'Награда за сбор моллюсков');
                
                $progress->logEvent('mini_game_complete', 'Мини-игра пройдена успешно!', [
                    'score' => $request->score,
                    'food_bonus' => self::FOOD_DAYS_BONUS,
                    'money_bonus' => $moneyBonus,
                ]);
            } else {
                $progress->logEvent('mini_game_failed', 'Мини-игра не пройдена', [
                    'score' => $request->score,
                    'mollusks' => $request->mollusks_collected,
                    'required' => self::MIN_MOLLUSKS_REQUIRED,
                ]);
            }
        });
        
        $rewards = [];
        if ($isSuccess) {
            $rewards = [
                'food_days' => self::FOOD_DAYS_BONUS,
                'money' => $request->score * self::MONEY_BONUS_PER_POINT,
            ];
        }
        
        return response()->json([
            'success' => true,
            'is_success' => $isSuccess,
            'score' => $request->score,
            'rewards' => $rewards,
            'message' => $isSuccess 
                ? "🎉 Отлично! Вы собрали {$request->mollusks_collected} моллюсков!" 
                : "😔 Недостаточно моллюсков. Нужно минимум " . self::MIN_MOLLUSKS_REQUIRED,
            'data' => $progress->fresh()->toGameState(),
        ]);
    }
    
    /**
     * Получить таблицу лидеров
     */
    public function leaderboard(): JsonResponse
    {
        $leaders = TeriberkaMiniGameResult::where('is_completed', true)
            ->where('is_success', true)
            ->orderByDesc('score')
            ->limit(10)
            ->with('user:id,name')
            ->get()
            ->map(function ($result, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $result->user->name ?? 'Аноним',
                    'score' => $result->score,
                    'mollusks' => $result->mollusks_collected,
                    'date' => $result->completed_at->format('d.m.Y'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'leaders' => $leaders,
        ]);
    }
    
    /**
     * Проверить наличие бонуса Чайки
     */
    private function checkSeagullBonus(int $userId): bool
    {
        // Ищем игрушку "Чайка Вперёдсмотрящая" у пользователя
        return UserToy::where('user_id', $userId)
            ->whereHas('toyCharacter', function ($query) {
                $query->where('character_name', 'like', '%Чайка%')
                    ->orWhereHas('crewRole', function ($q) {
                        $q->where('code', 'lookout'); // код роли вперёдсмотрящего
                    });
            })
            ->exists();
    }
    
    /**
     * Рассчитать максимально возможный счёт
     */
    private function calculateMaxPossibleScore(int $timePlayed, bool $hasSeagull): int
    {
        // Примерный расчёт: максимум 2 клика в секунду, все по максимальной цене
        $clicksPerSecond = 2;
        $maxClicks = $timePlayed * $clicksPerSecond;
        $avgPoints = (self::POINTS_FAR_ZONE + self::POINTS_MIDDLE_ZONE + self::POINTS_NEAR_ZONE) / 3;
        
        $maxScore = $maxClicks * $avgPoints;
        
        if ($hasSeagull) {
            $maxScore *= 1.3; // 30% бонус за чайку
        }
        
        return (int) $maxScore;
    }
}
