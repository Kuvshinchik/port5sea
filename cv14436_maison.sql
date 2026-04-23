-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 23 2026 г., 15:32
-- Версия сервера: 10.4.19-MariaDB
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cv14436_maison`
--

-- --------------------------------------------------------

--
-- Структура таблицы `crew_roles`
--

CREATE TABLE `crew_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `base_salary` int(11) NOT NULL DEFAULT 1000,
  `max_count` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `crew_roles`
--

INSERT INTO `crew_roles` (`id`, `code`, `name`, `icon`, `is_required`, `base_salary`, `max_count`, `created_at`, `updated_at`) VALUES
(1, 'captain', 'Капитан', '👨‍✈️', 1, 2000, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 'first_mate', 'Помощник капитана', '🧭', 0, 1800, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 'mechanic', 'Механик', '🔧', 1, 1500, 2, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 'doctor', 'Врач', '👨‍⚕️', 1, 1800, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 'cook', 'Кок', '👨‍🍳', 1, 1000, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(6, 'navigator', 'Штурман', '🧭', 1, 1400, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(7, 'radioman', 'Радист', '📻', 1, 1100, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(8, 'sailor', 'Матрос', '⚓', 0, 800, 5, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(9, 'boatswain', 'Боцман', '🚢', 0, 1300, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(10, 'lookout', 'Впередсмотрящий', '👁️', 0, 900, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(11, 'pilot', 'Лоцман', '🗺️', 0, 1600, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(12, 'diver', 'Водолаз', '🤿', 0, 1300, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(13, 'scientist', 'Учёный', '🔬', 0, 1600, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `default_crew_members`
--

CREATE TABLE `default_crew_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `crew_role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skill_level` int(11) NOT NULL DEFAULT 2,
  `trait` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trait_effect` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portrait_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3498db',
  `salary` int(11) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `default_crew_members`
--

INSERT INTO `default_crew_members` (`id`, `code`, `crew_role_id`, `name`, `skill_level`, `trait`, `trait_effect`, `quote`, `portrait_color`, `salary`, `is_required`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'captain', 1, 'Иван Северов', 3, 'Опытный навигатор', '+20% к скорости во льдах', 'Я проведу наш корабль через любые льды!', '#3498db', 2000, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 'mechanic1', 3, 'Пётр Гаечкин', 3, 'Экономит топливо', '-15% расход топлива', 'Я умею чинить двигатель прямо во льдах!', '#e67e22', 1500, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 'mechanic2', 3, 'Анна Болтова', 2, 'Быстрый ремонт', '-30% время ремонта', 'Дайте мне гаечный ключ — и корабль будет как новый!', '#9b59b6', 1200, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 'doctor', 4, 'Елена Айболитова', 3, 'Профилактика болезней', '-50% шанс болезней', 'Здоровье команды — моя главная забота!', '#1abc9c', 1800, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 'cook', 5, 'Борис Поваров', 2, 'Экономная готовка', '-20% расход еды', 'Накормлю всю команду так, что пальчики оближете!', '#f39c12', 1000, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(6, 'navigator', 6, 'Ольга Компасова', 2, 'Знание маршрутов', '+15% к навигации', 'Северный морской путь — моя вторая родина!', '#e74c3c', 1400, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(7, 'radioman', 7, 'Сергей Волнов', 2, 'Связь с берегом', '+погодные предупреждения', 'Всегда на связи, в любую погоду!', '#2980b9', 1100, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(8, 'sailor1', 8, 'Николай Морской', 1, 'Крепкий здоровьем', '+устойчивость к холоду', 'Готов к любой работе на палубе!', '#34495e', 800, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(9, 'sailor2', 8, 'Мария Якорева', 2, 'Ловкая', '+скорость при швартовке', 'Женщина на корабле — к удаче!', '#8e44ad', 800, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(10, 'sailor3', 8, 'Алексей Канатов', 1, 'Боится льдов', '-настроение во льдах', 'Надеюсь, льдов будет не слишком много...', '#7f8c8d', 700, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(11, 'scientist', 13, 'Виктор Наукин', 3, 'Исследователь', '+бонусы за открытия', 'Каждая экспедиция — это новые открытия!', '#16a085', 1600, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(12, 'diver', 12, 'Дмитрий Глубинов', 2, 'Подводный ремонт', '+ремонт без дока', 'Под водой — как дома!', '#0984e3', 1300, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_items`
--

CREATE TABLE `equipment_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `effect` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `food_days` int(11) NOT NULL DEFAULT 0,
  `special_effects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`special_effects`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `equipment_items`
--

INSERT INTO `equipment_items` (`id`, `code`, `name`, `icon`, `category`, `price`, `weight`, `effect`, `food_days`, `special_effects`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'tools', 'Набор инструментов', '🧰', 'equipment', 5000, 5, '+10% к ремонту', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 'rope', 'Канаты (комплект)', '🪢', 'equipment', 2000, 3, 'Необходимо для швартовки', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 'radio', 'Запасная рация', '📻', 'equipment', 8000, 2, 'Резервная связь', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 'lifeboat', 'Спасательная шлюпка', '🚣', 'equipment', 15000, 15, '+безопасность экипажа', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 'anchor', 'Запасной якорь', '⚓', 'equipment', 10000, 20, 'На случай потери', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(6, 'lights', 'Прожекторы', '🔦', 'equipment', 6000, 5, '+видимость ночью', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(7, 'heater', 'Обогреватели', '🔥', 'equipment', 12000, 10, '+комфорт экипажа', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(8, 'gps', 'GPS-навигатор', '📡', 'equipment', 20000, 1, '+точность навигации', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(9, 'bread', 'Хлеб (запас)', '🍞', 'food', 3000, 10, '+5 дней еды', 5, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(10, 'meat', 'Мясные консервы', '🥫', 'food', 8000, 15, '+10 дней еды', 10, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(11, 'fish', 'Рыбные консервы', '🐟', 'food', 6000, 12, '+8 дней еды', 8, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(12, 'vegetables', 'Овощи сушёные', '🥕', 'food', 4000, 8, '+6 дней еды', 6, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(13, 'water', 'Питьевая вода', '💧', 'food', 2000, 20, '+7 дней воды', 7, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(14, 'tea', 'Чай и кофе', '☕', 'food', 1500, 3, '+настроение', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(15, 'chocolate', 'Шоколад', '🍫', 'food', 2500, 5, '+энергия, +настроение', 2, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(16, 'vitamins', 'Витамины', '💊', 'food', 5000, 1, '+здоровье экипажа', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(17, 'firstaid', 'Аптечка', '🩹', 'medicine', 3000, 2, 'Базовая помощь', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(18, 'antibiotics', 'Антибиотики', '💊', 'medicine', 8000, 1, 'Лечение инфекций', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(19, 'painkillers', 'Обезболивающие', '💉', 'medicine', 4000, 1, 'Снятие боли', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(20, 'frostbite', 'Мазь от обморожения', '🧴', 'medicine', 5000, 2, 'Лечение обморожений', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `game_bonuses`
--

CREATE TABLE `game_bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `required_toys` int(11) NOT NULL DEFAULT 1,
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conditions`)),
  `is_permanent` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_bonuses`
--

INSERT INTO `game_bonuses` (`id`, `code`, `name`, `description`, `type`, `resource_type`, `value`, `required_toys`, `conditions`, `is_permanent`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'bonus_1toy', 'Первая игрушка', 'Бонус 10 000 рублей за активацию первой игрушки', 'money_bonus', 'money', 10000, 1, NULL, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 'bonus_2toys', 'Два друга', 'Бонус 25 000 рублей за активацию двух игрушек', 'money_bonus', 'money', 25000, 2, NULL, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 'infinite_fuel', 'Бесконечное топливо', 'Топливо никогда не заканчивается', 'infinite_resource', 'fuel', 0, 3, NULL, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 'bonus_4toys', 'Полная команда', 'Бонус 50 000 рублей и -10% зарплаты', 'money_bonus', 'money', 50000, 4, NULL, 0, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 'infinite_food', 'Бесконечная еда', 'Еда никогда не заканчивается', 'infinite_resource', 'food', 0, 5, NULL, 1, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `game_completed_tasks`
--

CREATE TABLE `game_completed_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `stop_task_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `reward_received` int(11) NOT NULL DEFAULT 0,
  `task_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`task_data`)),
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `game_crew`
--

CREATE TABLE `game_crew` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `toy_character_id` bigint(20) UNSIGNED DEFAULT NULL,
  `default_crew_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crew_role_id` bigint(20) UNSIGNED NOT NULL,
  `current_salary` int(11) NOT NULL,
  `health` int(11) NOT NULL DEFAULT 100,
  `morale` int(11) NOT NULL DEFAULT 100,
  `is_from_toy` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_crew`
--

INSERT INTO `game_crew` (`id`, `game_progress_id`, `toy_character_id`, `default_crew_code`, `crew_role_id`, `current_salary`, `health`, `morale`, `is_from_toy`, `created_at`, `updated_at`) VALUES
(22, 3, NULL, 'captain', 1, 2000, 100, 100, 0, '2026-01-27 14:12:20', '2026-01-27 14:12:20'),
(23, 3, NULL, 'cook', 5, 1000, 100, 100, 0, '2026-01-27 14:12:21', '2026-01-27 14:12:21'),
(24, 3, NULL, 'radioman', 7, 1100, 100, 100, 0, '2026-01-27 14:12:22', '2026-01-27 14:12:22'),
(25, 3, NULL, 'navigator', 6, 1400, 100, 100, 0, '2026-01-27 14:12:24', '2026-01-27 14:12:24'),
(26, 3, NULL, 'doctor', 4, 1800, 100, 100, 0, '2026-01-27 14:12:25', '2026-01-27 14:12:25'),
(27, 3, NULL, 'mechanic1', 3, 1500, 100, 100, 0, '2026-01-27 14:12:26', '2026-01-27 14:12:26');

-- --------------------------------------------------------

--
-- Структура таблицы `game_equipment`
--

CREATE TABLE `game_equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `purchase_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_equipment`
--

INSERT INTO `game_equipment` (`id`, `game_progress_id`, `equipment_item_id`, `quantity`, `purchase_price`, `created_at`, `updated_at`) VALUES
(21, 3, 5, 1, 10000, '2026-01-27 14:12:33', '2026-01-27 14:12:33'),
(22, 3, 3, 1, 8000, '2026-01-27 14:12:35', '2026-01-27 14:12:35'),
(23, 3, 4, 1, 15000, '2026-01-27 14:12:37', '2026-01-27 14:12:37'),
(24, 3, 9, 1, 3000, '2026-01-27 14:12:41', '2026-01-27 14:12:41'),
(25, 3, 11, 1, 6000, '2026-01-27 14:12:42', '2026-01-27 14:12:42');

-- --------------------------------------------------------

--
-- Структура таблицы `game_event_logs`
--

CREATE TABLE `game_event_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_data`)),
  `money_change` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_event_logs`
--

INSERT INTO `game_event_logs` (`id`, `game_progress_id`, `event_type`, `description`, `event_data`, `money_change`, `created_at`, `updated_at`) VALUES
(67, 3, 'hire', 'Нанят Иван Северов', '{\"member_id\":\"captain\",\"salary\":2000}', 0, '2026-01-27 14:12:20', '2026-01-27 14:12:20'),
(68, 3, 'hire', 'Нанят Борис Поваров', '{\"member_id\":\"cook\",\"salary\":1000}', 0, '2026-01-27 14:12:21', '2026-01-27 14:12:21'),
(69, 3, 'hire', 'Нанят Сергей Волнов', '{\"member_id\":\"radioman\",\"salary\":1100}', 0, '2026-01-27 14:12:23', '2026-01-27 14:12:23'),
(70, 3, 'hire', 'Нанят Ольга Компасова', '{\"member_id\":\"navigator\",\"salary\":1400}', 0, '2026-01-27 14:12:24', '2026-01-27 14:12:24'),
(71, 3, 'hire', 'Нанят Елена Айболитова', '{\"member_id\":\"doctor\",\"salary\":1800}', 0, '2026-01-27 14:12:25', '2026-01-27 14:12:25'),
(72, 3, 'hire', 'Нанят Пётр Гаечкин', '{\"member_id\":\"mechanic1\",\"salary\":1500}', 0, '2026-01-27 14:12:26', '2026-01-27 14:12:26'),
(73, 3, 'spend', 'Покупка: Запасной якорь', '{\"amount\":10000}', -10000, '2026-01-27 14:12:33', '2026-01-27 14:12:33'),
(74, 3, 'spend', 'Покупка: Запасная рация', '{\"amount\":8000}', -8000, '2026-01-27 14:12:35', '2026-01-27 14:12:35'),
(75, 3, 'spend', 'Покупка: Спасательная шлюпка', '{\"amount\":15000}', -15000, '2026-01-27 14:12:37', '2026-01-27 14:12:37'),
(76, 3, 'spend', 'Покупка: Хлеб (запас)', '{\"amount\":3000}', -3000, '2026-01-27 14:12:41', '2026-01-27 14:12:41'),
(77, 3, 'spend', 'Покупка: Рыбные консервы', '{\"amount\":6000}', -6000, '2026-01-27 14:12:42', '2026-01-27 14:12:42'),
(78, 3, 'job_accept', 'Принята подработка: Перевозка учёных', '{\"job_code\":\"job4\",\"reward\":35000}', 0, '2026-01-27 14:12:47', '2026-01-27 14:12:47'),
(79, 3, 'depart', 'Экспедиция отправилась в путь!', '[]', 0, '2026-01-27 14:12:55', '2026-01-27 14:12:55'),
(80, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-01-27 14:13:03', '2026-01-27 14:13:03'),
(81, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":2}', 0, '2026-01-27 14:13:16', '2026-01-27 14:13:16'),
(82, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":12900}', 12900, '2026-01-27 14:14:16', '2026-01-27 14:14:16'),
(83, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1290,\"food_bonus\":5,\"money_bonus\":12900}', 0, '2026-01-27 14:14:16', '2026-01-27 14:14:16'),
(84, 3, 'debug_teleport', 'Отладка: телепорт к остановке «Мурманск»', '[]', 0, '2026-01-27 14:15:04', '2026-01-27 14:15:04'),
(85, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-01-27 14:15:31', '2026-01-27 14:15:31'),
(86, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":3}', 0, '2026-01-27 14:16:50', '2026-01-27 14:16:50'),
(87, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":4}', 0, '2026-01-27 14:39:31', '2026-01-27 14:39:31'),
(88, 3, 'mini_game_failed', 'Мини-игра не пройдена', '{\"score\":0,\"mollusks\":0,\"required\":15}', 0, '2026-01-27 14:40:31', '2026-01-27 14:40:31'),
(89, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":5}', 0, '2026-01-27 14:48:43', '2026-01-27 14:48:43'),
(90, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":7600}', 7600, '2026-01-27 14:49:43', '2026-01-27 14:49:43'),
(91, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":760,\"food_bonus\":5,\"money_bonus\":7600}', 0, '2026-01-27 14:49:43', '2026-01-27 14:49:43'),
(92, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":6}', 0, '2026-01-27 15:26:10', '2026-01-27 15:26:10'),
(93, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":10800}', 10800, '2026-01-27 15:27:11', '2026-01-27 15:27:11'),
(94, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1080,\"food_bonus\":5,\"money_bonus\":10800}', 0, '2026-01-27 15:27:11', '2026-01-27 15:27:11'),
(95, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":7}', 0, '2026-02-01 13:52:49', '2026-02-01 13:52:49'),
(96, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":9400}', 9400, '2026-02-01 13:53:50', '2026-02-01 13:53:50'),
(97, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":940,\"food_bonus\":5,\"money_bonus\":9400}', 0, '2026-02-01 13:53:50', '2026-02-01 13:53:50'),
(98, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":8}', 0, '2026-02-01 13:54:23', '2026-02-01 13:54:23'),
(99, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":8600}', 8600, '2026-02-01 13:55:23', '2026-02-01 13:55:23'),
(100, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":860,\"food_bonus\":5,\"money_bonus\":8600}', 0, '2026-02-01 13:55:23', '2026-02-01 13:55:23'),
(101, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":9}', 0, '2026-02-01 14:01:16', '2026-02-01 14:01:16'),
(102, 3, 'mini_game_failed', 'Мини-игра не пройдена', '{\"score\":0,\"mollusks\":0,\"required\":15}', 0, '2026-02-01 14:02:16', '2026-02-01 14:02:16'),
(103, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":10}', 0, '2026-04-18 13:06:51', '2026-04-18 13:06:51'),
(104, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":10200}', 10200, '2026-04-18 13:07:51', '2026-04-18 13:07:51'),
(105, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1020,\"food_bonus\":5,\"money_bonus\":10200}', 0, '2026-04-18 13:07:51', '2026-04-18 13:07:51'),
(106, 3, 'debug_teleport', 'Отладка: телепорт к остановке «Мурманск»', '[]', 0, '2026-04-18 13:45:44', '2026-04-18 13:45:44'),
(107, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-18 13:46:06', '2026-04-18 13:46:06'),
(108, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":11}', 0, '2026-04-18 13:50:35', '2026-04-18 13:50:35'),
(109, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":7500}', 7500, '2026-04-18 13:51:35', '2026-04-18 13:51:35'),
(110, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":750,\"food_bonus\":5,\"money_bonus\":7500}', 0, '2026-04-18 13:51:35', '2026-04-18 13:51:35'),
(111, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":12}', 0, '2026-04-18 14:14:50', '2026-04-18 14:14:50'),
(112, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":10800}', 10800, '2026-04-18 14:15:51', '2026-04-18 14:15:51'),
(113, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1080,\"food_bonus\":5,\"money_bonus\":10800}', 0, '2026-04-18 14:15:51', '2026-04-18 14:15:51'),
(114, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":13}', 0, '2026-04-18 14:52:03', '2026-04-18 14:52:03'),
(115, 3, 'mini_game_failed', 'Мини-игра не пройдена', '{\"score\":0,\"mollusks\":0,\"required\":15}', 0, '2026-04-18 14:53:03', '2026-04-18 14:53:03'),
(116, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":14}', 0, '2026-04-18 15:02:55', '2026-04-18 15:02:55'),
(117, 3, 'mini_game_failed', 'Мини-игра не пройдена', '{\"score\":0,\"mollusks\":0,\"required\":15}', 0, '2026-04-18 15:03:55', '2026-04-18 15:03:55'),
(118, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":15}', 0, '2026-04-18 15:26:03', '2026-04-18 15:26:03'),
(119, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":13200}', 13200, '2026-04-18 15:27:03', '2026-04-18 15:27:03'),
(120, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1320,\"food_bonus\":5,\"money_bonus\":13200}', 0, '2026-04-18 15:27:03', '2026-04-18 15:27:03'),
(121, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":16}', 0, '2026-04-19 10:58:48', '2026-04-19 10:58:48'),
(122, 3, 'mini_game_failed', 'Мини-игра не пройдена', '{\"score\":0,\"mollusks\":0,\"required\":15}', 0, '2026-04-19 10:59:49', '2026-04-19 10:59:49'),
(123, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":17}', 0, '2026-04-19 11:01:20', '2026-04-19 11:01:20'),
(124, 3, 'mini_game_start', 'Начата мини-игра: Сбор моллюсков в Териберке', '{\"mini_game_id\":18}', 0, '2026-04-19 11:21:16', '2026-04-19 11:21:16'),
(125, 3, 'earn', 'Награда за сбор моллюсков', '{\"amount\":17600}', 17600, '2026-04-19 11:22:17', '2026-04-19 11:22:17'),
(126, 3, 'mini_game_complete', 'Мини-игра пройдена успешно!', '{\"score\":1760,\"food_bonus\":5,\"money_bonus\":17600}', 0, '2026-04-19 11:22:17', '2026-04-19 11:22:17'),
(127, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:49:52', '2026-04-20 15:49:52'),
(128, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:51:57', '2026-04-20 15:51:57'),
(129, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:52:04', '2026-04-20 15:52:04'),
(130, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:52:04', '2026-04-20 15:52:04'),
(131, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:52:15', '2026-04-20 15:52:15'),
(132, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:52:22', '2026-04-20 15:52:22'),
(133, 3, 'spend', 'Выплата зарплаты экипажу', '{\"amount\":8800}', -8800, '2026-04-20 15:52:22', '2026-04-20 15:52:22');

-- --------------------------------------------------------

--
-- Структура таблицы `game_jobs`
--

CREATE TABLE `game_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `job_offer_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('accepted','in_progress','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'accepted',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `actual_reward` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `game_progress`
--

CREATE TABLE `game_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `route_segment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'murmansk_anadyr',
  `current_stop_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_point_index` int(11) NOT NULL DEFAULT 0,
  `game_phase` enum('preparation','traveling','at_stop','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preparation',
  `money` int(11) NOT NULL DEFAULT 250000,
  `food_days` int(11) NOT NULL DEFAULT 0,
  `fuel_percent` int(11) NOT NULL DEFAULT 100,
  `cargo_used` int(11) NOT NULL DEFAULT 0,
  `cargo_capacity` int(11) NOT NULL DEFAULT 100,
  `morale` int(11) NOT NULL DEFAULT 100,
  `total_earned` int(11) NOT NULL DEFAULT 0,
  `total_spent` int(11) NOT NULL DEFAULT 0,
  `days_traveled` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_progress`
--

INSERT INTO `game_progress` (`id`, `user_id`, `route_segment`, `current_stop_id`, `current_point_index`, `game_phase`, `money`, `food_days`, `fuel_percent`, `cargo_used`, `cargo_capacity`, `morale`, `total_earned`, `total_spent`, `days_traveled`, `is_active`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(3, 1, 'murmansk_anadyr', 11, 10, 'completed', 228600, 63, 100, 59, 100, 100, 108600, 130000, 10, 1, '2026-01-27 13:40:43', '2026-04-23 08:01:43', '2026-01-27 13:40:43', '2026-04-23 08:01:43');

-- --------------------------------------------------------

--
-- Структура таблицы `job_offers`
--

CREATE TABLE `job_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward` int(11) NOT NULL,
  `risk_level` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `risk_value` int(11) NOT NULL DEFAULT 1,
  `requirement` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_weight` int(11) NOT NULL DEFAULT 0,
  `duration` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_stop_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `job_offers`
--

INSERT INTO `job_offers` (`id`, `code`, `title`, `icon`, `description`, `reward`, `risk_level`, `risk_value`, `requirement`, `cargo_weight`, `duration`, `destination_stop_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'job1', 'Доставка ящиков в Архангельск', '📦', 'Перевезти 20 ящиков с оборудованием для метеостанции', 25000, 'low', 1, 'Свободное место в трюме', 15, '+ 0 дней', NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 'job2', 'Перевозка замороженной рыбы', '🐟', 'Доставить партию свежемороженой трески в Диксон', 40000, 'medium', 2, 'Нужен холодильник на борту', 25, '+ 1 день', NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 'job3', 'Установка буёв', '📡', 'Установить навигационный буй в точке маршрута', 15000, 'low', 1, 'Нужен водолаз', 5, '+ 0.5 дня', NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 'job4', 'Перевозка учёных', '🔬', 'Взять на борт группу исследователей до острова Диксон', 35000, 'low', 1, 'Свободные каюты', 0, '+ 2 дня остановка', NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 'job5', 'Срочная почта', '✉️', 'Доставить важные документы на остров Врангеля', 20000, 'high', 3, 'Быстрый корабль', 1, 'Срок ограничен!', NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `kanin_cape_results`
--

CREATE TABLE `kanin_cape_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `stage_reached` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `time_spent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `mezon_domik`
--

CREATE TABLE `mezon_domik` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `text` text NOT NULL,
  `textSite` text NOT NULL,
  `articul` int(11) UNSIGNED NOT NULL,
  `category` varchar(40) NOT NULL,
  `yarmarka` varchar(500) NOT NULL,
  `price` int(9) UNSIGNED NOT NULL,
  `ostatok` int(3) UNSIGNED NOT NULL,
  `dostupno` int(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mezon_domik`
--

INSERT INTO `mezon_domik` (`id`, `name`, `text`, `textSite`, `articul`, `category`, `yarmarka`, `price`, `ostatok`, `dostupno`) VALUES
(1, 'Морж Боцман Жорж.', 'Суровый, но добродушный морж-боцман Жорж высотой 41 см — мастер на все ласты! Этот бравый морской волк командует волнами, чинит паруса и подмигивает вам с полки. Хватай, пока не уплыл!', '<p class=\"large\">Морж Боцман Жорж.</p>\n<p>Рост: 41 см.\n<br>\nМатериалы: авторская печать, хлопок, холлофайбер.\n<br>\n</p>', 1001, 'Интерьерная игрушка:::Для спальни', 'https://www.livemaster.ru/item/55063624-kukly-i-igrushki-interernaya-igrushka-morzh-botsman-zhorzh-41', 3150, 1000, 946),
(2, 'Кот Старпом Полоскин.', 'Кот Старпом Полоскин – старший помощник капитана. Невероятно важный член нашей полосатой команды! Его особая специальность – следить за порядком на корабле. Его опыт и умение помогают всей команде быть всегда на чеку и исполнять свою работу надлежащим образом. Он настоящий эксперт по всем морским специальностям!', '<p class=\"large\">Игрушка-Кот Старпом Полоскин.</p>\r\n<p>Размер - 40 см. \r\n<br>Материалы: хлопок, холлофайбер.\r\n</p>', 1002, 'Интерьерная игрушка:::Для спальни', 'https://www.livemaster.ru/item/55079940-kukly-i-igrushki-interernaya-igrushka-kot-starpom-poloskin-40', 3150, 1000, 947),
(3, 'Чайка Вперёдсмотрящий.', 'Чайка-впередсмотрящая – это не просто игрушка, это настоящая легенда полосатой команды! С её невероятно острым зрением ни один айсберг, ни один пиратский корабль (даже если он игрушечный!) не останется незамеченным.', '<p class=\"large\">Чайка белая.</p>\r\n<p>Размер - 24x32 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>\r\n', 1003, 'Интерьерная игрушка:::Для спальни', 'https://www.livemaster.ru/item/55077070-kukly-i-igrushki-interernaya-igrushka-chajka-vperedsmotryasch', 3100, 1000, 947),
(4, 'Пингвин Кок Макарони.', 'Наш Пингвин Макарони – это не просто кок, это настоящий кок-кулинар и душа всей полосатой команды! Он отвечает за самые вкусные и питательные блюда на корабле, превращая каждый приём пищи в маленький праздник. Макарони мастерски готовит из всего, что найдёт, будь то свежая рыба из океана или припасы, найденные на экзотических островах. Его фирменные блюда всегда поднимают настроение и дают команде силы для новых приключений, а сам Макарони всегда готов поделиться секретами своих кулинарных шедевров!', '<p class=\"large\">Пингвин Кок.</p>\r\n<p>Рост: 35 см\r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1004, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/40824886-kukly-i-igrushki-kok-pingvin-makaroni-35sm', 2000, 1000, 947),
(5, 'Медведь Капитан Арктика.', 'Эта мягкая игрушка гроза морей и полок, ростом 42 см! Этот суровый полярный командир одним строгим взглядом наводит порядок на корабле и в вашей комнате. Готов рулить волнами и вашим настроением — берите, пока не отдал швартовые!', '<p class=\"large\"Медведь Капитан.</p>\r\n<p>Рост 42 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1005, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/39648010-kukly-i-igrushki-medved-kapitan-arktika-42sm', 2500, 1000, 948),
(8, 'Арктический Заяц Матрос.', 'Арктический Заяц – это не просто матрос, это матрос-разведчик и самый быстрый член нашей полосатой команды! С его невероятной скоростью и ловкостью, Арктический Заяц незаменим в любой ситуации, где нужна молниеносная реакция. Он первый спрыгивает на берег, чтобы разведать обстановку, найти самые свежие ягоды для перекуса или обнаружить спрятанные сокровища. Его острый нюх и быстрые лапки всегда выручают команду, помогая избежать опасностей и исследовать новые, неизведанные уголки мира.', '<p class=\"large\">Арктический Заяц.</p>\r\n<p>Рост 45 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1008, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/41485254-kukly-i-igrushki-arkticheskij-zayats-45-sm', 2000, 1000, 947),
(10, 'Нерпа Лоцман Коля.', 'Нерпа Коля – это не просто член экипажа, это наш бесценный лоцман-навигатор! С его удивительной интуицией и безупречным знанием подводных течений, Нерпа с лёгкостью прокладывает путь через самые запутанные лабиринты льдов и туманов. Он чувствует малейшие изменения в воде, всегда знает, где находятся самые безопасные проходы и где можно встретить самых любопытных морских обитателей. Благодаря ей, наша полосатая команда никогда не сбивается с курса и всегда находит дорогу к новым приключениям, даже в самых непредсказуемых водах!', '<p class=\"large\">Нерпа Лоцман.</p>\r\n<p>Рост 41 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1010, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/55071124-kukly-i-igrushki-interernaya-igrushka-nerpa-lotsman-kolya-41s', 3350, 1000, 947),
(11, 'Тюлень Подводник Жак-Ив.', 'Это бесспорно самый таинственный и глубоководный член нашей полосатой команды, настоящий подводник-исследователь! Обладая исключительной способностью задерживать дыхание надолго и бесшумно скользить в толще воды, он — глаза и уши команды под водой. Жак-Ив с лёгкостью исследует затонувшие корабли, общается с морскими обитателями и находит самые диковинные сокровища, скрытые от посторонних глаз. Его бесстрашие и природная любознательность делают его незаменимым при изучении подводного мира, принося команде ценные сведения и невероятные открытия с морского дна.', '<p class=\"large\">Тюлень Подводник.</p>\r\n<p>Рост 41 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1011, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/55074298-kukly-i-igrushki-interernaya-igrushka-tyulen-podvodnik-zhak-i', 3350, 1000, 947),
(12, 'Помощник Капитана.', 'Наш Помощник Капитана, белый Мишка, — это не просто второй в команде, это правая лапа и надежная опора самого капитана Арктики! Обладая невероятной силой и непоколебимым спокойствием, он всегда готов взять на себя управление, когда капитан занят. Мишка мастерски решает любые сложности, будь то шторм в открытом море или запутанные морские узлы, и его мудрые советы бесценны для всей команды. Он — символ верности, надежности и готовности прийти на помощь в любой ситуации, делая каждое приключение безопасным и увлекательным!', '<p class=\"large\">Белый Мишка.</p>\r\n<p>Рост 40 см. \r\n<br>Материалы: авторская печать, хлопок, холлофайбер.\r\n</p>', 1012, 'Интерьерная игрушка', 'https://www.livemaster.ru/item/39647878-kukly-i-igrushki-belyj-mishka-marsel-40sm', 2000, 1000, 947);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_10_01_171450_create_telegrams_table', 1),
(6, '2025_08_27_155115_create_visitors_table', 2),
(7, '2025_08_30_191325_create_visits_table,', 3),
(8, '2026_01_13_175302_create_user_prize_points_table', 4),
(9, '2026_01_26_000001_create_ship_game_tables', 5),
(10, '2024_01_15_000001_create_teriberika_mini_game_results_table', 6),
(11, '2024_02_09_create_payments_table', 7),
(12, '2026_04_20_000001_create_kanin_cape_results_table', 8),
(13, '2026_04_20_000002_add_slug_and_icon_slug_to_route_stops_table', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `robokassa_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `route_stops`
--

CREATE TABLE `route_stops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_index` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_coord` int(11) NOT NULL,
  `y_coord` int(11) NOT NULL,
  `is_stop` tinyint(1) NOT NULL DEFAULT 1,
  `level_number` int(11) DEFAULT NULL,
  `route_segment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'murmansk_anadyr',
  `salary_days` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `route_stops`
--

INSERT INTO `route_stops` (`id`, `order_index`, `name`, `slug`, `icon_slug`, `x_coord`, `y_coord`, `is_stop`, `level_number`, `route_segment`, `salary_days`, `created_at`, `updated_at`) VALUES
(1, 0, 'Мурманск', NULL, NULL, 625, 462, 1, 1, 'murmansk_anadyr', 0, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 1, 'Переход', NULL, NULL, 639, 441, 0, NULL, 'murmansk_anadyr', 0, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 2, 'Териберка', NULL, NULL, 680, 466, 1, 2, 'murmansk_anadyr', 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 3, 'Канин мыс', NULL, NULL, 751, 478, 1, 3, 'murmansk_anadyr', 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 4, 'Остров Моржовский', NULL, NULL, 758, 534, 1, 4, 'murmansk_anadyr', 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(6, 5, 'Соловецкие острова', NULL, NULL, 664, 594, 1, 5, 'murmansk_anadyr', 2, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(7, 6, 'Переход', NULL, NULL, 631, 540, 0, NULL, 'murmansk_anadyr', 0, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(8, 7, 'Кандалакша', NULL, NULL, 615, 525, 1, 6, 'murmansk_anadyr', 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(9, 8, 'Переход', NULL, NULL, 631, 540, 0, NULL, 'murmansk_anadyr', 0, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(10, 9, 'Переход', NULL, NULL, 694, 576, 0, NULL, 'murmansk_anadyr', 0, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(11, 10, 'Архангельск', NULL, NULL, 738, 612, 1, 7, 'murmansk_anadyr', 2, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `stop_tasks`
--

CREATE TABLE `stop_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_stop_id` bigint(20) UNSIGNED NOT NULL,
  `task_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_in_stop` int(11) NOT NULL DEFAULT 1,
  `reward_money` int(11) NOT NULL DEFAULT 0,
  `reward_food_days` int(11) NOT NULL DEFAULT 0,
  `reward_fuel_percent` int(11) NOT NULL DEFAULT 0,
  `reward_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reward_items`)),
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `telegrams`
--

CREATE TABLE `telegrams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `teriberika_mini_game_results`
--

CREATE TABLE `teriberika_mini_game_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_progress_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `mollusks_collected` int(11) NOT NULL DEFAULT 0,
  `far_zone_collected` int(11) NOT NULL DEFAULT 0,
  `middle_zone_collected` int(11) NOT NULL DEFAULT 0,
  `near_zone_collected` int(11) NOT NULL DEFAULT 0,
  `crabs_clicked` int(11) NOT NULL DEFAULT 0,
  `time_played` int(11) NOT NULL DEFAULT 0,
  `has_seagull_bonus` tinyint(1) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_success` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `teriberika_mini_game_results`
--

INSERT INTO `teriberika_mini_game_results` (`id`, `game_progress_id`, `user_id`, `score`, `mollusks_collected`, `far_zone_collected`, `middle_zone_collected`, `near_zone_collected`, `crabs_clicked`, `time_played`, `has_seagull_bonus`, `is_completed`, `is_success`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(18, 3, 1, 1760, 69, 12, 27, 30, 2, 60, 0, 1, 1, '2026-04-19 11:21:16', '2026-04-19 11:22:17', '2026-04-19 11:21:16', '2026-04-19 11:22:17');

-- --------------------------------------------------------

--
-- Структура таблицы `toy_characters`
--

CREATE TABLE `toy_characters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `toy_id` int(10) UNSIGNED NOT NULL,
  `crew_role_id` bigint(20) UNSIGNED NOT NULL,
  `character_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skill_level` int(11) NOT NULL DEFAULT 2,
  `trait` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trait_effect` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portrait_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3498db',
  `salary_modifier` int(11) NOT NULL DEFAULT 0,
  `special_abilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`special_abilities`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `toy_characters`
--

INSERT INTO `toy_characters` (`id`, `toy_id`, `crew_role_id`, `character_name`, `skill_level`, `trait`, `trait_effect`, `quote`, `avatar_path`, `portrait_color`, `salary_modifier`, `special_abilities`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Капитан Арктика', 3, 'Опытный навигатор', '+20% к скорости во льдах', 'Я проведу наш корабль через любые льды!', NULL, '#3498db', 500, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(2, 12, 2, 'Помощник Марсель', 3, 'Надёжная опора', '+15% к морали команды', 'Всегда готов прийти на помощь!', NULL, '#ecf0f1', 300, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(3, 1, 9, 'Боцман Жорж', 3, 'Мастер на все ласты', '+10% к ремонту, +10% к швартовке', 'Суровый, но добродушный - это про меня!', NULL, '#795548', 200, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(4, 2, 6, 'Штурман Полоскин', 3, 'Морской глаз', '+15% к навигации, -10% риск посадки на мель', 'Мой полосатый хвост чует курс!', NULL, '#ff9800', 400, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(5, 3, 10, 'Чайка-наблюдатель', 3, 'Острое зрение', 'Раннее обнаружение опасностей и островов', 'Ни один айсберг не останется незамеченным!', NULL, '#fafafa', 100, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(6, 4, 5, 'Кок Макарони', 3, 'Кулинар-виртуоз', '-25% расход еды, +10% мораль', 'Накормлю всю команду так, что пальчики оближете!', NULL, '#263238', 200, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(7, 8, 8, 'Матрос-разведчик', 2, 'Молниеносная реакция', '+20% скорость погрузки/разгрузки', 'Первый на берег, первый обратно!', NULL, '#e0e0e0', 0, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(8, 10, 11, 'Лоцман Коля', 3, 'Чутьё подводных течений', '+25% к навигации в сложных водах', 'Я чувствую каждое течение!', NULL, '#607d8b', 300, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53'),
(9, 11, 12, 'Подводник Жак-Ив', 3, 'Глубоководный исследователь', 'Подводный ремонт без дока, поиск сокровищ', 'Под водой — как дома!', NULL, '#455a64', 400, NULL, 1, '2026-01-26 13:16:53', '2026-01-26 13:16:53');

-- --------------------------------------------------------

--
-- Структура таблицы `toy_qr_codes`
--

CREATE TABLE `toy_qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `toy_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'toly', 'toly@toly.ru', NULL, '$2y$10$i7GxFmM.m3fVU8WJPuNIue69WL2Y.VnSyD6e54iu7Z3eSU0HLNGd2', 'ZNg2AVrJ6tduJsmQXYQ5L1u8sZeqgrTxp6vv3rBtXSdRLaLHzlU41ciVfdgC', '2026-01-13 17:49:18', '2026-01-13 17:49:18');

-- --------------------------------------------------------

--
-- Структура таблицы `user_bonuses`
--

CREATE TABLE `user_bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_bonus_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `activated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_prize_points`
--

CREATE TABLE `user_prize_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0,
  `last_spin_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_prize_points`
--

INSERT INTO `user_prize_points` (`id`, `user_id`, `balance`, `last_spin_at`, `created_at`, `updated_at`) VALUES
(2, 1, 134, '2026-04-19 14:53:18', '2026-04-19 14:51:25', '2026-04-19 14:53:18');

-- --------------------------------------------------------

--
-- Структура таблицы `user_toys`
--

CREATE TABLE `user_toys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `toy_character_id` bigint(20) UNSIGNED NOT NULL,
  `qr_code_id` bigint(20) UNSIGNED NOT NULL,
  `bonus_money` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `visitors`
--

CREATE TABLE `visitors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visitors`
--

INSERT INTO `visitors` (`id`, `ip`, `date`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, '12ca17b49af2289436f303e0166030a21e525d266e209267433801a8fd4071a0', '2025-08-27', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-27 13:04:31', '2025-08-27 13:04:31');

-- --------------------------------------------------------

--
-- Структура таблицы `visits`
--

CREATE TABLE `visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visits`
--

INSERT INTO `visits` (`id`, `ip`, `visit_date`, `created_at`, `updated_at`) VALUES
(1, '127.0.0.1', '2025-08-30', '2025-08-30 16:23:57', '2025-08-30 16:23:57'),
(2, '127.0.0.1', '2025-11-03', '2025-11-03 12:54:34', '2025-11-03 12:54:34'),
(3, '127.0.0.1', '2026-01-12', '2026-01-12 12:24:09', '2026-01-12 12:24:09'),
(4, '127.0.0.1', '2026-01-13', '2026-01-13 15:46:32', '2026-01-13 15:46:32'),
(5, '127.0.0.1', '2026-01-14', '2026-01-14 12:03:10', '2026-01-14 12:03:10'),
(6, '127.0.0.1', '2026-01-15', '2026-01-14 21:56:16', '2026-01-14 21:56:16'),
(7, '127.0.0.1', '2026-01-19', '2026-01-18 22:07:20', '2026-01-18 22:07:20'),
(8, '127.0.0.1', '2026-01-20', '2026-01-20 14:22:24', '2026-01-20 14:22:24'),
(9, '127.0.0.1', '2026-01-21', '2026-01-21 07:58:30', '2026-01-21 07:58:30'),
(10, '127.0.0.1', '2026-01-23', '2026-01-23 09:01:25', '2026-01-23 09:01:25'),
(11, '127.0.0.1', '2026-01-25', '2026-01-25 08:43:18', '2026-01-25 08:43:18'),
(12, '127.0.0.1', '2026-01-26', '2026-01-26 02:23:42', '2026-01-26 02:23:42'),
(13, '127.0.0.1', '2026-01-27', '2026-01-27 11:52:13', '2026-01-27 11:52:13'),
(14, '127.0.0.1', '2026-02-01', '2026-02-01 13:49:18', '2026-02-01 13:49:18'),
(15, '127.0.0.1', '2026-02-06', '2026-02-06 12:34:38', '2026-02-06 12:34:38'),
(16, '127.0.0.1', '2026-02-08', '2026-02-08 13:11:28', '2026-02-08 13:11:28'),
(17, '127.0.0.1', '2026-02-09', '2026-02-09 11:20:48', '2026-02-09 11:20:48'),
(18, '127.0.0.1', '2026-02-10', '2026-02-10 07:31:58', '2026-02-10 07:31:58'),
(19, '127.0.0.1', '2026-02-18', '2026-02-18 16:00:24', '2026-02-18 16:00:24'),
(20, '127.0.0.1', '2026-04-18', '2026-04-18 11:01:14', '2026-04-18 11:01:14'),
(21, '127.0.0.1', '2026-04-19', '2026-04-19 10:44:10', '2026-04-19 10:44:10'),
(22, '127.0.0.1', '2026-04-20', '2026-04-20 14:44:53', '2026-04-20 14:44:53'),
(23, '127.0.0.1', '2026-04-23', '2026-04-23 07:17:12', '2026-04-23 07:17:12');

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--

CREATE TABLE `workers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `age` int(11) NOT NULL,
  `description` text NOT NULL,
  `is_married` tinyint(1) NOT NULL,
  `updated_at` text NOT NULL,
  `created_at` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `workers`
--

INSERT INTO `workers` (`id`, `name`, `surname`, `email`, `age`, `description`, `is_married`, `updated_at`, `created_at`) VALUES
(3, 'Ivan', 'Ivanov', 'ivanov@mail.ru', 20, 'Im Ivan', 1, '2023-10-13 10:50:24', '2023-10-09 08:29:41'),
(5, 'Mark', 'Markov', 'Markov@mail.ru', 20, 'Im Ivan', 1, '2023-10-13 10:50:11', '2023-10-09 10:07:31'),
(6, 'Серж', 'Пампеду', 'nav@dzvr.ru', 62, 'Здесь текст', 0, '2023-10-13 11:51:59', '2023-10-12 12:10:11'),
(8, 'Максим', 'Перепелица', 'perepelica@mail.com', 33, 'Перепелица - герой старого фильма начала 60-х прошлого века', 0, '2023-10-13 10:49:18', '2023-10-13 10:49:18'),
(9, 'Жорж', 'Жоржович', 'jorj@jorjovich.com', 22, 'текст 2', 1, '2023-10-13 11:57:25', '2023-10-13 11:57:25'),
(10, 'Кирилл', 'Ковров', 'kovrov@kirill.com', 35, 'Кимарик - звала его в детстве бабушка', 1, '2023-10-18 09:49:30', '2023-10-18 09:49:30'),
(11, 'Лукьянова', 'Дарья', 'luka@perdisheva.com', 47, 'Родом из Хацапетовки', 0, '2023-10-18 09:50:52', '2023-10-18 09:50:52');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `crew_roles`
--
ALTER TABLE `crew_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `crew_roles_code_unique` (`code`);

--
-- Индексы таблицы `default_crew_members`
--
ALTER TABLE `default_crew_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `default_crew_members_code_unique` (`code`),
  ADD KEY `default_crew_members_crew_role_id_foreign` (`crew_role_id`);

--
-- Индексы таблицы `equipment_items`
--
ALTER TABLE `equipment_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipment_items_code_unique` (`code`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `game_bonuses`
--
ALTER TABLE `game_bonuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `game_bonuses_code_unique` (`code`);

--
-- Индексы таблицы `game_completed_tasks`
--
ALTER TABLE `game_completed_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `game_completed_tasks_game_progress_id_stop_task_id_unique` (`game_progress_id`,`stop_task_id`),
  ADD KEY `game_completed_tasks_stop_task_id_foreign` (`stop_task_id`);

--
-- Индексы таблицы `game_crew`
--
ALTER TABLE `game_crew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_crew_toy_character_id_foreign` (`toy_character_id`),
  ADD KEY `game_crew_crew_role_id_foreign` (`crew_role_id`),
  ADD KEY `game_crew_game_progress_id_index` (`game_progress_id`);

--
-- Индексы таблицы `game_equipment`
--
ALTER TABLE `game_equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `game_equipment_game_progress_id_equipment_item_id_unique` (`game_progress_id`,`equipment_item_id`),
  ADD KEY `game_equipment_equipment_item_id_foreign` (`equipment_item_id`);

--
-- Индексы таблицы `game_event_logs`
--
ALTER TABLE `game_event_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_event_logs_game_progress_id_event_type_index` (`game_progress_id`,`event_type`);

--
-- Индексы таблицы `game_jobs`
--
ALTER TABLE `game_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_jobs_job_offer_id_foreign` (`job_offer_id`),
  ADD KEY `game_jobs_game_progress_id_status_index` (`game_progress_id`,`status`);

--
-- Индексы таблицы `game_progress`
--
ALTER TABLE `game_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `game_progress_user_id_route_segment_is_active_unique` (`user_id`,`route_segment`,`is_active`),
  ADD KEY `game_progress_current_stop_id_foreign` (`current_stop_id`);

--
-- Индексы таблицы `job_offers`
--
ALTER TABLE `job_offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_offers_code_unique` (`code`),
  ADD KEY `job_offers_destination_stop_id_foreign` (`destination_stop_id`);

--
-- Индексы таблицы `kanin_cape_results`
--
ALTER TABLE `kanin_cape_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kanin_cape_results_user_id_is_completed_index` (`user_id`,`is_completed`),
  ADD KEY `kanin_cape_results_game_progress_id_stage_reached_index` (`game_progress_id`,`stage_reached`);

--
-- Индексы таблицы `mezon_domik`
--
ALTER TABLE `mezon_domik`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_order_id_unique` (`order_id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `route_stops`
--
ALTER TABLE `route_stops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_stops_route_segment_order_index_unique` (`route_segment`,`order_index`),
  ADD UNIQUE KEY `route_stops_route_segment_slug_unique` (`route_segment`,`slug`);

--
-- Индексы таблицы `stop_tasks`
--
ALTER TABLE `stop_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stop_tasks_route_stop_id_foreign` (`route_stop_id`);

--
-- Индексы таблицы `telegrams`
--
ALTER TABLE `telegrams`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teriberika_mini_game_results`
--
ALTER TABLE `teriberika_mini_game_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teriberika_mini_game_results_user_id_is_completed_index` (`user_id`,`is_completed`),
  ADD KEY `teriberika_mini_game_results_game_progress_id_is_success_index` (`game_progress_id`,`is_success`),
  ADD KEY `teriberika_mini_game_results_score_index` (`score`);

--
-- Индексы таблицы `toy_characters`
--
ALTER TABLE `toy_characters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `toy_characters_toy_id_unique` (`toy_id`),
  ADD KEY `toy_characters_crew_role_id_foreign` (`crew_role_id`);

--
-- Индексы таблицы `toy_qr_codes`
--
ALTER TABLE `toy_qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `toy_qr_codes_code_unique` (`code`),
  ADD KEY `toy_qr_codes_user_id_foreign` (`user_id`),
  ADD KEY `toy_qr_codes_toy_id_is_used_index` (`toy_id`,`is_used`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `user_bonuses`
--
ALTER TABLE `user_bonuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_bonuses_user_id_game_bonus_id_unique` (`user_id`,`game_bonus_id`),
  ADD KEY `user_bonuses_game_bonus_id_foreign` (`game_bonus_id`);

--
-- Индексы таблицы `user_prize_points`
--
ALTER TABLE `user_prize_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_prize_points_user_id_unique` (`user_id`);

--
-- Индексы таблицы `user_toys`
--
ALTER TABLE `user_toys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_toys_user_id_toy_character_id_unique` (`user_id`,`toy_character_id`),
  ADD KEY `user_toys_toy_character_id_foreign` (`toy_character_id`),
  ADD KEY `user_toys_qr_code_id_foreign` (`qr_code_id`);

--
-- Индексы таблицы `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `visitors_ip_date_unique` (`ip`,`date`);

--
-- Индексы таблицы `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `visits_ip_visit_date_unique` (`ip`,`visit_date`);

--
-- Индексы таблицы `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `crew_roles`
--
ALTER TABLE `crew_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `default_crew_members`
--
ALTER TABLE `default_crew_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `equipment_items`
--
ALTER TABLE `equipment_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `game_bonuses`
--
ALTER TABLE `game_bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `game_completed_tasks`
--
ALTER TABLE `game_completed_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `game_crew`
--
ALTER TABLE `game_crew`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `game_equipment`
--
ALTER TABLE `game_equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `game_event_logs`
--
ALTER TABLE `game_event_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT для таблицы `game_jobs`
--
ALTER TABLE `game_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `game_progress`
--
ALTER TABLE `game_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `job_offers`
--
ALTER TABLE `job_offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `kanin_cape_results`
--
ALTER TABLE `kanin_cape_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mezon_domik`
--
ALTER TABLE `mezon_domik`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `route_stops`
--
ALTER TABLE `route_stops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `stop_tasks`
--
ALTER TABLE `stop_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `telegrams`
--
ALTER TABLE `telegrams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teriberika_mini_game_results`
--
ALTER TABLE `teriberika_mini_game_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `toy_characters`
--
ALTER TABLE `toy_characters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `toy_qr_codes`
--
ALTER TABLE `toy_qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user_bonuses`
--
ALTER TABLE `user_bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_prize_points`
--
ALTER TABLE `user_prize_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user_toys`
--
ALTER TABLE `user_toys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `workers`
--
ALTER TABLE `workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `default_crew_members`
--
ALTER TABLE `default_crew_members`
  ADD CONSTRAINT `default_crew_members_crew_role_id_foreign` FOREIGN KEY (`crew_role_id`) REFERENCES `crew_roles` (`id`);

--
-- Ограничения внешнего ключа таблицы `game_completed_tasks`
--
ALTER TABLE `game_completed_tasks`
  ADD CONSTRAINT `game_completed_tasks_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_completed_tasks_stop_task_id_foreign` FOREIGN KEY (`stop_task_id`) REFERENCES `stop_tasks` (`id`);

--
-- Ограничения внешнего ключа таблицы `game_crew`
--
ALTER TABLE `game_crew`
  ADD CONSTRAINT `game_crew_crew_role_id_foreign` FOREIGN KEY (`crew_role_id`) REFERENCES `crew_roles` (`id`),
  ADD CONSTRAINT `game_crew_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_crew_toy_character_id_foreign` FOREIGN KEY (`toy_character_id`) REFERENCES `toy_characters` (`id`);

--
-- Ограничения внешнего ключа таблицы `game_equipment`
--
ALTER TABLE `game_equipment`
  ADD CONSTRAINT `game_equipment_equipment_item_id_foreign` FOREIGN KEY (`equipment_item_id`) REFERENCES `equipment_items` (`id`),
  ADD CONSTRAINT `game_equipment_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `game_event_logs`
--
ALTER TABLE `game_event_logs`
  ADD CONSTRAINT `game_event_logs_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `game_jobs`
--
ALTER TABLE `game_jobs`
  ADD CONSTRAINT `game_jobs_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_jobs_job_offer_id_foreign` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offers` (`id`);

--
-- Ограничения внешнего ключа таблицы `game_progress`
--
ALTER TABLE `game_progress`
  ADD CONSTRAINT `game_progress_current_stop_id_foreign` FOREIGN KEY (`current_stop_id`) REFERENCES `route_stops` (`id`),
  ADD CONSTRAINT `game_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `job_offers`
--
ALTER TABLE `job_offers`
  ADD CONSTRAINT `job_offers_destination_stop_id_foreign` FOREIGN KEY (`destination_stop_id`) REFERENCES `route_stops` (`id`);

--
-- Ограничения внешнего ключа таблицы `kanin_cape_results`
--
ALTER TABLE `kanin_cape_results`
  ADD CONSTRAINT `kanin_cape_results_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kanin_cape_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `stop_tasks`
--
ALTER TABLE `stop_tasks`
  ADD CONSTRAINT `stop_tasks_route_stop_id_foreign` FOREIGN KEY (`route_stop_id`) REFERENCES `route_stops` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `teriberika_mini_game_results`
--
ALTER TABLE `teriberika_mini_game_results`
  ADD CONSTRAINT `teriberika_mini_game_results_game_progress_id_foreign` FOREIGN KEY (`game_progress_id`) REFERENCES `game_progress` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teriberika_mini_game_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `toy_characters`
--
ALTER TABLE `toy_characters`
  ADD CONSTRAINT `toy_characters_crew_role_id_foreign` FOREIGN KEY (`crew_role_id`) REFERENCES `crew_roles` (`id`);

--
-- Ограничения внешнего ключа таблицы `toy_qr_codes`
--
ALTER TABLE `toy_qr_codes`
  ADD CONSTRAINT `toy_qr_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `user_bonuses`
--
ALTER TABLE `user_bonuses`
  ADD CONSTRAINT `user_bonuses_game_bonus_id_foreign` FOREIGN KEY (`game_bonus_id`) REFERENCES `game_bonuses` (`id`),
  ADD CONSTRAINT `user_bonuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_prize_points`
--
ALTER TABLE `user_prize_points`
  ADD CONSTRAINT `user_prize_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_toys`
--
ALTER TABLE `user_toys`
  ADD CONSTRAINT `user_toys_qr_code_id_foreign` FOREIGN KEY (`qr_code_id`) REFERENCES `toy_qr_codes` (`id`),
  ADD CONSTRAINT `user_toys_toy_character_id_foreign` FOREIGN KEY (`toy_character_id`) REFERENCES `toy_characters` (`id`),
  ADD CONSTRAINT `user_toys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
