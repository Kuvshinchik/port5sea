<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Создание всех таблиц для игры "Путешествие по Северному морскому пути"
     */
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ПРОФЕССИЙ КОМАНДЫ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('crew_roles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // captain, mechanic, doctor, cook, navigator, radioman, sailor, scientist, diver
            $table->string('name', 100); // Капитан, Механик, Врач...
            $table->string('icon', 20); // Эмодзи иконка
            $table->boolean('is_required')->default(false); // Обязательная роль для отплытия
            $table->integer('base_salary')->default(1000); // Базовая зарплата
            $table->integer('max_count')->default(1); // Максимум членов этой роли
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ИГРУШЕК-ПЕРСОНАЖЕЙ (расширение mezon_domik)
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('toy_characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('toy_id')->unique(); // ID из mezon_domik
            $table->foreignId('crew_role_id')->constrained('crew_roles');
            $table->string('character_name', 100); // Имя персонажа в игре
            $table->integer('skill_level')->default(2); // Уровень навыка 1-3
            $table->string('trait', 100)->nullable(); // Особенность
            $table->string('trait_effect', 200)->nullable(); // Эффект особенности
            $table->string('quote', 255)->nullable(); // Цитата персонажа
            $table->string('avatar_path', 255)->nullable(); // Путь к аватару
            $table->string('portrait_color', 20)->default('#3498db'); // Цвет портрета (fallback)
            $table->integer('salary_modifier')->default(0); // Модификатор зарплаты (+/- от базовой)
            $table->json('special_abilities')->nullable(); // Особые способности JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА QR-КОДОВ (связь игрушек с пользователями)
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('toy_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique(); // Уникальный QR код
            $table->unsignedInteger('toy_id'); // ID из mezon_domik
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('activated_at')->nullable(); // Когда активирован
            $table->boolean('is_used')->default(false);
            $table->timestamps();
            
            $table->index(['toy_id', 'is_used']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА АКТИВИРОВАННЫХ ИГРУШЕК ПОЛЬЗОВАТЕЛЯ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('user_toys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('toy_character_id')->constrained('toy_characters');
            $table->foreignId('qr_code_id')->constrained('toy_qr_codes');
            $table->integer('bonus_money')->default(0); // Бонусные деньги при активации
            $table->timestamps();
            
            $table->unique(['user_id', 'toy_character_id']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ОСТАНОВОК МАРШРУТА
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->integer('order_index'); // Порядок на маршруте
            $table->string('name', 100);
            $table->integer('x_coord'); // Координата X на карте
            $table->integer('y_coord'); // Координата Y на карте
            $table->boolean('is_stop')->default(true); // Остановка или промежуточная точка
            $table->integer('level_number')->nullable(); // Номер уровня
            $table->string('route_segment', 50)->default('murmansk_anadyr'); // Сегмент маршрута
            $table->integer('salary_days')->default(1); // Дней зарплаты до этой точки
            $table->timestamps();
            
            $table->unique(['route_segment', 'order_index']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ЗАДАНИЙ НА ОСТАНОВКАХ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('stop_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_stop_id')->constrained('route_stops')->onDelete('cascade');
            $table->string('task_type', 50); // minigame, quiz, collection, etc.
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->integer('order_in_stop')->default(1); // Порядок задания на остановке
            $table->integer('reward_money')->default(0);
            $table->integer('reward_food_days')->default(0);
            $table->integer('reward_fuel_percent')->default(0);
            $table->json('reward_items')->nullable(); // Дополнительные награды
            $table->json('requirements')->nullable(); // Требования для задания
            $table->boolean('is_required')->default(true); // Обязательно для продолжения
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА СНАРЯЖЕНИЯ (справочник)
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('icon', 20);
            $table->string('category', 50); // equipment, food, medicine
            $table->integer('price');
            $table->integer('weight');
            $table->string('effect', 200);
            $table->integer('food_days')->default(0); // Для еды - сколько дней
            $table->json('special_effects')->nullable(); // Дополнительные эффекты
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ПОДРАБОТОК (справочник)
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title', 200);
            $table->string('icon', 20);
            $table->text('description');
            $table->integer('reward');
            $table->string('risk_level', 20); // low, medium, high
            $table->integer('risk_value')->default(1); // 1-3
            $table->string('requirement', 200)->nullable();
            $table->integer('cargo_weight')->default(0);
            $table->string('duration', 50);
            $table->foreignId('destination_stop_id')->nullable()->constrained('route_stops');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА БОНУСОВ И ПРИВИЛЕГИЙ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('description', 255);
            $table->string('type', 50); // infinite_resource, money_bonus, discount, etc.
            $table->string('resource_type', 50)->nullable(); // fuel, food, money, health
            $table->integer('value')->default(0); // Значение бонуса
            $table->integer('required_toys')->default(1); // Сколько игрушек нужно
            $table->json('conditions')->nullable(); // Дополнительные условия
            $table->boolean('is_permanent')->default(false); // Неубиваемый бонус
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ИГРОВОГО ПРОГРЕССА ПОЛЬЗОВАТЕЛЯ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('route_segment', 50)->default('murmansk_anadyr');
            
            // Текущая позиция
            $table->foreignId('current_stop_id')->nullable()->constrained('route_stops');
            $table->integer('current_point_index')->default(0);
            $table->enum('game_phase', ['preparation', 'traveling', 'at_stop', 'completed'])->default('preparation');
            
            // Ресурсы
            $table->integer('money')->default(250000);
            $table->integer('food_days')->default(0);
            $table->integer('fuel_percent')->default(100);
            $table->integer('cargo_used')->default(0);
            $table->integer('cargo_capacity')->default(100);
            $table->integer('morale')->default(100);
            
            // Статистика
            $table->integer('total_earned')->default(0);
            $table->integer('total_spent')->default(0);
            $table->integer('days_traveled')->default(0);
            
            // Флаги
            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            $table->unique(['user_id', 'route_segment', 'is_active']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА НАНЯТОЙ КОМАНДЫ ПОЛЬЗОВАТЕЛЯ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_crew', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('toy_character_id')->nullable()->constrained('toy_characters'); // Если игрушка
            $table->string('default_crew_code', 50)->nullable(); // Если стандартный член команды
            $table->foreignId('crew_role_id')->constrained('crew_roles');
            $table->integer('current_salary'); // Текущая зарплата
            $table->integer('health')->default(100);
            $table->integer('morale')->default(100);
            $table->boolean('is_from_toy')->default(false); // Из активированной игрушки
            $table->timestamps();
            
            $table->index('game_progress_id');
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА КУПЛЕННОГО СНАРЯЖЕНИЯ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('equipment_item_id')->constrained('equipment_items');
            $table->integer('quantity')->default(1);
            $table->integer('purchase_price');
            $table->timestamps();
            
            $table->unique(['game_progress_id', 'equipment_item_id']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ПРИНЯТЫХ ПОДРАБОТОК
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('job_offer_id')->constrained('job_offers');
            $table->enum('status', ['accepted', 'in_progress', 'completed', 'failed'])->default('accepted');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('actual_reward')->nullable();
            $table->timestamps();
            
            $table->index(['game_progress_id', 'status']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА АКТИВНЫХ БОНУСОВ ПОЛЬЗОВАТЕЛЯ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('user_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('game_bonus_id')->constrained('game_bonuses');
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // null = бессрочно
            $table->timestamps();
            
            $table->unique(['user_id', 'game_bonus_id']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ВЫПОЛНЕННЫХ ЗАДАНИЙ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_completed_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->foreignId('stop_task_id')->constrained('stop_tasks');
            $table->integer('score')->default(0);
            $table->integer('reward_received')->default(0);
            $table->json('task_data')->nullable(); // Данные выполнения
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['game_progress_id', 'stop_task_id']);
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА СТАНДАРТНЫХ ЧЛЕНОВ КОМАНДЫ (для тех, у кого нет игрушек)
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('default_crew_members', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('crew_role_id')->constrained('crew_roles');
            $table->string('name', 100);
            $table->integer('skill_level')->default(2);
            $table->string('trait', 100)->nullable();
            $table->string('trait_effect', 200)->nullable();
            $table->string('quote', 255)->nullable();
            $table->string('portrait_color', 20)->default('#3498db');
            $table->integer('salary');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ═══════════════════════════════════════════════════════════════════
        // ТАБЛИЦА ЛОГОВ ИГРОВЫХ СОБЫТИЙ
        // ═══════════════════════════════════════════════════════════════════
        Schema::create('game_event_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_progress_id')->constrained('game_progress')->onDelete('cascade');
            $table->string('event_type', 50); // hire, fire, purchase, sell, travel, task_complete, etc.
            $table->string('description', 255);
            $table->json('event_data')->nullable();
            $table->integer('money_change')->default(0);
            $table->timestamps();
            
            $table->index(['game_progress_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_event_logs');
        Schema::dropIfExists('default_crew_members');
        Schema::dropIfExists('game_completed_tasks');
        Schema::dropIfExists('user_bonuses');
        Schema::dropIfExists('game_jobs');
        Schema::dropIfExists('game_equipment');
        Schema::dropIfExists('game_crew');
        Schema::dropIfExists('game_progress');
        Schema::dropIfExists('game_bonuses');
        Schema::dropIfExists('job_offers');
        Schema::dropIfExists('equipment_items');
        Schema::dropIfExists('stop_tasks');
        Schema::dropIfExists('route_stops');
        Schema::dropIfExists('user_toys');
        Schema::dropIfExists('toy_qr_codes');
        Schema::dropIfExists('toy_characters');
        Schema::dropIfExists('crew_roles');
    }
};
