<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameAdminMiddleware
{
    /**
     * Список email администраторов игры
     * Добавляйте сюда email пользователей, которым разрешён доступ к отладке
     */
    protected array $adminEmails = [
        'toly@toly.ru',
        'admin@example.com',
        // Добавляйте новые email ниже:
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем авторизацию
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Требуется авторизация',
                ], 401);
            }
            return redirect()->route('login');
        }

        // Проверяем, есть ли email пользователя в списке администраторов
        $userEmail = auth()->user()->email;
        
        if (!in_array($userEmail, $this->adminEmails)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Доступ запрещён. Недостаточно прав.',
                ], 403);
            }
            abort(403, 'Доступ запрещён. Недостаточно прав для выполнения этого действия.');
        }

        return $next($request);
    }

    /**
     * Проверить, является ли пользователь администратором игры
     * Статический метод для использования в других частях приложения
     */
    public static function isGameAdmin(?int $userId = null): bool
    {
        if ($userId) {
            $user = \App\Models\User::find($userId);
        } else {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        $adminEmails = [
            'toly@toly.ru',
            //'admin@example.com',
        ];

        return in_array($user->email, $adminEmails);
    }
}
