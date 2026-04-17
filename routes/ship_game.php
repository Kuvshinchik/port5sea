<?php

use App\Http\Controllers\ShipGameController;
use App\Http\Controllers\GameDebugController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ShipGame\MiniGames\TeriberkaMiniGameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ship Game Routes
|--------------------------------------------------------------------------
|
| Маршруты для игры "Путешествие по Северному морскому пути"
| Все маршруты требуют авторизации
|
*/

Route::middleware('auth')->prefix('ship-game')->name('ship_game.')->group(function () {
    
    // ═══════════════════════════════════════════════════════════════════
    // СТРАНИЦЫ
    // ═══════════════════════════════════════════════════════════════════
    
    // Главная страница игры (карта)
    Route::get('/', [ShipGameController::class, 'index'])->name('index');
    
    // Алиас для совместимости с app.blade.php
    Route::get('/game', [ShipGameController::class, 'index'])->name('ship_game');
    
    // Порт Мурманск (подготовка)
    Route::get('/murmansk-port', [ShipGameController::class, 'murmanskPort'])->name('murmansk_port');
    
    // ═══════════════════════════════════════════════════════════════════
    // МИНИ-ИГРЫ (ОСТАНОВКИ)
    // ═══════════════════════════════════════════════════════════════════
    
    // Териберка - Сбор моллюсков
    Route::prefix('teriberika')->name('teriberika.')->group(function () {
        Route::get('/', [TeriberkaMiniGameController::class, 'index'])->name('index');
        Route::post('/start', [TeriberkaMiniGameController::class, 'startGame'])->name('start');
        Route::post('/submit', [TeriberkaMiniGameController::class, 'submitResult'])->name('submit');
        Route::get('/leaderboard', [TeriberkaMiniGameController::class, 'leaderboard'])->name('leaderboard');
    });
    
    // Здесь можно добавить другие мини-игры:
    // Route::prefix('dikson')->name('dikson.')->group(function () { ... });
    // Route::prefix('tiksi')->name('tiksi.')->group(function () { ... });
    
    // ═══════════════════════════════════════════════════════════════════
    // API: СОСТОЯНИЕ ИГРЫ
    // ═══════════════════════════════════════════════════════════════════
    
    Route::prefix('api')->name('api.')->group(function () {
        
        // Получить текущее состояние
        Route::get('/state', [ShipGameController::class, 'getGameState'])->name('state');
        
        // Сбросить игру
        Route::post('/reset', [ShipGameController::class, 'resetGame'])->name('reset');
        
        // ═══════════════════════════════════════════════════════════════
        // КОМАНДА
        // ═══════════════════════════════════════════════════════════════
        
        Route::post('/crew/hire', [ShipGameController::class, 'hireCrew'])->name('crew.hire');
        Route::post('/crew/fire', [ShipGameController::class, 'fireCrew'])->name('crew.fire');
        
        // ═══════════════════════════════════════════════════════════════
        // СНАРЯЖЕНИЕ
        // ═══════════════════════════════════════════════════════════════
        
        Route::post('/equipment/buy', [ShipGameController::class, 'buyEquipment'])->name('equipment.buy');
        Route::post('/equipment/sell', [ShipGameController::class, 'sellEquipment'])->name('equipment.sell');
        
        // ═══════════════════════════════════════════════════════════════
        // ПОДРАБОТКИ
        // ═══════════════════════════════════════════════════════════════
        
        Route::post('/job/accept', [ShipGameController::class, 'acceptJob'])->name('job.accept');
        Route::post('/job/cancel', [ShipGameController::class, 'cancelJob'])->name('job.cancel');
        
        // ═══════════════════════════════════════════════════════════════
        // ПУТЕШЕСТВИЕ
        // ═══════════════════════════════════════════════════════════════
        
        Route::get('/ready-to-depart', [ShipGameController::class, 'checkReadyToDepart'])->name('ready');
        Route::post('/depart', [ShipGameController::class, 'depart'])->name('depart');
        Route::post('/move-next', [ShipGameController::class, 'moveToNextPoint'])->name('move');
    });
    
    // ═══════════════════════════════════════════════════════════════════
    // ПАНЕЛЬ ОТЛАДКИ (только для администраторов)
    // ═══════════════════════════════════════════════════════════════════
    
    Route::middleware('game.admin')->prefix('debug')->name('debug.')->group(function () {
        
        // Страница панели отладки
        Route::get('/', [GameDebugController::class, 'index'])->name('index');
        
        // API отладки
        Route::prefix('api')->group(function () {
            Route::post('/full-reset', [GameDebugController::class, 'fullReset']);
            Route::post('/reset-crew', [GameDebugController::class, 'resetCrew']);
            Route::post('/reset-equipment', [GameDebugController::class, 'resetEquipment']);
            Route::post('/reset-jobs', [GameDebugController::class, 'resetJobs']);
            Route::post('/set-money', [GameDebugController::class, 'setMoney']);
            Route::post('/set-food', [GameDebugController::class, 'setFood']);
            Route::post('/set-fuel', [GameDebugController::class, 'setFuel']);
            Route::post('/set-phase', [GameDebugController::class, 'setGamePhase']);
            Route::post('/teleport', [GameDebugController::class, 'teleportToStop']);
            Route::get('/logs', [GameDebugController::class, 'getEventLog']);
        });
    });
});

// ═══════════════════════════════════════════════════════════════════
// QR-КОДЫ
// ═══════════════════════════════════════════════════════════════════

Route::middleware('auth')->prefix('qr')->name('qr.')->group(function () {
    Route::get('/activate', [QrCodeController::class, 'showActivationForm'])->name('form');
    Route::post('/activate', [QrCodeController::class, 'activate'])->name('activate');
    Route::get('/my-toys', [QrCodeController::class, 'myToys'])->name('my_toys');
});
