<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShipGameSeeder extends Seeder
{
    /**
     * Заполнение базовых данных для игры
     */
    public function run(): void
    {
        $now = Carbon::now();

        // ═══════════════════════════════════════════════════════════════════
        // ПРОФЕССИИ КОМАНДЫ
        // ═══════════════════════════════════════════════════════════════════
        $roles = [
            ['code' => 'captain', 'name' => 'Капитан', 'icon' => '👨‍✈️', 'is_required' => true, 'base_salary' => 2000, 'max_count' => 1],
            ['code' => 'first_mate', 'name' => 'Помощник капитана', 'icon' => '🧭', 'is_required' => false, 'base_salary' => 1800, 'max_count' => 1],
            ['code' => 'mechanic', 'name' => 'Механик', 'icon' => '🔧', 'is_required' => true, 'base_salary' => 1500, 'max_count' => 2],
            ['code' => 'doctor', 'name' => 'Врач', 'icon' => '👨‍⚕️', 'is_required' => true, 'base_salary' => 1800, 'max_count' => 1],
            ['code' => 'cook', 'name' => 'Кок', 'icon' => '👨‍🍳', 'is_required' => true, 'base_salary' => 1000, 'max_count' => 1],
            ['code' => 'navigator', 'name' => 'Штурман', 'icon' => '🧭', 'is_required' => true, 'base_salary' => 1400, 'max_count' => 1],
            ['code' => 'radioman', 'name' => 'Радист', 'icon' => '📻', 'is_required' => true, 'base_salary' => 1100, 'max_count' => 1],
            ['code' => 'sailor', 'name' => 'Матрос', 'icon' => '⚓', 'is_required' => false, 'base_salary' => 800, 'max_count' => 5],
            ['code' => 'boatswain', 'name' => 'Боцман', 'icon' => '🚢', 'is_required' => false, 'base_salary' => 1300, 'max_count' => 1],
            ['code' => 'lookout', 'name' => 'Впередсмотрящий', 'icon' => '👁️', 'is_required' => false, 'base_salary' => 900, 'max_count' => 1],
            ['code' => 'pilot', 'name' => 'Лоцман', 'icon' => '🗺️', 'is_required' => false, 'base_salary' => 1600, 'max_count' => 1],
            ['code' => 'diver', 'name' => 'Водолаз', 'icon' => '🤿', 'is_required' => false, 'base_salary' => 1300, 'max_count' => 1],
            ['code' => 'scientist', 'name' => 'Учёный', 'icon' => '🔬', 'is_required' => false, 'base_salary' => 1600, 'max_count' => 1],
        ];

        foreach ($roles as $role) {
            $role['created_at'] = $now;
            $role['updated_at'] = $now;
            DB::table('crew_roles')->insert($role);
        }

        // ═══════════════════════════════════════════════════════════════════
        // ПЕРСОНАЖИ ИЗ ИГРУШЕК (связь с mezon_domik)
        // ═══════════════════════════════════════════════════════════════════
        $toyCharacters = [
            [
                'toy_id' => 5, // Медведь Капитан Арктика
                'crew_role_id' => 1, // captain
                'character_name' => 'Капитан Арктика',
                'skill_level' => 3,
                'trait' => 'Опытный навигатор',
                'trait_effect' => '+20% к скорости во льдах',
                'quote' => 'Я проведу наш корабль через любые льды!',
                'portrait_color' => '#3498db',
                'salary_modifier' => 500,
            ],
            [
                'toy_id' => 12, // Помощник Капитана (белый мишка)
                'crew_role_id' => 2, // first_mate
                'character_name' => 'Помощник Марсель',
                'skill_level' => 3,
                'trait' => 'Надёжная опора',
                'trait_effect' => '+15% к морали команды',
                'quote' => 'Всегда готов прийти на помощь!',
                'portrait_color' => '#ecf0f1',
                'salary_modifier' => 300,
            ],
            [
                'toy_id' => 1, // Морж Боцман Жорж
                'crew_role_id' => 9, // boatswain
                'character_name' => 'Боцман Жорж',
                'skill_level' => 3,
                'trait' => 'Мастер на все ласты',
                'trait_effect' => '+10% к ремонту, +10% к швартовке',
                'quote' => 'Суровый, но добродушный - это про меня!',
                'portrait_color' => '#795548',
                'salary_modifier' => 200,
            ],
            [
                'toy_id' => 2, // Кот Старпом Полоскин
                'crew_role_id' => 6, // navigator
                'character_name' => 'Штурман Полоскин',
                'skill_level' => 3,
                'trait' => 'Морской глаз',
                'trait_effect' => '+15% к навигации, -10% риск посадки на мель',
                'quote' => 'Мой полосатый хвост чует курс!',
                'portrait_color' => '#ff9800',
                'salary_modifier' => 400,
            ],
            [
                'toy_id' => 3, // Чайка Вперёдсмотрящий
                'crew_role_id' => 10, // lookout
                'character_name' => 'Чайка-наблюдатель',
                'skill_level' => 3,
                'trait' => 'Острое зрение',
                'trait_effect' => 'Раннее обнаружение опасностей и островов',
                'quote' => 'Ни один айсберг не останется незамеченным!',
                'portrait_color' => '#fafafa',
                'salary_modifier' => 100,
            ],
            [
                'toy_id' => 4, // Пингвин Кок Макарони
                'crew_role_id' => 5, // cook
                'character_name' => 'Кок Макарони',
                'skill_level' => 3,
                'trait' => 'Кулинар-виртуоз',
                'trait_effect' => '-25% расход еды, +10% мораль',
                'quote' => 'Накормлю всю команду так, что пальчики оближете!',
                'portrait_color' => '#263238',
                'salary_modifier' => 200,
            ],
            [
                'toy_id' => 8, // Арктический Заяц Матрос
                'crew_role_id' => 8, // sailor
                'character_name' => 'Матрос-разведчик',
                'skill_level' => 2,
                'trait' => 'Молниеносная реакция',
                'trait_effect' => '+20% скорость погрузки/разгрузки',
                'quote' => 'Первый на берег, первый обратно!',
                'portrait_color' => '#e0e0e0',
                'salary_modifier' => 0,
            ],
            [
                'toy_id' => 10, // Нерпа Лоцман Коля
                'crew_role_id' => 11, // pilot
                'character_name' => 'Лоцман Коля',
                'skill_level' => 3,
                'trait' => 'Чутьё подводных течений',
                'trait_effect' => '+25% к навигации в сложных водах',
                'quote' => 'Я чувствую каждое течение!',
                'portrait_color' => '#607d8b',
                'salary_modifier' => 300,
            ],
            [
                'toy_id' => 11, // Тюлень Подводник Жак-Ив
                'crew_role_id' => 12, // diver
                'character_name' => 'Подводник Жак-Ив',
                'skill_level' => 3,
                'trait' => 'Глубоководный исследователь',
                'trait_effect' => 'Подводный ремонт без дока, поиск сокровищ',
                'quote' => 'Под водой — как дома!',
                'portrait_color' => '#455a64',
                'salary_modifier' => 400,
            ],
        ];

        foreach ($toyCharacters as $char) {
            $char['created_at'] = $now;
            $char['updated_at'] = $now;
            DB::table('toy_characters')->insert($char);
        }

        // ═══════════════════════════════════════════════════════════════════
        // СТАНДАРТНЫЕ ЧЛЕНЫ КОМАНДЫ (для игроков без игрушек)
        // ═══════════════════════════════════════════════════════════════════
        $defaultCrew = [
            ['code' => 'captain', 'crew_role_id' => 1, 'name' => 'Иван Северов', 'skill_level' => 3, 'trait' => 'Опытный навигатор', 'trait_effect' => '+20% к скорости во льдах', 'quote' => 'Я проведу наш корабль через любые льды!', 'portrait_color' => '#3498db', 'salary' => 2000, 'is_required' => true],
            ['code' => 'mechanic1', 'crew_role_id' => 3, 'name' => 'Пётр Гаечкин', 'skill_level' => 3, 'trait' => 'Экономит топливо', 'trait_effect' => '-15% расход топлива', 'quote' => 'Я умею чинить двигатель прямо во льдах!', 'portrait_color' => '#e67e22', 'salary' => 1500, 'is_required' => true],
            ['code' => 'mechanic2', 'crew_role_id' => 3, 'name' => 'Анна Болтова', 'skill_level' => 2, 'trait' => 'Быстрый ремонт', 'trait_effect' => '-30% время ремонта', 'quote' => 'Дайте мне гаечный ключ — и корабль будет как новый!', 'portrait_color' => '#9b59b6', 'salary' => 1200, 'is_required' => false],
            ['code' => 'doctor', 'crew_role_id' => 4, 'name' => 'Елена Айболитова', 'skill_level' => 3, 'trait' => 'Профилактика болезней', 'trait_effect' => '-50% шанс болезней', 'quote' => 'Здоровье команды — моя главная забота!', 'portrait_color' => '#1abc9c', 'salary' => 1800, 'is_required' => true],
            ['code' => 'cook', 'crew_role_id' => 5, 'name' => 'Борис Поваров', 'skill_level' => 2, 'trait' => 'Экономная готовка', 'trait_effect' => '-20% расход еды', 'quote' => 'Накормлю всю команду так, что пальчики оближете!', 'portrait_color' => '#f39c12', 'salary' => 1000, 'is_required' => true],
            ['code' => 'navigator', 'crew_role_id' => 6, 'name' => 'Ольга Компасова', 'skill_level' => 2, 'trait' => 'Знание маршрутов', 'trait_effect' => '+15% к навигации', 'quote' => 'Северный морской путь — моя вторая родина!', 'portrait_color' => '#e74c3c', 'salary' => 1400, 'is_required' => true],
            ['code' => 'radioman', 'crew_role_id' => 7, 'name' => 'Сергей Волнов', 'skill_level' => 2, 'trait' => 'Связь с берегом', 'trait_effect' => '+погодные предупреждения', 'quote' => 'Всегда на связи, в любую погоду!', 'portrait_color' => '#2980b9', 'salary' => 1100, 'is_required' => true],
            ['code' => 'sailor1', 'crew_role_id' => 8, 'name' => 'Николай Морской', 'skill_level' => 1, 'trait' => 'Крепкий здоровьем', 'trait_effect' => '+устойчивость к холоду', 'quote' => 'Готов к любой работе на палубе!', 'portrait_color' => '#34495e', 'salary' => 800, 'is_required' => false],
            ['code' => 'sailor2', 'crew_role_id' => 8, 'name' => 'Мария Якорева', 'skill_level' => 2, 'trait' => 'Ловкая', 'trait_effect' => '+скорость при швартовке', 'quote' => 'Женщина на корабле — к удаче!', 'portrait_color' => '#8e44ad', 'salary' => 800, 'is_required' => false],
            ['code' => 'sailor3', 'crew_role_id' => 8, 'name' => 'Алексей Канатов', 'skill_level' => 1, 'trait' => 'Боится льдов', 'trait_effect' => '-настроение во льдах', 'quote' => 'Надеюсь, льдов будет не слишком много...', 'portrait_color' => '#7f8c8d', 'salary' => 700, 'is_required' => false],
            ['code' => 'scientist', 'crew_role_id' => 13, 'name' => 'Виктор Наукин', 'skill_level' => 3, 'trait' => 'Исследователь', 'trait_effect' => '+бонусы за открытия', 'quote' => 'Каждая экспедиция — это новые открытия!', 'portrait_color' => '#16a085', 'salary' => 1600, 'is_required' => false],
            ['code' => 'diver', 'crew_role_id' => 12, 'name' => 'Дмитрий Глубинов', 'skill_level' => 2, 'trait' => 'Подводный ремонт', 'trait_effect' => '+ремонт без дока', 'quote' => 'Под водой — как дома!', 'portrait_color' => '#0984e3', 'salary' => 1300, 'is_required' => false],
        ];

        foreach ($defaultCrew as $member) {
            $member['created_at'] = $now;
            $member['updated_at'] = $now;
            DB::table('default_crew_members')->insert($member);
        }

        // ═══════════════════════════════════════════════════════════════════
        // ОСТАНОВКИ МАРШРУТА
        // ═══════════════════════════════════════════════════════════════════
        $routeStops = [
            ['order_index' => 0, 'name' => 'Мурманск', 'x_coord' => 625, 'y_coord' => 462, 'is_stop' => true, 'level_number' => 1, 'salary_days' => 0],
            ['order_index' => 1, 'name' => 'Переход', 'x_coord' => 639, 'y_coord' => 441, 'is_stop' => false, 'level_number' => null, 'salary_days' => 0],
            ['order_index' => 2, 'name' => 'Териберка', 'x_coord' => 680, 'y_coord' => 466, 'is_stop' => true, 'level_number' => 2, 'salary_days' => 1],
            ['order_index' => 3, 'name' => 'Мыс Надежда', 'x_coord' => 751, 'y_coord' => 478, 'is_stop' => true, 'level_number' => 3, 'salary_days' => 1],
            ['order_index' => 4, 'name' => 'Остров Моржовский', 'x_coord' => 758, 'y_coord' => 534, 'is_stop' => true, 'level_number' => 4, 'salary_days' => 1],
            ['order_index' => 5, 'name' => 'Соловецкие острова', 'x_coord' => 664, 'y_coord' => 594, 'is_stop' => true, 'level_number' => 5, 'salary_days' => 2],
            ['order_index' => 6, 'name' => 'Переход', 'x_coord' => 631, 'y_coord' => 540, 'is_stop' => false, 'level_number' => null, 'salary_days' => 0],
            ['order_index' => 7, 'name' => 'Кандалакша', 'x_coord' => 615, 'y_coord' => 525, 'is_stop' => true, 'level_number' => 6, 'salary_days' => 1],
            ['order_index' => 8, 'name' => 'Переход', 'x_coord' => 631, 'y_coord' => 540, 'is_stop' => false, 'level_number' => null, 'salary_days' => 0],
            ['order_index' => 9, 'name' => 'Переход', 'x_coord' => 694, 'y_coord' => 576, 'is_stop' => false, 'level_number' => null, 'salary_days' => 0],
            ['order_index' => 10, 'name' => 'Архангельск', 'x_coord' => 738, 'y_coord' => 612, 'is_stop' => true, 'level_number' => 7, 'salary_days' => 2],
        ];

        foreach ($routeStops as $stop) {
            $stop['route_segment'] = 'murmansk_anadyr';
            $stop['created_at'] = $now;
            $stop['updated_at'] = $now;
            DB::table('route_stops')->insert($stop);
        }

        // ═══════════════════════════════════════════════════════════════════
        // СНАРЯЖЕНИЕ
        // ═══════════════════════════════════════════════════════════════════
        $equipment = [
            // Оборудование
            ['code' => 'tools', 'name' => 'Набор инструментов', 'icon' => '🧰', 'category' => 'equipment', 'price' => 5000, 'weight' => 5, 'effect' => '+10% к ремонту'],
            ['code' => 'rope', 'name' => 'Канаты (комплект)', 'icon' => '🪢', 'category' => 'equipment', 'price' => 2000, 'weight' => 3, 'effect' => 'Необходимо для швартовки'],
            ['code' => 'radio', 'name' => 'Запасная рация', 'icon' => '📻', 'category' => 'equipment', 'price' => 8000, 'weight' => 2, 'effect' => 'Резервная связь'],
            ['code' => 'lifeboat', 'name' => 'Спасательная шлюпка', 'icon' => '🚣', 'category' => 'equipment', 'price' => 15000, 'weight' => 15, 'effect' => '+безопасность экипажа'],
            ['code' => 'anchor', 'name' => 'Запасной якорь', 'icon' => '⚓', 'category' => 'equipment', 'price' => 10000, 'weight' => 20, 'effect' => 'На случай потери'],
            ['code' => 'lights', 'name' => 'Прожекторы', 'icon' => '🔦', 'category' => 'equipment', 'price' => 6000, 'weight' => 5, 'effect' => '+видимость ночью'],
            ['code' => 'heater', 'name' => 'Обогреватели', 'icon' => '🔥', 'category' => 'equipment', 'price' => 12000, 'weight' => 10, 'effect' => '+комфорт экипажа'],
            ['code' => 'gps', 'name' => 'GPS-навигатор', 'icon' => '📡', 'category' => 'equipment', 'price' => 20000, 'weight' => 1, 'effect' => '+точность навигации'],
            
            // Еда
            ['code' => 'bread', 'name' => 'Хлеб (запас)', 'icon' => '🍞', 'category' => 'food', 'price' => 3000, 'weight' => 10, 'effect' => '+5 дней еды', 'food_days' => 5],
            ['code' => 'meat', 'name' => 'Мясные консервы', 'icon' => '🥫', 'category' => 'food', 'price' => 8000, 'weight' => 15, 'effect' => '+10 дней еды', 'food_days' => 10],
            ['code' => 'fish', 'name' => 'Рыбные консервы', 'icon' => '🐟', 'category' => 'food', 'price' => 6000, 'weight' => 12, 'effect' => '+8 дней еды', 'food_days' => 8],
            ['code' => 'vegetables', 'name' => 'Овощи сушёные', 'icon' => '🥕', 'category' => 'food', 'price' => 4000, 'weight' => 8, 'effect' => '+6 дней еды', 'food_days' => 6],
            ['code' => 'water', 'name' => 'Питьевая вода', 'icon' => '💧', 'category' => 'food', 'price' => 2000, 'weight' => 20, 'effect' => '+7 дней воды', 'food_days' => 7],
            ['code' => 'tea', 'name' => 'Чай и кофе', 'icon' => '☕', 'category' => 'food', 'price' => 1500, 'weight' => 3, 'effect' => '+настроение', 'food_days' => 0],
            ['code' => 'chocolate', 'name' => 'Шоколад', 'icon' => '🍫', 'category' => 'food', 'price' => 2500, 'weight' => 5, 'effect' => '+энергия, +настроение', 'food_days' => 2],
            ['code' => 'vitamins', 'name' => 'Витамины', 'icon' => '💊', 'category' => 'food', 'price' => 5000, 'weight' => 1, 'effect' => '+здоровье экипажа', 'food_days' => 0],
            
            // Медикаменты
            ['code' => 'firstaid', 'name' => 'Аптечка', 'icon' => '🩹', 'category' => 'medicine', 'price' => 3000, 'weight' => 2, 'effect' => 'Базовая помощь'],
            ['code' => 'antibiotics', 'name' => 'Антибиотики', 'icon' => '💊', 'category' => 'medicine', 'price' => 8000, 'weight' => 1, 'effect' => 'Лечение инфекций'],
            ['code' => 'painkillers', 'name' => 'Обезболивающие', 'icon' => '💉', 'category' => 'medicine', 'price' => 4000, 'weight' => 1, 'effect' => 'Снятие боли'],
            ['code' => 'frostbite', 'name' => 'Мазь от обморожения', 'icon' => '🧴', 'category' => 'medicine', 'price' => 5000, 'weight' => 2, 'effect' => 'Лечение обморожений'],
        ];

        foreach ($equipment as $item) {
            if (!isset($item['food_days'])) $item['food_days'] = 0;
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
            DB::table('equipment_items')->insert($item);
        }

        // ═══════════════════════════════════════════════════════════════════
        // ПОДРАБОТКИ
        // ═══════════════════════════════════════════════════════════════════
        $jobs = [
            ['code' => 'job1', 'title' => 'Доставка ящиков в Архангельск', 'icon' => '📦', 'description' => 'Перевезти 20 ящиков с оборудованием для метеостанции', 'reward' => 25000, 'risk_level' => 'low', 'risk_value' => 1, 'requirement' => 'Свободное место в трюме', 'cargo_weight' => 15, 'duration' => '+ 0 дней'],
            ['code' => 'job2', 'title' => 'Перевозка замороженной рыбы', 'icon' => '🐟', 'description' => 'Доставить партию свежемороженой трески в Диксон', 'reward' => 40000, 'risk_level' => 'medium', 'risk_value' => 2, 'requirement' => 'Нужен холодильник на борту', 'cargo_weight' => 25, 'duration' => '+ 1 день'],
            ['code' => 'job3', 'title' => 'Установка буёв', 'icon' => '📡', 'description' => 'Установить навигационный буй в точке маршрута', 'reward' => 15000, 'risk_level' => 'low', 'risk_value' => 1, 'requirement' => 'Нужен водолаз', 'cargo_weight' => 5, 'duration' => '+ 0.5 дня'],
            ['code' => 'job4', 'title' => 'Перевозка учёных', 'icon' => '🔬', 'description' => 'Взять на борт группу исследователей до острова Диксон', 'reward' => 35000, 'risk_level' => 'low', 'risk_value' => 1, 'requirement' => 'Свободные каюты', 'cargo_weight' => 0, 'duration' => '+ 2 дня остановка'],
            ['code' => 'job5', 'title' => 'Срочная почта', 'icon' => '✉️', 'description' => 'Доставить важные документы на остров Врангеля', 'reward' => 20000, 'risk_level' => 'high', 'risk_value' => 3, 'requirement' => 'Быстрый корабль', 'cargo_weight' => 1, 'duration' => 'Срок ограничен!'],
        ];

        foreach ($jobs as $job) {
            $job['created_at'] = $now;
            $job['updated_at'] = $now;
            DB::table('job_offers')->insert($job);
        }

        // ═══════════════════════════════════════════════════════════════════
        // БОНУСЫ
        // ═══════════════════════════════════════════════════════════════════
        $bonuses = [
            ['code' => 'bonus_1toy', 'name' => 'Первая игрушка', 'description' => 'Бонус 10 000 рублей за активацию первой игрушки', 'type' => 'money_bonus', 'resource_type' => 'money', 'value' => 10000, 'required_toys' => 1, 'is_permanent' => false],
            ['code' => 'bonus_2toys', 'name' => 'Два друга', 'description' => 'Бонус 25 000 рублей за активацию двух игрушек', 'type' => 'money_bonus', 'resource_type' => 'money', 'value' => 25000, 'required_toys' => 2, 'is_permanent' => false],
            ['code' => 'infinite_fuel', 'name' => 'Бесконечное топливо', 'description' => 'Топливо никогда не заканчивается', 'type' => 'infinite_resource', 'resource_type' => 'fuel', 'value' => 0, 'required_toys' => 3, 'is_permanent' => true],
            ['code' => 'bonus_4toys', 'name' => 'Полная команда', 'description' => 'Бонус 50 000 рублей и -10% зарплаты', 'type' => 'money_bonus', 'resource_type' => 'money', 'value' => 50000, 'required_toys' => 4, 'is_permanent' => false],
            ['code' => 'infinite_food', 'name' => 'Бесконечная еда', 'description' => 'Еда никогда не заканчивается', 'type' => 'infinite_resource', 'resource_type' => 'food', 'value' => 0, 'required_toys' => 5, 'is_permanent' => true],
        ];

        foreach ($bonuses as $bonus) {
            $bonus['created_at'] = $now;
            $bonus['updated_at'] = $now;
            DB::table('game_bonuses')->insert($bonus);
        }
    }
}
