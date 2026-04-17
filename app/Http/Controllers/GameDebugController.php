<?php

namespace App\Http\Controllers;

use App\Models\GameCrew;
use App\Models\GameEquipment;
use App\Models\GameJob;
use App\Models\GameProgress;
use App\Models\GameEventLog;
use App\Models\GameCompletedTask;
use App\Models\RouteStop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameDebugController extends Controller
{
    /**
     * Страница панели отладки
     */
    public function index()
    {
        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        // Статистика для отображения
        $stats = null;
        if ($progress) {
            $stats = [
                'money' => $progress->money,
                'food_days' => $progress->food_days,
                'fuel_percent' => $progress->fuel_percent,
                'cargo_used' => $progress->cargo_used,
                'morale' => $progress->morale,
                'crew_count' => $progress->crew()->count(),
                'equipment_count' => $progress->equipment()->count(),
                'jobs_count' => $progress->jobs()->where('status', 'accepted')->count(),
                'current_stop' => $progress->currentStop?->name ?? 'Не определено',
                'game_phase' => $progress->game_phase,
                'days_traveled' => $progress->days_traveled,
                'total_earned' => $progress->total_earned,
                'total_spent' => $progress->total_spent,
            ];
        }

        // Все остановки маршрута для выбора
        $routeStops = RouteStop::where('is_stop', true)
            ->orderBy('order_index')
            ->get();

        return view('ship_game.debug', [
            'progress' => $progress,
            'stats' => $stats,
            'routeStops' => $routeStops,
        ]);
    }

    /**
     * Полный сброс игры - удаляет прогресс и создаёт новый
     */
    public function fullReset(): JsonResponse
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {
            // Находим все активные игры пользователя
            $progresses = GameProgress::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($progresses as $progress) {
                // Удаляем связанные данные
                GameCrew::where('game_progress_id', $progress->id)->delete();
                GameEquipment::where('game_progress_id', $progress->id)->delete();
                GameJob::where('game_progress_id', $progress->id)->delete();
                GameCompletedTask::where('game_progress_id', $progress->id)->delete();
                GameEventLog::where('game_progress_id', $progress->id)->delete();
                
                // Удаляем сам прогресс
                $progress->delete();
            }
        });

        // Создаём новую игру
        $newProgress = GameProgress::getOrCreateForUser($user->id);

        return response()->json([
            'success' => true,
            'message' => '🔄 Игра полностью сброшена! Создан новый прогресс.',
            'new_progress_id' => $newProgress->id,
        ]);
    }

    /**
     * Сброс только команды (увольняет всех)
     */
    public function resetCrew(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $count = GameCrew::where('game_progress_id', $progress->id)->count();
        GameCrew::where('game_progress_id', $progress->id)->delete();

        $progress->logEvent('debug_reset', "Отладка: уволена вся команда ({$count} чел.)");

        return response()->json([
            'success' => true,
            'message' => "👥 Команда сброшена! Уволено: {$count} человек.",
        ]);
    }

    /**
     * Сброс только снаряжения и еды
     */
    public function resetEquipment(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $count = GameEquipment::where('game_progress_id', $progress->id)->count();
        GameEquipment::where('game_progress_id', $progress->id)->delete();

        // Сбрасываем связанные показатели
        $progress->food_days = 0;
        $progress->cargo_used = 0;
        $progress->save();

        $progress->logEvent('debug_reset', "Отладка: сброшено снаряжение ({$count} предметов)");

        return response()->json([
            'success' => true,
            'message' => "🧰 Снаряжение сброшено! Удалено: {$count} предметов. Еда и груз обнулены.",
        ]);
    }

    /**
     * Сброс подработок
     */
    public function resetJobs(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        // Считаем вес груза от подработок
        $jobs = GameJob::where('game_progress_id', $progress->id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->with('jobOffer')
            ->get();

        $cargoWeight = $jobs->sum(fn($j) => $j->jobOffer->cargo_weight ?? 0);
        $count = $jobs->count();

        GameJob::where('game_progress_id', $progress->id)->delete();

        // Освобождаем груз
        $progress->cargo_used = max(0, $progress->cargo_used - $cargoWeight);
        $progress->save();

        $progress->logEvent('debug_reset', "Отладка: отменены подработки ({$count} шт.)");

        return response()->json([
            'success' => true,
            'message' => "💼 Подработки сброшены! Отменено: {$count}. Освобождено груза: {$cargoWeight} кг.",
        ]);
    }

    /**
     * Установить количество денег
     */
    public function setMoney(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|integer|min:0|max:99999999',
        ]);

        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $oldMoney = $progress->money;
        $progress->money = $request->input('amount');
        $progress->save();

        $progress->logEvent('debug_set', "Отладка: деньги изменены {$oldMoney} → {$progress->money}");

        return response()->json([
            'success' => true,
            'message' => "💰 Деньги установлены: " . number_format($progress->money, 0, ',', ' ') . " ₽",
        ]);
    }

    /**
     * Установить количество дней еды
     */
    public function setFood(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'required|integer|min:0|max:365',
        ]);

        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $oldFood = $progress->food_days;
        $progress->food_days = $request->input('days');
        $progress->save();

        $progress->logEvent('debug_set', "Отладка: еда изменена {$oldFood} → {$progress->food_days} дней");

        return response()->json([
            'success' => true,
            'message' => "🍞 Еда установлена: {$progress->food_days} дней",
        ]);
    }

    /**
     * Установить процент топлива
     */
    public function setFuel(Request $request): JsonResponse
    {
        $request->validate([
            'percent' => 'required|integer|min:0|max:100',
        ]);

        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $oldFuel = $progress->fuel_percent;
        $progress->fuel_percent = $request->input('percent');
        $progress->save();

        $progress->logEvent('debug_set', "Отладка: топливо изменено {$oldFuel}% → {$progress->fuel_percent}%");

        return response()->json([
            'success' => true,
            'message' => "⛽ Топливо установлено: {$progress->fuel_percent}%",
        ]);
    }

    /**
     * Телепортация к определённой остановке
     */
    public function teleportToStop(Request $request): JsonResponse
    {
        $request->validate([
            'stop_id' => 'required|integer|exists:route_stops,id',
        ]);

        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $stop = RouteStop::find($request->input('stop_id'));

        $progress->current_stop_id = $stop->id;
        $progress->current_point_index = $stop->order_index;
        $progress->game_phase = 'at_stop';
        $progress->save();

        $progress->logEvent('debug_teleport', "Отладка: телепорт к остановке «{$stop->name}»");

        return response()->json([
            'success' => true,
            'message' => "🚀 Телепорт выполнен! Вы на остановке: {$stop->name}",
        ]);
    }

    /**
     * Установить фазу игры
     */
    public function setGamePhase(Request $request): JsonResponse
    {
        $request->validate([
            'phase' => 'required|in:preparation,traveling,at_stop,completed',
        ]);

        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $oldPhase = $progress->game_phase;
        $progress->game_phase = $request->input('phase');
        $progress->save();

        $phaseNames = [
            'preparation' => 'Подготовка',
            'traveling' => 'В пути',
            'at_stop' => 'На остановке',
            'completed' => 'Завершено',
        ];

        $progress->logEvent('debug_set', "Отладка: фаза изменена {$oldPhase} → {$progress->game_phase}");

        return response()->json([
            'success' => true,
            'message' => "🎮 Фаза игры: {$phaseNames[$progress->game_phase]}",
        ]);
    }

    /**
     * Получить лог событий
     */
    public function getEventLog(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Активная игра не найдена',
            ], 404);
        }

        $logs = GameEventLog::where('game_progress_id', $progress->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'type' => $log->event_type,
                    'description' => $log->description,
                    'money_change' => $log->money_change,
                    'time' => $log->created_at->format('d.m.Y H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }
}
