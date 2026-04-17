<?php

namespace Database\Seeders;

use App\Models\CrewRole;
use App\Models\RouteStop;
use App\Models\ToyCharacter;
use Illuminate\Database\Seeder;

class TeriberkaSeeder extends Seeder
{
    /**
     * Добавляет данные для остановки в Териберке
     */
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════════════
        // РОЛЬ ВПЕРЁДСМОТРЯЩЕГО (для Чайки)
        // ═══════════════════════════════════════════════════════════════════
        
        $lookoutRole = CrewRole::firstOrCreate(
            ['code' => 'lookout'],
            [
                'name' => 'Вперёдсмотрящий',
                'icon' => '🔭',
                'is_required' => false,
                'base_salary' => 3500,
                'max_count' => 1,
            ]
        );

        // ═══════════════════════════════════════════════════════════════════
        // ПЕРСОНАЖ ЧАЙКА ВПЕРЁДСМОТРЯЩАЯ (игрушка)
        // ═══════════════════════════════════════════════════════════════════
        
        ToyCharacter::firstOrCreate(
            ['character_name' => 'Чайка Вперёдсмотрящая'],
            [
                'toy_id' => 1, // ID игрушки в mezon_domik, замените на реальный
                'crew_role_id' => $lookoutRole->id,
                'skill_level' => 5,
                'trait' => 'Зоркий глаз',
                'trait_effect' => 'Видит опасности и ценные находки раньше других',
                'quote' => 'Кар-р! Вижу что-то интересное на горизонте!',
                'portrait_color' => '#f8fafc',
                'salary_modifier' => 500,
                'special_abilities' => json_encode([
                    'teriberika_bonus' => [
                        'type' => 'mini_game_helper',
                        'effect' => 'Подсказывает местоположение редких моллюсков',
                        'tide_slowdown' => 0.5,
                        'rare_spawn_boost' => 1.5,
                    ],
                    'navigation_bonus' => [
                        'type' => 'travel_helper',
                        'effect' => 'Уменьшает шанс столкновения с айсбергами',
                    ],
                ]),
                'is_active' => true,
            ]
        );

        // ═══════════════════════════════════════════════════════════════════
        // ТОЧКИ МАРШРУТА (добавляем Териберку если её нет)
        // ═══════════════════════════════════════════════════════════════════
        
        // Проверяем, есть ли уже точки маршрута
        $existingStops = RouteStop::where('route_segment', 'murmansk_anadyr')->count();
        
        if ($existingStops === 0) {
            // Создаём полный маршрут
            $routePoints = [
                // Мурманск (старт)
                [
                    'order_index' => 0,
                    'name' => 'Мурманск',
                    'x_coord' => 100,
                    'y_coord' => 300,
                    'is_stop' => true,
                    'level_number' => 1,
                    'salary_days' => 0,
                ],
                // Промежуточная точка
                [
                    'order_index' => 1,
                    'name' => 'Баренцево море',
                    'x_coord' => 180,
                    'y_coord' => 250,
                    'is_stop' => false,
                    'level_number' => null,
                    'salary_days' => 1,
                ],
                // Териберка
                [
                    'order_index' => 2,
                    'name' => 'Териберка',
                    'x_coord' => 250,
                    'y_coord' => 200,
                    'is_stop' => true,
                    'level_number' => 2,
                    'salary_days' => 2,
                ],
                // Промежуточные точки
                [
                    'order_index' => 3,
                    'name' => 'Кольский полуостров',
                    'x_coord' => 350,
                    'y_coord' => 180,
                    'is_stop' => false,
                    'level_number' => null,
                    'salary_days' => 1,
                ],
                // Диксон
                [
                    'order_index' => 4,
                    'name' => 'Диксон',
                    'x_coord' => 450,
                    'y_coord' => 150,
                    'is_stop' => true,
                    'level_number' => 3,
                    'salary_days' => 3,
                ],
                // Промежуточная точка
                [
                    'order_index' => 5,
                    'name' => 'Карское море',
                    'x_coord' => 550,
                    'y_coord' => 160,
                    'is_stop' => false,
                    'level_number' => null,
                    'salary_days' => 2,
                ],
                // Тикси
                [
                    'order_index' => 6,
                    'name' => 'Тикси',
                    'x_coord' => 650,
                    'y_coord' => 140,
                    'is_stop' => true,
                    'level_number' => 4,
                    'salary_days' => 3,
                ],
                // Море Лаптевых
                [
                    'order_index' => 7,
                    'name' => 'Море Лаптевых',
                    'x_coord' => 750,
                    'y_coord' => 150,
                    'is_stop' => false,
                    'level_number' => null,
                    'salary_days' => 2,
                ],
                // Певек
                [
                    'order_index' => 8,
                    'name' => 'Певек',
                    'x_coord' => 850,
                    'y_coord' => 180,
                    'is_stop' => true,
                    'level_number' => 5,
                    'salary_days' => 3,
                ],
                // Чукотское море
                [
                    'order_index' => 9,
                    'name' => 'Чукотское море',
                    'x_coord' => 950,
                    'y_coord' => 220,
                    'is_stop' => false,
                    'level_number' => null,
                    'salary_days' => 2,
                ],
                // Анадырь (финиш)
                [
                    'order_index' => 10,
                    'name' => 'Анадырь',
                    'x_coord' => 1050,
                    'y_coord' => 280,
                    'is_stop' => true,
                    'level_number' => 6,
                    'salary_days' => 0,
                ],
            ];

            foreach ($routePoints as $point) {
                RouteStop::create(array_merge($point, [
                    'route_segment' => 'murmansk_anadyr',
                ]));
            }

            $this->command->info('Создано ' . count($routePoints) . ' точек маршрута');
        } else {
            // Просто обновляем/добавляем Териберку
            RouteStop::updateOrCreate(
                [
                    'route_segment' => 'murmansk_anadyr',
                    'name' => 'Териберка',
                ],
                [
                    'order_index' => 2,
                    'x_coord' => 250,
                    'y_coord' => 200,
                    'is_stop' => true,
                    'level_number' => 2,
                    'salary_days' => 2,
                ]
            );

            $this->command->info('Териберка обновлена в маршруте');
        }

        $this->command->info('Данные Териберки успешно добавлены!');
    }
}
