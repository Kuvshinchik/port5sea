<?php

namespace App\Http\Controllers;

use App\Models\ToyQrCode;
use App\Models\ToyCharacter;
use App\Models\GameProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Страница активации QR-кода
     */
    public function showActivationForm()
    {
        return view('qr.activate');
    }

    /**
     * Активировать QR-код
     */
    public function activate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $code = strtoupper(trim($request->input('code')));

        $qrCode = ToyQrCode::where('code', $code)->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR-код не найден. Проверьте правильность ввода.',
            ], 404);
        }

        if ($qrCode->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Этот QR-код уже был активирован.',
            ], 400);
        }

        $activated = $qrCode->activate($user->id);

        if (!$activated) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось активировать QR-код.',
            ], 400);
        }

        // Получаем информацию о персонаже
        $toyCharacter = ToyCharacter::where('toy_id', $qrCode->toy_id)
            ->with('crewRole')
            ->first();

        $bonusMessage = '';
        
        // Проверяем, получил ли пользователь новые бонусы
        $userToysCount = \App\Models\UserToy::where('user_id', $user->id)->count();
        
        if ($userToysCount >= 3) {
            $bonusMessage = "\n🎁 Поздравляем! Вы разблокировали бонус «Бесконечное топливо»!";
        } elseif ($userToysCount >= 2) {
            $bonusMessage = "\n💰 Бонус за 2 игрушки: +25 000 ₽!";
        }

        return response()->json([
            'success' => true,
            'message' => "🎉 QR-код активирован!\nНовый член команды: {$toyCharacter->character_name} ({$toyCharacter->crewRole->name})\n💰 Бонус: +10 000 ₽{$bonusMessage}",
            'character' => [
                'id' => $toyCharacter->id,
                'name' => $toyCharacter->character_name,
                'role' => $toyCharacter->crewRole->name,
                'avatar' => $toyCharacter->getAvatarUrl(),
            ],
            'toys_count' => $userToysCount,
        ]);
    }

    /**
     * Получить список активированных игрушек пользователя
     */
    public function myToys(): JsonResponse
    {
        $user = Auth::user();

        $toys = \App\Models\UserToy::where('user_id', $user->id)
            ->with(['toyCharacter.crewRole', 'qrCode'])
            ->get()
            ->map(function ($userToy) {
                $char = $userToy->toyCharacter;
                return [
                    'id' => $char->id,
                    'name' => $char->character_name,
                    'role' => $char->crewRole->name,
                    'role_icon' => $char->crewRole->icon,
                    'skill_level' => $char->skill_level,
                    'trait' => $char->trait,
                    'avatar_url' => $char->getAvatarUrl(),
                    'activated_at' => $userToy->qrCode->activated_at->format('d.m.Y H:i'),
                    'bonus_money' => $userToy->bonus_money,
                ];
            });

        $activeBonuses = \App\Models\UserBonus::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('gameBonus')
            ->get()
            ->map(function ($ub) {
                return [
                    'code' => $ub->gameBonus->code,
                    'name' => $ub->gameBonus->name,
                    'description' => $ub->gameBonus->description,
                    'is_permanent' => $ub->gameBonus->is_permanent,
                ];
            });

        return response()->json([
            'success' => true,
            'toys' => $toys,
            'toys_count' => $toys->count(),
            'active_bonuses' => $activeBonuses,
        ]);
    }
}
