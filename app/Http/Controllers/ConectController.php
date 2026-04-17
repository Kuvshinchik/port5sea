<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception; // Обязательно импортируйте класс Exception

class ConectController extends Controller
{
      public function checkConnection()
    {
        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                $otvet = "Соединение с базой данных установлено. Имя базы данных: " . DB::connection()->getDatabaseName();
            } else {
                $otvet = "Соединение с базой данных установлено, но имя базы данных не найдено.";
            }
        } catch (Exception $e) {
            $otvet = "Не удалось подключиться к базе данных. Проверьте конфигурацию. Ошибка: " . $e->getMessage();
        }
        return dd($otvet);
    }
}
