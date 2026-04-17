<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrizePoint;

class FortuneWheelController extends Controller
{
    /**
     * Показывает страницу с колесом фортуны
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        $prizePoints = UserPrizePoint::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        return view('fortune-wheel', compact('prizePoints'));
    }

    /**
     * Обрабатывает вращение колеса (AJAX)
     */
    public function spin(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $prizePoints = UserPrizePoint::where('user_id', $user->id)->first();

        // ============================================
        // ОГРАНИЧЕНИЕ: раз в 24 часа (раскомментируйте если нужно)
        // ============================================
        /*
        if ($prizePoints && $prizePoints->last_spin_at) {
            $hoursSinceLastSpin = now()->diffInHours($prizePoints->last_spin_at);
            
            if ($hoursSinceLastSpin < 24) {
                return response()->json([
                    'success' => false,
                    'error' => 'Вы можете крутить колесо раз в 24 часа.',
                    'next_spin_in_hours' => 24 - $hoursSinceLastSpin
                ], 429);
            }
        }
        */

        // ============================================
        // 8 ПОЗИЦИЙ КОЛЕСА
        // ============================================
        // Угол 0° = верх (стрелка указывает вверх)
        // Углы идут по часовой стрелке:
        //   0° = верх
        //  45° = верх-право
        //  90° = право
        // 135° = низ-право
        // 180° = низ
        // 225° = низ-лево
        // 270° = лево
        // 315° = верх-лево
        // ============================================
        
        $positions = [0, 45, 90, 135, 180, 225, 270, 315];
        
        $pointsMap = [
            0   => 12,   // Верх
            45  => 1,    // Верх-право
            90  => 3,    // Право
            135 => 2,    // Низ-право
            180 => 6,    // Низ
            225 => 5,    // Низ-лево
            270 => 9,    // Лево
            315 => 15,   // Верх-лево
        ];

        // Выбираем случайную позицию
        $randomIndex = array_rand($positions);
        $selectedAngle = $positions[$randomIndex];
        $earnedPoints = $pointsMap[$selectedAngle];

        // Сохраняем в базу
        $currentBalance = $prizePoints ? $prizePoints->balance : 0;
        $newBalance = $currentBalance + $earnedPoints;
        
        UserPrizePoint::updateOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => $newBalance,
                'last_spin_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'angle' => $selectedAngle,
            'points' => $earnedPoints,
            'total_balance' => $newBalance,
        ]);
    }
}
