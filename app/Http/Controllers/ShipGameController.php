<?php

namespace App\Http\Controllers;

use App\Models\CrewRole;
use App\Models\DefaultCrewMember;
use App\Models\EquipmentItem;
use App\Models\GameCrew;
use App\Models\GameEquipment;
use App\Models\GameJob;
use App\Models\GameProgress;
use App\Models\JobOffer;
use App\Models\RouteStop;
use App\Models\ToyCharacter;
use App\Models\UserBonus;
use App\Models\UserToy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShipGameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ═══════════════════════════════════════════════════════════════════
    // СТРАНИЦЫ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Главная страница игры (карта путешествия)
     */
    public function index()
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $routePoints = RouteStop::getRoutePoints();

        return view('ship_game.ship_game', [
            'progress' => $progress,
            'routePoints' => $routePoints,
            'gameState' => $progress->toGameState(),
        ]);
    }

    /**
     * Страница порта Мурманск (подготовка экспедиции)
     */
    public function murmanskPort()
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        // Получаем доступных членов команды
        $crewMembers = $this->getAvailableCrewMembers($user->id, $progress->id);

        // Снаряжение и еда
        $allItems = EquipmentItem::getAllForGame();

        // Подработки
        $jobs = JobOffer::getAllForGame();

        return view('ship_game.murmanskPort', [
            'progress' => $progress,
            'gameState' => $progress->toGameState(),
            'crewMembers' => $crewMembers,
            'equipment' => $allItems['equipment'],
            'food' => $allItems['food'],
            'medicine' => $allItems['medicine'],
            'jobs' => $jobs,
            'maxCrew' => 12,
            'minCrewRequired' => 6,
            'maxCargoCapacity' => 100,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    // API: СОСТОЯНИЕ ИГРЫ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Получить текущее состояние игры
     */
    public function getGameState(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        return response()->json([
            'success' => true,
            'data' => $progress->toGameState(),
        ]);
    }

    /**
     * Сбросить игру (начать заново)
     */
    public function resetGame(): JsonResponse
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {
            // Деактивируем текущую игру
            GameProgress::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        });

        $progress = GameProgress::getOrCreateForUser($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Игра сброшена',
            'data' => $progress->toGameState(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    // API: УПРАВЛЕНИЕ КОМАНДОЙ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Получить список доступных членов команды
     */
    private function getAvailableCrewMembers(int $userId, int $progressId): array
    {
        $members = [];

        // Получаем игрушки пользователя
        $userToys = UserToy::where('user_id', $userId)
            ->with('toyCharacter.crewRole')
            ->get();

        // Добавляем персонажей из игрушек
        foreach ($userToys as $userToy) {
            if ($userToy->toyCharacter && $userToy->toyCharacter->is_active) {
                $members[] = $userToy->toyCharacter->toCrewMemberData();
            }
        }

        // Получаем роли, которые уже заняты игрушками
        $toyRoleCodes = collect($members)->pluck('role_code')->toArray();

        // Добавляем стандартных членов команды (кроме тех ролей, что заняты игрушками)
        $defaultMembers = DefaultCrewMember::where('is_active', true)
            ->with('crewRole')
            ->get();

        foreach ($defaultMembers as $member) {
            // Если роль уже занята игрушкой и это не матрос (матросов может быть несколько)
            if (in_array($member->crewRole->code, $toyRoleCodes) && $member->crewRole->code !== 'sailor') {
                continue;
            }

            $members[] = $member->toCrewMemberData();
        }

        // Помечаем уже нанятых
        $hiredIds = GameCrew::where('game_progress_id', $progressId)
            ->get()
            ->map(function ($gc) {
                return $gc->is_from_toy ? 'toy_' . $gc->toy_character_id : $gc->default_crew_code;
            })
            ->toArray();

        foreach ($members as &$member) {
            $member['hired'] = in_array($member['id'], $hiredIds);
        }

        return $members;
    }

    /**
     * Нанять члена команды
     */
    public function hireCrew(Request $request): JsonResponse
    {
        $request->validate([
            'member_id' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $memberId = $request->input('member_id');

        // Проверяем лимит команды
        if ($progress->getCrewCount() >= 12) {
            return response()->json([
                'success' => false,
                'message' => 'Экипаж укомплектован! Максимум 12 человек.',
            ], 400);
        }

        // Определяем тип члена команды
        if (str_starts_with($memberId, 'toy_')) {
            // Это персонаж из игрушки
            $toyCharacterId = (int) str_replace('toy_', '', $memberId);
            $toyCharacter = ToyCharacter::with('crewRole')->find($toyCharacterId);

            if (!$toyCharacter) {
                return response()->json(['success' => false, 'message' => 'Персонаж не найден'], 404);
            }

            // Проверяем, есть ли у пользователя эта игрушка
            $hasThis = UserToy::where('user_id', $user->id)
                ->where('toy_character_id', $toyCharacterId)
                ->exists();

            if (!$hasThis) {
                return response()->json(['success' => false, 'message' => 'У вас нет этой игрушки'], 403);
            }

            // Проверяем, не нанят ли уже
            $alreadyHired = GameCrew::where('game_progress_id', $progress->id)
                ->where('toy_character_id', $toyCharacterId)
                ->exists();

            if ($alreadyHired) {
                return response()->json(['success' => false, 'message' => 'Уже нанят'], 400);
            }

            $salary = $toyCharacter->getSalary();

            GameCrew::create([
                'game_progress_id' => $progress->id,
                'toy_character_id' => $toyCharacterId,
                'crew_role_id' => $toyCharacter->crew_role_id,
                'current_salary' => $salary,
                'is_from_toy' => true,
            ]);

            $progress->logEvent('hire', "Нанят {$toyCharacter->character_name}", [
                'member_id' => $memberId,
                'salary' => $salary,
            ]);

            $memberName = $toyCharacter->character_name;

        } else {
            // Стандартный член команды
            $defaultMember = DefaultCrewMember::where('code', $memberId)
                ->with('crewRole')
                ->first();

            if (!$defaultMember) {
                return response()->json(['success' => false, 'message' => 'Член команды не найден'], 404);
            }

            // Проверяем, не нанят ли уже
            $alreadyHired = GameCrew::where('game_progress_id', $progress->id)
                ->where('default_crew_code', $memberId)
                ->exists();

            if ($alreadyHired) {
                return response()->json(['success' => false, 'message' => 'Уже нанят'], 400);
            }

            GameCrew::create([
                'game_progress_id' => $progress->id,
                'default_crew_code' => $memberId,
                'crew_role_id' => $defaultMember->crew_role_id,
                'current_salary' => $defaultMember->salary,
                'is_from_toy' => false,
            ]);

            $progress->logEvent('hire', "Нанят {$defaultMember->name}", [
                'member_id' => $memberId,
                'salary' => $defaultMember->salary,
            ]);

            $memberName = $defaultMember->name;
        }

        return response()->json([
            'success' => true,
            'message' => "✅ {$memberName} нанят(а)!",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    /**
     * Уволить члена команды
     */
    public function fireCrew(Request $request): JsonResponse
    {
        $request->validate([
            'member_id' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $memberId = $request->input('member_id');

        if (str_starts_with($memberId, 'toy_')) {
            $toyCharacterId = (int) str_replace('toy_', '', $memberId);
            $crew = GameCrew::where('game_progress_id', $progress->id)
                ->where('toy_character_id', $toyCharacterId)
                ->first();
        } else {
            $crew = GameCrew::where('game_progress_id', $progress->id)
                ->where('default_crew_code', $memberId)
                ->first();
        }

        if (!$crew) {
            return response()->json(['success' => false, 'message' => 'Член команды не найден'], 404);
        }

        $memberName = $crew->is_from_toy
            ? $crew->toyCharacter->character_name
            : DefaultCrewMember::where('code', $crew->default_crew_code)->value('name');

        $crew->delete();

        $progress->logEvent('fire', "Уволен {$memberName}", ['member_id' => $memberId]);

        return response()->json([
            'success' => true,
            'message' => "❌ {$memberName} уволен(а)",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    // API: СНАРЯЖЕНИЕ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Купить снаряжение
     */
    public function buyEquipment(Request $request): JsonResponse
    {
        $request->validate([
            'item_code' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $itemCode = $request->input('item_code');

        $item = EquipmentItem::where('code', $itemCode)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Предмет не найден'], 404);
        }

        // Проверяем, не куплен ли уже
        $alreadyBought = GameEquipment::where('game_progress_id', $progress->id)
            ->where('equipment_item_id', $item->id)
            ->exists();

        if ($alreadyBought) {
            return response()->json(['success' => false, 'message' => 'Уже куплено'], 400);
        }

        // Проверяем деньги
        if (!$progress->canAfford($item->price)) {
            return response()->json(['success' => false, 'message' => 'Недостаточно денег!'], 400);
        }

        // Проверяем место в трюме
        if (!$progress->hasCargoSpace($item->weight)) {
            return response()->json(['success' => false, 'message' => 'Нет места в трюме!'], 400);
        }

        DB::transaction(function () use ($progress, $item) {
            $progress->spendMoney($item->price, "Покупка: {$item->name}");
            $progress->addCargo($item->weight);

            if ($item->food_days > 0) {
                $progress->addFoodDays($item->food_days);
            }

            GameEquipment::create([
                'game_progress_id' => $progress->id,
                'equipment_item_id' => $item->id,
                'quantity' => 1,
                'purchase_price' => $item->price,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "✅ Куплено: {$item->name}",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    /**
     * Продать снаряжение (возврат 80%)
     */
    public function sellEquipment(Request $request): JsonResponse
    {
        $request->validate([
            'item_code' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $itemCode = $request->input('item_code');

        $item = EquipmentItem::where('code', $itemCode)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Предмет не найден'], 404);
        }

        $gameEquipment = GameEquipment::where('game_progress_id', $progress->id)
            ->where('equipment_item_id', $item->id)
            ->first();

        if (!$gameEquipment) {
            return response()->json(['success' => false, 'message' => 'У вас нет этого предмета'], 400);
        }

        $refund = (int) ($item->price * 0.8);

        DB::transaction(function () use ($progress, $item, $gameEquipment, $refund) {
            $progress->earnMoney($refund, "Возврат: {$item->name}");
            $progress->removeCargo($item->weight);

            if ($item->food_days > 0) {
                $progress->food_days = max(0, $progress->food_days - $item->food_days);
                $progress->save();
            }

            $gameEquipment->delete();
        });

        return response()->json([
            'success' => true,
            'message' => "💰 Возврат: " . number_format($refund, 0, ',', ' ') . " ₽",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    // API: ПОДРАБОТКИ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Принять подработку
     */
    public function acceptJob(Request $request): JsonResponse
    {
        $request->validate([
            'job_code' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $jobCode = $request->input('job_code');

        $job = JobOffer::where('code', $jobCode)->first();

        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Подработка не найдена'], 404);
        }

        // Проверяем, не принята ли уже
        $alreadyAccepted = GameJob::where('game_progress_id', $progress->id)
            ->where('job_offer_id', $job->id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->exists();

        if ($alreadyAccepted) {
            return response()->json(['success' => false, 'message' => 'Уже принята'], 400);
        }

        // Проверяем место в трюме
        if ($job->cargo_weight > 0 && !$progress->hasCargoSpace($job->cargo_weight)) {
            return response()->json(['success' => false, 'message' => 'Нет места в трюме для груза!'], 400);
        }

        DB::transaction(function () use ($progress, $job) {
            if ($job->cargo_weight > 0) {
                $progress->addCargo($job->cargo_weight);
            }

            GameJob::create([
                'game_progress_id' => $progress->id,
                'job_offer_id' => $job->id,
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $progress->logEvent('job_accept', "Принята подработка: {$job->title}", [
                'job_code' => $job->code,
                'reward' => $job->reward,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "✅ Принято: {$job->title}",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    /**
     * Отменить подработку
     */
    public function cancelJob(Request $request): JsonResponse
    {
        $request->validate([
            'job_code' => 'required|string',
        ]);

        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);
        $jobCode = $request->input('job_code');

        $job = JobOffer::where('code', $jobCode)->first();

        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Подработка не найдена'], 404);
        }

        $gameJob = GameJob::where('game_progress_id', $progress->id)
            ->where('job_offer_id', $job->id)
            ->where('status', 'accepted')
            ->first();

        if (!$gameJob) {
            return response()->json(['success' => false, 'message' => 'Подработка не найдена в вашем списке'], 400);
        }

        DB::transaction(function () use ($progress, $job, $gameJob) {
            if ($job->cargo_weight > 0) {
                $progress->removeCargo($job->cargo_weight);
            }

            $gameJob->delete();

            $progress->logEvent('job_cancel', "Отменена подработка: {$job->title}", [
                'job_code' => $job->code,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "❌ Отменено: {$job->title}",
            'data' => $progress->fresh()->toGameState(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    // API: ОТПЛЫТИЕ И ПУТЕШЕСТВИЕ
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Проверить готовность к отплытию
     */
    public function checkReadyToDepart(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        $problems = $progress->isReadyToDepart();

        if (empty($problems)) {
            return response()->json([
                'success' => true,
                'ready' => true,
                'message' => 'Готовы к отплытию!',
                'data' => $progress->toGameState(),
            ]);
        }

        return response()->json([
            'success' => true,
            'ready' => false,
            'problems' => $problems,
        ]);
    }

    /**
     * Начать путешествие (отплыть)
     */
    public function depart(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        $problems = $progress->isReadyToDepart();

        if (!empty($problems)) {
            return response()->json([
                'success' => false,
                'message' => 'Не готовы к отплытию',
                'problems' => $problems,
            ], 400);
        }

        $progress->game_phase = 'traveling';
        $progress->save();

        $progress->logEvent('depart', 'Экспедиция отправилась в путь!');

        return response()->json([
            'success' => true,
            'message' => '🚢 Отправляемся в путь!',
            'redirect' => route('ship_game.index'),
            'data' => $progress->toGameState(),
        ]);
    }

    /**
     * Перейти к следующей точке маршрута
     */
    public function moveToNextPoint(): JsonResponse
    {
        $user = Auth::user();
        $progress = GameProgress::getOrCreateForUser($user->id);

        $nextStop = $progress->moveToNextPoint();

        if (!$nextStop) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => '🎉 Поздравляем! Маршрут пройден!',
                'data' => $progress->toGameState(),
            ]);
        }

        $response = [
            'success' => true,
            'completed' => false,
            'next_point' => [
                'id' => $nextStop->id,
                'name' => $nextStop->name,
                'x' => $nextStop->x_coord,
                'y' => $nextStop->y_coord,
                'is_stop' => $nextStop->is_stop,
                'level' => $nextStop->level_number,
            ],
            'data' => $progress->toGameState(),
        ];

        if ($nextStop->is_stop) {
            $response['message'] = "📍 Прибыли: {$nextStop->name}";
        }

        return response()->json($response);
    }
}
