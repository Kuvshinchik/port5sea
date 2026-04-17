<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ConectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Группа маршрутов для основного сайта
Route::group([], function () {
    Route::match(['get', 'post'], '/', [IndexController::class, 'execute'])->name('index');
});

// Подключаем маршруты игры
require __DIR__.'/ship_game.php';

Route::get('/conect', function () {
    $ConectController = new ConectController;
    $ConectController->checkConnection();
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/history_1', function () {
    return view('history.history_1');
})->name('history');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

use App\Http\Controllers\FortuneWheelController;

Route::middleware('auth')->group(function () {
    Route::get('/fortune-wheel', [FortuneWheelController::class, 'index'])->name('fortune.wheel');
    Route::post('/fortune-wheel/spin', [FortuneWheelController::class, 'spin'])->name('fortune.spin');
});
