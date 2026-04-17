@extends('layouts.app')

@section('content')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>
@endpush
@push('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Nunito:wght@400;600;700;800&display=swap');
        
        body { 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #1a2a3a 0%, #0d1b2a 50%, #1b263b 100%);
            font-family: 'Nunito', Arial, sans-serif;
            min-height: 100vh;
        }
        
        #game-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        h1, h2 {
            font-family: 'Russo One', Arial, sans-serif;
            color: #4fc3f7;
            text-align: center;
            text-shadow: 0 2px 10px rgba(79, 195, 247, 0.3);
            letter-spacing: 2px;
        }
        
        #game-controls {
            max-width: 1200px;
            margin: 20px auto;
            text-align: center;
        }
        
        #start-button {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            padding: 18px 60px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
            transition: all 0.3s ease;
            margin-bottom: 25px;
            font-family: 'Russo One', Arial, sans-serif;
        }
        
        #start-button:hover {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.5);
        }
        
        #start-button:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }
        
        #start-button:disabled {
            background: #7f8c8d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        #game-description {
            background: linear-gradient(135deg, #1e3a5f, #0d2137);
            border: 2px solid #4fc3f7;
            border-radius: 15px;
            padding: 25px 30px;
            color: #ecf0f1;
            text-align: left;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        #game-description h3 {
            color: #4fc3f7;
            margin-top: 0;
            font-size: 22px;
            text-align: center;
            border-bottom: 2px solid #4fc3f7;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-family: 'Russo One', Arial, sans-serif;
        }
        
        #game-description p {
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        #game-description ul {
            padding-left: 25px;
            margin-bottom: 15px;
        }
        
        #game-description li {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        #game-description .highlight {
            color: #f39c12;
            font-weight: bold;
        }
        
        #game-description .emoji-icon {
            font-size: 18px;
            margin-right: 8px;
        }
        
        #game-controls.game-started #start-button {
            display: none;
        }
        
        #game-controls.game-started #game-description {
            display: none;
        }
        
        .game-phase-indicator {
            background: rgba(79, 195, 247, 0.1);
            border: 1px solid rgba(79, 195, 247, 0.3);
            border-radius: 10px;
            padding: 10px 20px;
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .game-phase-indicator span {
            color: #4fc3f7;
            font-weight: bold;
        }
    </style>
@endpush

<div class="container mt-5">
    <h2 class="text-center mb-4">⚓ Путешествие по Северному морскому пути</h2>
    
    <div class="text-center mb-3">
        <div class="game-phase-indicator">
            📍 Этап: <span id="current-phase">Подготовка экспедиции</span>
        </div>
    </div>
    
    <div id="game-container"></div>
    
    <div id="game-controls">
        <button id="start-button" onclick="startPreparation()">⚓ Начать подготовку</button>
        
        <div id="game-description">
            <h3>🗺️ Подготовка экспедиции в порту Мурманск</h3>
            
            <p>
                <span class="emoji-icon">⚓</span>
                Добро пожаловать в <span class="highlight">порт Мурманск</span>! Здесь начинается ваше 
                путешествие из Мурманска в <span class="highlight">Анадырь</span> 
                по легендарному <span class="highlight">Северному морскому пути</span>.
            </p>
            
            <p>
                <span class="emoji-icon">💰</span>
                На экспедицию выделено <span class="highlight">150 000 ₽</span>. 
                Распорядитесь ими мудро!
            </p>
            
            <p>
                <span class="emoji-icon">📋</span>
                <strong>Перед отправлением вам нужно:</strong>
            </p>
            <ul>
                <li>🧑‍✈️ <strong>Набрать команду</strong> — в Кадровом агентстве</li>
                <li>🧰 <strong>Купить снаряжение</strong> — на Складе снаряжения</li>
                <li>🍞 <strong>Закупить продовольствие</strong> — на Продовольственном складе</li>
                <li>💼 <strong>Взять подработку</strong> — на Доске объявлений (необязательно, но прибыльно!)</li>
            </ul>
            
            <p>
                <span class="emoji-icon">🎯</span>
                <strong>Совет:</strong> Кликайте по зданиям порта, чтобы открыть их меню. 
                Следите за панелью состояния внизу экрана!
            </p>
            
            <p style="text-align: center; margin-top: 20px; color: #95a5a6; font-size: 14px;">
                Нажмите кнопку "Начать подготовку" чтобы приступить
            </p>
        </div>
    </div>
</div>

@push('scripts')    
<script>
// ═══════════════════════════════════════════════════════════════════════════
// ДАННЫЕ ИГРЫ
// ═══════════════════════════════════════════════════════════════════════════

const GAME_DATA = {
    initialMoney: 150000,
    minCrewRequired: 6,
    maxCrew: 12,
    maxCargoCapacity: 100,
    
    crew: [
        { id: 'captain', name: 'Иван Северов', role: 'Капитан', roleIcon: '👨‍✈️', salary: 2000, skill: 3, trait: 'Опытный навигатор', traitEffect: '+20% к скорости во льдах', quote: 'Я проведу наш корабль через любые льды!', required: true, portrait: '#3498db' },
        { id: 'mechanic1', name: 'Пётр Гаечкин', role: 'Механик', roleIcon: '🔧', salary: 1500, skill: 3, trait: 'Экономит топливо', traitEffect: '-15% расход топлива', quote: 'Я умею чинить двигатель прямо во льдах!', required: true, portrait: '#e67e22' },
        { id: 'mechanic2', name: 'Анна Болтова', role: 'Механик', roleIcon: '🔧', salary: 1200, skill: 2, trait: 'Быстрый ремонт', traitEffect: '-30% время ремонта', quote: 'Дайте мне гаечный ключ — и корабль будет как новый!', required: false, portrait: '#9b59b6' },
        { id: 'doctor', name: 'Елена Айболитова', role: 'Врач', roleIcon: '👨‍⚕️', salary: 1800, skill: 3, trait: 'Профилактика болезней', traitEffect: '-50% шанс болезней', quote: 'Здоровье команды — моя главная забота!', required: true, portrait: '#1abc9c' },
        { id: 'cook', name: 'Борис Поваров', role: 'Кок', roleIcon: '👨‍🍳', salary: 1000, skill: 2, trait: 'Экономная готовка', traitEffect: '-20% расход еды', quote: 'Накормлю всю команду так, что пальчики оближете!', required: true, portrait: '#f39c12' },
        { id: 'navigator', name: 'Ольга Компасова', role: 'Штурман', roleIcon: '🧭', salary: 1400, skill: 2, trait: 'Знание маршрутов', traitEffect: '+15% к навигации', quote: 'Северный морской путь — моя вторая родина!', required: true, portrait: '#e74c3c' },
        { id: 'radioman', name: 'Сергей Волнов', role: 'Радист', roleIcon: '📻', salary: 1100, skill: 2, trait: 'Связь с берегом', traitEffect: '+погодные предупреждения', quote: 'Всегда на связи, в любую погоду!', required: true, portrait: '#2980b9' },
        { id: 'sailor1', name: 'Николай Морской', role: 'Матрос', roleIcon: '⚓', salary: 800, skill: 1, trait: 'Крепкий здоровьем', traitEffect: '+устойчивость к холоду', quote: 'Готов к любой работе на палубе!', required: false, portrait: '#34495e' },
        { id: 'sailor2', name: 'Мария Якорева', role: 'Матрос', roleIcon: '⚓', salary: 800, skill: 2, trait: 'Ловкая', traitEffect: '+скорость при швартовке', quote: 'Женщина на корабле — к удаче!', required: false, portrait: '#8e44ad' },
        { id: 'sailor3', name: 'Алексей Канатов', role: 'Матрос', roleIcon: '⚓', salary: 700, skill: 1, trait: 'Боится льдов', traitEffect: '-настроение во льдах', quote: 'Надеюсь, льдов будет не слишком много...', required: false, portrait: '#7f8c8d' },
        { id: 'scientist', name: 'Виктор Наукин', role: 'Учёный', roleIcon: '🔬', salary: 1600, skill: 3, trait: 'Исследователь', traitEffect: '+бонусы за открытия', quote: 'Каждая экспедиция — это новые открытия!', required: false, portrait: '#16a085' },
        { id: 'diver', name: 'Дмитрий Глубинов', role: 'Водолаз', roleIcon: '🤿', salary: 1300, skill: 2, trait: 'Подводный ремонт', traitEffect: '+ремонт без дока', quote: 'Под водой — как дома!', required: false, portrait: '#0984e3' }
    ],
    
    equipment: [
        { id: 'tools', name: 'Набор инструментов', icon: '🧰', price: 5000, weight: 5, effect: '+10% к ремонту', category: 'equipment' },
        { id: 'rope', name: 'Канаты (комплект)', icon: '🪢', price: 2000, weight: 3, effect: 'Необходимо для швартовки', category: 'equipment' },
        { id: 'radio', name: 'Запасная рация', icon: '📻', price: 8000, weight: 2, effect: 'Резервная связь', category: 'equipment' },
        { id: 'lifeboat', name: 'Спасательная шлюпка', icon: '🚣', price: 15000, weight: 15, effect: '+безопасность экипажа', category: 'equipment' },
        { id: 'anchor', name: 'Запасной якорь', icon: '⚓', price: 10000, weight: 20, effect: 'На случай потери', category: 'equipment' },
        { id: 'lights', name: 'Прожекторы', icon: '🔦', price: 6000, weight: 5, effect: '+видимость ночью', category: 'equipment' },
        { id: 'heater', name: 'Обогреватели', icon: '🔥', price: 12000, weight: 10, effect: '+комфорт экипажа', category: 'equipment' },
        { id: 'gps', name: 'GPS-навигатор', icon: '📡', price: 20000, weight: 1, effect: '+точность навигации', category: 'equipment' },
    ],
    
    food: [
        { id: 'bread', name: 'Хлеб (запас)', icon: '🍞', price: 3000, weight: 10, effect: '+5 дней еды', days: 5, category: 'food' },
        { id: 'meat', name: 'Мясные консервы', icon: '🥫', price: 8000, weight: 15, effect: '+10 дней еды', days: 10, category: 'food' },
        { id: 'fish', name: 'Рыбные консервы', icon: '🐟', price: 6000, weight: 12, effect: '+8 дней еды', days: 8, category: 'food' },
        { id: 'vegetables', name: 'Овощи сушёные', icon: '🥕', price: 4000, weight: 8, effect: '+6 дней еды', days: 6, category: 'food' },
        { id: 'water', name: 'Питьевая вода', icon: '💧', price: 2000, weight: 20, effect: '+7 дней воды', days: 7, category: 'food' },
        { id: 'tea', name: 'Чай и кофе', icon: '☕', price: 1500, weight: 3, effect: '+настроение', days: 0, category: 'food' },
        { id: 'chocolate', name: 'Шоколад', icon: '🍫', price: 2500, weight: 5, effect: '+энергия, +настроение', days: 2, category: 'food' },
        { id: 'vitamins', name: 'Витамины', icon: '💊', price: 5000, weight: 1, effect: '+здоровье экипажа', days: 0, category: 'food' },
    ],
    
    medicine: [
        { id: 'firstaid', name: 'Аптечка', icon: '🩹', price: 3000, weight: 2, effect: 'Базовая помощь', category: 'medicine' },
        { id: 'antibiotics', name: 'Антибиотики', icon: '💊', price: 8000, weight: 1, effect: 'Лечение инфекций', category: 'medicine' },
        { id: 'painkillers', name: 'Обезболивающие', icon: '💉', price: 4000, weight: 1, effect: 'Снятие боли', category: 'medicine' },
        { id: 'frostbite', name: 'Мазь от обморожения', icon: '🧴', price: 5000, weight: 2, effect: 'Лечение обморожений', category: 'medicine' },
    ],
    
    jobs: [
        { id: 'job1', title: 'Доставка ящиков в Архангельск', icon: '📦', description: 'Перевезти 20 ящиков с оборудованием для метеостанции', reward: 25000, risk: 'Низкий', riskLevel: 1, requirement: 'Свободное место в трюме', cargoWeight: 15, duration: '+ 0 дней' },
        { id: 'job2', title: 'Перевозка замороженной рыбы', icon: '🐟', description: 'Доставить партию свежемороженой трески в Диксон', reward: 40000, risk: 'Средний', riskLevel: 2, requirement: 'Нужен холодильник на борту', cargoWeight: 25, duration: '+ 1 день' },
        { id: 'job3', title: 'Установка буя', icon: '📡', description: 'Установить навигационный буй в точке маршрута', reward: 15000, risk: 'Низкий', riskLevel: 1, requirement: 'Нужен водолаз', cargoWeight: 5, duration: '+ 0.5 дня' },
        { id: 'job4', title: 'Перевозка учёных', icon: '🔬', description: 'Взять на борт группу исследователей до острова Диксон', reward: 35000, risk: 'Низкий', riskLevel: 1, requirement: 'Свободные каюты', cargoWeight: 0, duration: '+ 2 дня остановка' },
        { id: 'job5', title: 'Срочная почта', icon: '✉️', description: 'Доставить важные документы на остров Врангеля', reward: 20000, risk: 'Высокий', riskLevel: 3, requirement: 'Быстрый корабль', cargoWeight: 1, duration: 'Срок ограничен!' }
    ],
    
    // Точки маршрута (из оригинальной игры)
    routePoints: [
        { x: 625, y: 462, isStop: true, name: "Мурманск", level: 1 },
        { x: 639, y: 441, isStop: false },
        { x: 680, y: 466, isStop: true, name: "Териберка", level: 2 },
        { x: 751, y: 478, isStop: true, name: "Мыс Надежда", level: 3 },
        { x: 758, y: 534, isStop: true, name: "Остров Моржовский", level: 4 },
        { x: 664, y: 594, isStop: true, name: "Соловецкие острова", level: 5 },
        { x: 631, y: 540, isStop: false },
        { x: 615, y: 525, isStop: true, name: "Кандалакша", level: 6 },
        { x: 631, y: 540, isStop: false },
        { x: 694, y: 576, isStop: false },                
        { x: 738, y: 612, isStop: true, name: "Архангельск", level: 7 }
    ]
};

// ═══════════════════════════════════════════════════════════════════════════
// СОСТОЯНИЕ ИГРЫ
// ═══════════════════════════════════════════════════════════════════════════

const gameState = {
    money: GAME_DATA.initialMoney,
    crew: [],
    equipment: [],
    food: [],
    medicine: [],
    jobs: [],
    foodDays: 0,
    cargoUsed: 0,
    morale: 100,
    fuelPercent: 100,
    phase: 'preparation' // preparation, voyage
};

// ═══════════════════════════════════════════════════════════════════════════
// КОНФИГУРАЦИЯ PHASER
// ═══════════════════════════════════════════════════════════════════════════

const config = {
    type: Phaser.AUTO,
    width: 1200,
    height: 800,
    parent: 'game-container',
    backgroundColor: '#1a3a4a',
    scene: [PreparationScene, VoyageScene]
};

let game;
let currentModal = null;
let statusPanel = null;

// ═══════════════════════════════════════════════════════════════════════════
// СЦЕНА ПОДГОТОВКИ (ПОРТ МУРМАНСК)
// ═══════════════════════════════════════════════════════════════════════════

class PreparationScene extends Phaser.Scene {
    constructor() {
        super({ key: 'PreparationScene' });
    }
    
    preload() {
        // Загрузка ассетов
        // this.load.image('port_bg', 'assets/game/murmansk/port_bg.png');
    }
    
    create() {
        this.createPortBackground();
        this.createBuildings();
        this.createStatusPanel();
        this.createDecorations();
        this.createShip();
    }
    
    createPortBackground() {
        // Градиентный фон неба
        const skyGradient = this.add.graphics();
        skyGradient.fillGradientStyle(0x87CEEB, 0x87CEEB, 0x4a90a4, 0x4a90a4, 1);
        skyGradient.fillRect(0, 0, 1200, 400);
        
        // Горы
        const mountains = this.add.graphics();
        mountains.fillStyle(0x5d6d7e, 1);
        mountains.beginPath();
        mountains.moveTo(0, 400);
        mountains.lineTo(150, 280);
        mountains.lineTo(300, 350);
        mountains.lineTo(450, 250);
        mountains.lineTo(600, 320);
        mountains.lineTo(750, 230);
        mountains.lineTo(900, 300);
        mountains.lineTo(1050, 260);
        mountains.lineTo(1200, 350);
        mountains.lineTo(1200, 400);
        mountains.closePath();
        mountains.fill();
        
        // Снежные вершины
        mountains.fillStyle(0xecf0f1, 1);
        [[450, 250, 430, 280, 470, 280], [750, 230, 725, 265, 775, 265]].forEach(p => {
            mountains.beginPath();
            mountains.moveTo(p[0], p[1]);
            mountains.lineTo(p[2], p[3]);
            mountains.lineTo(p[4], p[5]);
            mountains.closePath();
            mountains.fill();
        });
        
        // Море
        const sea = this.add.graphics();
        sea.fillGradientStyle(0x2980b9, 0x2980b9, 0x1a5276, 0x1a5276, 1);
        sea.fillRect(0, 400, 1200, 400);
        
        // Волны
        for (let i = 0; i < 8; i++) {
            const waveY = 420 + i * 45;
            const wave = this.add.graphics();
            wave.lineStyle(2, 0x3498db, 0.3 - i * 0.03);
            wave.beginPath();
            for (let x = 0; x <= 1200; x += 10) {
                const y = waveY + Math.sin(x * 0.02 + i) * 8;
                if (x === 0) wave.moveTo(x, y);
                else wave.lineTo(x, y);
            }
            wave.strokePath();
            
            this.tweens.add({
                targets: wave,
                x: -50,
                duration: 3000 + i * 500,
                repeat: -1,
                yoyo: true,
                ease: 'Sine.easeInOut'
            });
        }
        
        // Причал
        const dock = this.add.graphics();
        dock.fillStyle(0x6d4c41, 1);
        dock.fillRect(50, 550, 400, 30);
        dock.fillStyle(0x5d4037, 1);
        dock.fillRect(50, 580, 400, 20);
        
        for (let i = 0; i < 5; i++) {
            dock.fillStyle(0x4e342e, 1);
            dock.fillRect(70 + i * 95, 580, 15, 60);
        }
        
        // Земля
        const ground = this.add.graphics();
        ground.fillStyle(0x7f8c8d, 1);
        ground.fillRect(0, 600, 1200, 200);
        
        ground.fillStyle(0x6c7a7a, 1);
        for (let i = 0; i < 20; i++) {
            ground.fillRect(Math.random() * 1200, 620 + Math.random() * 160, 30 + Math.random() * 50, 5);
        }
        
        // Заголовок
        const titleBg = this.add.graphics();
        titleBg.fillStyle(0x000000, 0.5);
        titleBg.fillRoundedRect(400, 15, 400, 50, 10);
        
        const title = this.add.text(600, 40, '🏭 ПОРТ МУРМАНСК', {
            fontSize: '28px',
            fontFamily: 'Russo One, Arial',
            color: '#ffffff',
            stroke: '#000000',
            strokeThickness: 2
        });
        title.setOrigin(0.5);
    }
    
    createBuildings() {
        const buildings = [
            { x: 150, y: 500, width: 120, height: 100, name: 'Кадровое\nагентство', icon: '🧑‍✈️', color: 0x3498db, action: () => this.openCrewModal() },
            { x: 350, y: 500, width: 120, height: 120, name: 'Склад\nснаряжения', icon: '🧰', color: 0xe67e22, action: () => this.openEquipmentModal() },
            { x: 550, y: 500, width: 120, height: 110, name: 'Продоволь-\nственный\nсклад', icon: '🍞', color: 0x27ae60, action: () => this.openFoodModal() },
            { x: 750, y: 500, width: 130, height: 100, name: 'Доска\nобъявлений', icon: '💼', color: 0x9b59b6, action: () => this.openJobsModal() }
        ];
        
        buildings.forEach(b => this.createBuilding(b));
    }
    
    createBuilding(config) {
        const container = this.add.container(config.x, config.y);
        
        // Тень
        const shadow = this.add.graphics();
        shadow.fillStyle(0x000000, 0.3);
        shadow.fillRoundedRect(5, 5, config.width, config.height, 10);
        container.add(shadow);
        
        // Здание
        const building = this.add.graphics();
        building.fillStyle(config.color, 1);
        building.fillRoundedRect(0, 0, config.width, config.height, 10);
        building.lineStyle(3, 0xffffff, 0.3);
        building.strokeRoundedRect(0, 0, config.width, config.height, 10);
        container.add(building);
        
        // Крыша
        const roof = this.add.graphics();
        roof.fillStyle(darkenColor(config.color, 30), 1);
        roof.beginPath();
        roof.moveTo(-10, 0);
        roof.lineTo(config.width / 2, -30);
        roof.lineTo(config.width + 10, 0);
        roof.closePath();
        roof.fill();
        container.add(roof);
        
        // Окна
        const windowCount = Math.floor(config.width / 40);
        for (let i = 0; i < windowCount; i++) {
            const win = this.add.graphics();
            win.fillStyle(0xf4d03f, 0.8);
            win.fillRect(15 + i * 35, 20, 20, 25);
            win.lineStyle(2, 0x5d4037, 1);
            win.strokeRect(15 + i * 35, 20, 20, 25);
            container.add(win);
        }
        
        // Иконка
        const icon = this.add.text(config.width / 2, config.height / 2 - 5, config.icon, { fontSize: '40px' });
        icon.setOrigin(0.5);
        container.add(icon);
        
        // Название
        const name = this.add.text(config.width / 2, config.height + 15, config.name, {
            fontSize: '14px',
            fontFamily: 'Nunito, Arial',
            color: '#ffffff',
            align: 'center',
            stroke: '#000000',
            strokeThickness: 3
        });
        name.setOrigin(0.5, 0);
        container.add(name);
        
        // Интерактивность
        const hitArea = this.add.rectangle(config.width / 2, config.height / 2, config.width, config.height, 0xffffff, 0);
        hitArea.setInteractive({ useHandCursor: true });
        container.add(hitArea);
        
        hitArea.on('pointerover', () => {
            this.tweens.add({
                targets: container,
                scaleX: 1.05,
                scaleY: 1.05,
                y: config.y - 10,
                duration: 150,
                ease: 'Back.easeOut'
            });
        });
        
        hitArea.on('pointerout', () => {
            this.tweens.add({
                targets: container,
                scaleX: 1,
                scaleY: 1,
                y: config.y,
                duration: 150,
                ease: 'Back.easeIn'
            });
        });
        
        hitArea.on('pointerdown', () => {
            this.tweens.add({
                targets: container,
                scaleX: 0.95,
                scaleY: 0.95,
                duration: 50,
                yoyo: true,
                onComplete: config.action
            });
        });
        
        return container;
    }
    
    createShip() {
        const shipContainer = this.add.container(200, 420);
        
        // Корпус
        const hull = this.add.graphics();
        hull.fillStyle(0x2c3e50, 1);
        hull.beginPath();
        hull.moveTo(-60, 0);
        hull.lineTo(-80, 30);
        hull.lineTo(80, 30);
        hull.lineTo(100, 0);
        hull.lineTo(80, -10);
        hull.lineTo(-60, -10);
        hull.closePath();
        hull.fill();
        hull.lineStyle(2, 0x1a252f, 1);
        hull.strokePath();
        shipContainer.add(hull);
        
        // Палуба
        const deck = this.add.graphics();
        deck.fillStyle(0x6d4c41, 1);
        deck.fillRect(-55, -25, 130, 15);
        shipContainer.add(deck);
        
        // Надстройка
        const cabin = this.add.graphics();
        cabin.fillStyle(0xecf0f1, 1);
        cabin.fillRoundedRect(-30, -60, 60, 35, 5);
        cabin.fillStyle(0x3498db, 0.8);
        cabin.fillRect(-25, -55, 20, 15);
        cabin.fillRect(5, -55, 20, 15);
        shipContainer.add(cabin);
        
        // Труба
        const chimney = this.add.graphics();
        chimney.fillStyle(0xe74c3c, 1);
        chimney.fillRect(15, -85, 20, 25);
        chimney.fillStyle(0xffffff, 1);
        chimney.fillRect(15, -75, 20, 5);
        shipContainer.add(chimney);
        
        // Флаг
        const flag = this.add.graphics();
        flag.fillStyle(0xffffff, 1);
        flag.fillRect(40, -90, 3, 50);
        flag.fillStyle(0xff0000, 1);
        flag.fillTriangle(43, -90, 43, -70, 70, -80);
        shipContainer.add(flag);
        
        // Кнопка отплытия
        const buttonContainer = this.add.container(0, 60);
        
        const btnBg = this.add.graphics();
        btnBg.fillStyle(0x27ae60, 1);
        btnBg.fillRoundedRect(-80, -20, 160, 40, 20);
        btnBg.lineStyle(3, 0x1e8449, 1);
        btnBg.strokeRoundedRect(-80, -20, 160, 40, 20);
        buttonContainer.add(btnBg);
        
        const btnText = this.add.text(0, 0, '🚢 В путь!', {
            fontSize: '18px',
            fontFamily: 'Nunito, Arial',
            color: '#ffffff',
            fontStyle: 'bold'
        });
        btnText.setOrigin(0.5);
        buttonContainer.add(btnText);
        
        const btnHit = this.add.rectangle(0, 0, 160, 40, 0xffffff, 0);
        btnHit.setInteractive({ useHandCursor: true });
        buttonContainer.add(btnHit);
        
        btnHit.on('pointerover', () => {
            this.tweens.add({ targets: buttonContainer, scaleX: 1.1, scaleY: 1.1, duration: 100 });
        });
        
        btnHit.on('pointerout', () => {
            this.tweens.add({ targets: buttonContainer, scaleX: 1, scaleY: 1, duration: 100 });
        });
        
        btnHit.on('pointerdown', () => {
            this.checkReadyToDepart();
        });
        
        shipContainer.add(buttonContainer);
        
        // Покачивание корабля
        this.tweens.add({
            targets: shipContainer,
            y: 425,
            rotation: 0.02,
            duration: 2000,
            repeat: -1,
            yoyo: true,
            ease: 'Sine.easeInOut'
        });
    }
    
    createDecorations() {
        // Чайки
        for (let i = 0; i < 5; i++) {
            const gull = this.add.text(100 + Math.random() * 1000, 100 + Math.random() * 200, '🕊️', { fontSize: '20px' });
            this.tweens.add({
                targets: gull,
                x: gull.x + (Math.random() - 0.5) * 200,
                y: gull.y + (Math.random() - 0.5) * 100,
                duration: 3000 + Math.random() * 2000,
                repeat: -1,
                yoyo: true,
                ease: 'Sine.easeInOut'
            });
        }
        
        // Облака
        for (let i = 0; i < 4; i++) {
            const cloud = this.add.text(-100 + i * 400, 50 + Math.random() * 100, '☁️', { fontSize: (30 + Math.random() * 20) + 'px' });
            cloud.setAlpha(0.7);
            this.tweens.add({
                targets: cloud,
                x: 1300,
                duration: 30000 + Math.random() * 20000,
                repeat: -1,
                onRepeat: () => { cloud.x = -100; cloud.y = 50 + Math.random() * 100; }
            });
        }
        
        // Бочки
        const barrels = ['🛢️', '🪵', '📦'];
        for (let i = 0; i < 6; i++) {
            this.add.text(80 + i * 60, 610 + (i % 2) * 15, barrels[i % 3], { fontSize: '25px' });
        }
    }
    
    createStatusPanel() {
        statusPanel = this.add.container(600, 760);
        
        const panelBg = this.add.graphics();
        panelBg.fillStyle(0x1a1a2e, 0.95);
        panelBg.fillRoundedRect(-580, -35, 1160, 70, 15);
        panelBg.lineStyle(2, 0x4fc3f7, 0.5);
        panelBg.strokeRoundedRect(-580, -35, 1160, 70, 15);
        statusPanel.add(panelBg);
        
        const items = [
            { key: 'money', icon: '💰', label: 'Деньги', getValue: () => formatMoney(gameState.money) },
            { key: 'crew', icon: '🧑‍✈️', label: 'Экипаж', getValue: () => `${gameState.crew.length} / ${GAME_DATA.maxCrew}` },
            { key: 'food', icon: '🍞', label: 'Еда', getValue: () => `${gameState.foodDays} дней` },
            { key: 'fuel', icon: '⛽', label: 'Топливо', getValue: () => `${gameState.fuelPercent}%` },
            { key: 'cargo', icon: '⚖️', label: 'Груз', getValue: () => `${gameState.cargoUsed} / ${GAME_DATA.maxCargoCapacity}` },
            { key: 'morale', icon: '😊', label: 'Настрой', getValue: () => getMoraleEmoji() }
        ];
        
        statusPanel.statusTexts = {};
        
        items.forEach((item, index) => {
            const x = -480 + index * 190;
            
            const icon = this.add.text(x, -10, item.icon, { fontSize: '24px' });
            icon.setOrigin(0, 0.5);
            statusPanel.add(icon);
            
            const label = this.add.text(x + 35, -18, item.label, {
                fontSize: '12px',
                fontFamily: 'Nunito, Arial',
                color: '#95a5a6'
            });
            statusPanel.add(label);
            
            const value = this.add.text(x + 35, 2, item.getValue(), {
                fontSize: '16px',
                fontFamily: 'Nunito, Arial',
                color: '#ffffff',
                fontStyle: 'bold'
            });
            statusPanel.add(value);
            
            statusPanel.statusTexts[item.key] = { text: value, getValue: item.getValue };
        });
    }
    
    // Модальные окна и остальная логика...
    // (Используем те же функции, что и в standalone версии)
    
    openCrewModal() {
        openCrewModal(this);
    }
    
    openEquipmentModal() {
        openEquipmentModal(this);
    }
    
    openFoodModal() {
        openFoodModal(this);
    }
    
    openJobsModal() {
        openJobsModal(this);
    }
    
    checkReadyToDepart() {
        checkReadyToDepart(this);
    }
    
    startVoyage() {
        // Сохраняем состояние и переходим к сцене путешествия
        gameState.phase = 'voyage';
        document.getElementById('current-phase').textContent = 'Путешествие';
        this.scene.start('VoyageScene');
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// СЦЕНА ПУТЕШЕСТВИЯ (из оригинального кода)
// ═══════════════════════════════════════════════════════════════════════════

class VoyageScene extends Phaser.Scene {
    constructor() {
        super({ key: 'VoyageScene' });
    }
    
    preload() {
        this.load.image('map', 'assets/game/murmansk/map_01.png');
        this.load.image('ship', 'assets/game/murmansk/ship.png');
    }
    
    create() {
        // Код из оригинального ship_game_blade.php
        const map = this.add.image(600, 400, 'map');
        const scaleX = 1200 / map.width;
        const scaleY = 800 / map.height;
        const scale = Math.max(scaleX, scaleY);
        map.setScale(scale);
        
        // Рисуем маршрут
        const graphics = this.add.graphics();
        graphics.lineStyle(4, 0xFF0000, 0.8);
        graphics.beginPath();
        graphics.moveTo(GAME_DATA.routePoints[0].x, GAME_DATA.routePoints[0].y);
        GAME_DATA.routePoints.forEach(point => {
            graphics.lineTo(point.x, point.y);
        });
        graphics.strokePath();
        
        // Остановки
        GAME_DATA.routePoints.forEach(point => {
            if (point.isStop) {
                graphics.fillStyle(0x000000, 1);
                graphics.fillCircle(point.x, point.y, 10);
                graphics.lineStyle(2, 0xFFFFFF, 1);
                graphics.strokeCircle(point.x, point.y, 10);
            }
        });
        
        // Корабль
        this.ship = this.add.image(GAME_DATA.routePoints[0].x, GAME_DATA.routePoints[0].y, 'ship');
        this.ship.setScale(0.3);
        this.ship.setOrigin(0.5, 0.5);
        
        // Тень
        const shadow = this.add.ellipse(this.ship.x, this.ship.y + 15, 60, 20, 0x000000, 0.3);
        shadow.setDepth(-1);
        this.ship.shadow = shadow;
        
        // ... остальная логика путешествия
        
        // Показываем информацию о состоянии
        this.add.text(10, 10, `💰 ${formatMoney(gameState.money)} | 🧑‍✈️ ${gameState.crew.length} чел. | 🍞 ${gameState.foodDays} дней`, {
            fontSize: '16px',
            fontFamily: 'Nunito, Arial',
            color: '#ffffff',
            backgroundColor: '#000000',
            padding: { x: 10, y: 5 }
        });
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ (такие же как в standalone)
// ═══════════════════════════════════════════════════════════════════════════

function formatMoney(amount) {
    return amount.toLocaleString('ru-RU') + ' ₽';
}

function darkenColor(color, percent) {
    const r = (color >> 16) & 0xFF;
    const g = (color >> 8) & 0xFF;
    const b = color & 0xFF;
    const factor = (100 - percent) / 100;
    return (Math.floor(r * factor) << 16) | (Math.floor(g * factor) << 8) | Math.floor(b * factor);
}

function getMoraleEmoji() {
    if (gameState.morale >= 80) return '😊 Отлично';
    if (gameState.morale >= 60) return '🙂 Хорошо';
    if (gameState.morale >= 40) return '😐 Нормально';
    if (gameState.morale >= 20) return '😟 Плохо';
    return '😢 Ужасно';
}

function updateStatusPanel() {
    if (!statusPanel || !statusPanel.statusTexts) return;
    Object.keys(statusPanel.statusTexts).forEach(key => {
        const item = statusPanel.statusTexts[key];
        item.text.setText(item.getValue());
        if (key === 'crew' && gameState.crew.length < GAME_DATA.minCrewRequired) {
            item.text.setColor('#e74c3c');
        } else if (key === 'food' && gameState.foodDays < 10) {
            item.text.setColor('#f39c12');
        } else {
            item.text.setColor('#ffffff');
        }
    });
}

// Копируем все функции модальных окон из standalone версии
// createModal, closeModal, openCrewModal, createCrewCard, и т.д.
// (Код тот же, что и в port_murmansk_game.html)

function createModal(scene, title, width, height) {
    if (currentModal) currentModal.destroy();
    
    const modal = scene.add.container(600, 400);
    modal.setDepth(1000);
    
    const overlay = scene.add.rectangle(0, 0, 1200, 800, 0x000000, 0.7);
    overlay.setInteractive();
    modal.add(overlay);
    
    const windowBg = scene.add.graphics();
    windowBg.fillStyle(0x1e3a5f, 1);
    windowBg.fillRoundedRect(-width/2, -height/2, width, height, 20);
    windowBg.lineStyle(3, 0x4fc3f7, 1);
    windowBg.strokeRoundedRect(-width/2, -height/2, width, height, 20);
    modal.add(windowBg);
    
    const titleBg = scene.add.graphics();
    titleBg.fillStyle(0x0d2137, 1);
    titleBg.fillRoundedRect(-width/2 + 10, -height/2 + 10, width - 20, 50, 10);
    modal.add(titleBg);
    
    const titleText = scene.add.text(0, -height/2 + 35, title, {
        fontSize: '24px',
        fontFamily: 'Russo One, Arial',
        color: '#4fc3f7'
    });
    titleText.setOrigin(0.5);
    modal.add(titleText);
    
    const closeBtn = scene.add.text(width/2 - 30, -height/2 + 35, '✖', {
        fontSize: '24px',
        color: '#e74c3c'
    });
    closeBtn.setOrigin(0.5);
    closeBtn.setInteractive({ useHandCursor: true });
    closeBtn.on('pointerdown', () => closeModal(scene, modal));
    closeBtn.on('pointerover', () => closeBtn.setScale(1.2));
    closeBtn.on('pointerout', () => closeBtn.setScale(1));
    modal.add(closeBtn);
    
    modal.setScale(0.8);
    modal.setAlpha(0);
    scene.tweens.add({
        targets: modal,
        scaleX: 1,
        scaleY: 1,
        alpha: 1,
        duration: 200,
        ease: 'Back.easeOut'
    });
    
    currentModal = modal;
    return modal;
}

function closeModal(scene, modal) {
    scene.tweens.add({
        targets: modal,
        scaleX: 0.8,
        scaleY: 0.8,
        alpha: 0,
        duration: 150,
        ease: 'Back.easeIn',
        onComplete: () => {
            modal.destroy();
            currentModal = null;
            updateStatusPanel();
        }
    });
}

function showNotification(scene, message, color) {
    const notification = scene.add.container(600, 100);
    notification.setDepth(2000);
    
    const bg = scene.add.graphics();
    bg.fillStyle(parseInt(color.replace('#', '0x')), 0.95);
    bg.fillRoundedRect(-200, -25, 400, 50, 10);
    notification.add(bg);
    
    const text = scene.add.text(0, 0, message, {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    text.setOrigin(0.5);
    notification.add(text);
    
    notification.setAlpha(0);
    notification.y = 50;
    
    scene.tweens.add({
        targets: notification,
        y: 100,
        alpha: 1,
        duration: 300,
        ease: 'Back.easeOut'
    });
    
    scene.time.delayedCall(2000, () => {
        scene.tweens.add({
            targets: notification,
            y: 50,
            alpha: 0,
            duration: 300,
            ease: 'Back.easeIn',
            onComplete: () => notification.destroy()
        });
    });
}

function showMoneyAnimation(scene, amount, isPositive) {
    const color = isPositive ? '#2ecc71' : '#e74c3c';
    const prefix = isPositive ? '+' : '';
    
    const text = scene.add.text(600, 700, `${prefix}${formatMoney(amount)}`, {
        fontSize: '24px',
        fontFamily: 'Russo One, Arial',
        color: color,
        stroke: '#000000',
        strokeThickness: 3
    });
    text.setOrigin(0.5);
    text.setDepth(2000);
    
    scene.tweens.add({
        targets: text,
        y: 600,
        alpha: 0,
        duration: 1500,
        ease: 'Power2',
        onComplete: () => text.destroy()
    });
}

// Полные функции модальных окон (openCrewModal, openEquipmentModal, etc.) 
// идентичны standalone версии - для краткости не дублируем

function openCrewModal(scene) {
    const modal = createModal(scene, '🧑‍✈️ КАДРОВОЕ АГЕНТСТВО', 1000, 650);
    const cardsContainer = scene.add.container(0, 30);
    modal.add(cardsContainer);
    
    const cardsPerRow = 4;
    const cardWidth = 220;
    const cardHeight = 180;
    const startX = -440;
    const startY = -200;
    
    GAME_DATA.crew.forEach((member, index) => {
        const row = Math.floor(index / cardsPerRow);
        const col = index % cardsPerRow;
        const x = startX + col * (cardWidth + 15);
        const y = startY + row * (cardHeight + 15);
        
        const card = createCrewCard(scene, member, x, y);
        cardsContainer.add(card);
    });
    
    const infoText = scene.add.text(0, 280, 
        `Минимум для экспедиции: ${GAME_DATA.minCrewRequired} человек | Максимум: ${GAME_DATA.maxCrew} человек`, {
        fontSize: '14px',
        fontFamily: 'Nunito, Arial',
        color: '#95a5a6',
        align: 'center'
    });
    infoText.setOrigin(0.5);
    modal.add(infoText);
}

function createCrewCard(scene, member, x, y) {
    const card = scene.add.container(x, y);
    const isHired = gameState.crew.includes(member.id);
    const bgColor = isHired ? 0x27ae60 : 0x2c3e50;
    
    const bg = scene.add.graphics();
    bg.fillStyle(bgColor, 1);
    bg.fillRoundedRect(0, 0, 210, 170, 12);
    bg.lineStyle(2, isHired ? 0x2ecc71 : 0x4fc3f7, 1);
    bg.strokeRoundedRect(0, 0, 210, 170, 12);
    card.add(bg);
    
    if (member.required && !isHired) {
        const reqBadge = scene.add.graphics();
        reqBadge.fillStyle(0xe74c3c, 1);
        reqBadge.fillRoundedRect(130, 5, 75, 20, 5);
        card.add(reqBadge);
        const reqText = scene.add.text(167, 15, 'НУЖЕН!', { fontSize: '10px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
        reqText.setOrigin(0.5);
        card.add(reqText);
    }
    
    const portrait = scene.add.graphics();
    portrait.fillStyle(parseInt(member.portrait.replace('#', '0x')), 1);
    portrait.fillCircle(40, 45, 30);
    portrait.lineStyle(3, 0xffffff, 0.5);
    portrait.strokeCircle(40, 45, 30);
    card.add(portrait);
    
    const roleIcon = scene.add.text(40, 45, member.roleIcon, { fontSize: '28px' });
    roleIcon.setOrigin(0.5);
    card.add(roleIcon);
    
    const name = scene.add.text(80, 25, member.name, { fontSize: '14px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
    card.add(name);
    
    const role = scene.add.text(80, 42, member.role, { fontSize: '12px', fontFamily: 'Nunito', color: '#4fc3f7' });
    card.add(role);
    
    const stars = '⭐'.repeat(member.skill) + '☆'.repeat(3 - member.skill);
    const skillText = scene.add.text(80, 58, stars, { fontSize: '12px' });
    card.add(skillText);
    
    const trait = scene.add.text(10, 85, `⚡ ${member.trait}`, { fontSize: '11px', fontFamily: 'Nunito', color: '#f39c12', wordWrap: { width: 190 } });
    card.add(trait);
    
    const salary = scene.add.text(10, 115, `💰 ${formatMoney(member.salary)}/день`, { fontSize: '12px', fontFamily: 'Nunito', color: '#2ecc71' });
    card.add(salary);
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(isHired ? 0xe74c3c : 0x3498db, 1);
    btnBg.fillRoundedRect(10, 138, 190, 25, 8);
    card.add(btnBg);
    
    const btnText = scene.add.text(105, 150, isHired ? '❌ Уволить' : '✓ Нанять', { fontSize: '13px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
    btnText.setOrigin(0.5);
    card.add(btnText);
    
    const hitArea = scene.add.rectangle(105, 150, 190, 25, 0xffffff, 0);
    hitArea.setInteractive({ useHandCursor: true });
    hitArea.on('pointerdown', () => toggleHireCrew(scene, member));
    card.add(hitArea);
    
    return card;
}

function toggleHireCrew(scene, member) {
    const isHired = gameState.crew.includes(member.id);
    
    if (isHired) {
        gameState.crew = gameState.crew.filter(id => id !== member.id);
        showMoneyAnimation(scene, member.salary * 5, true);
        gameState.money += member.salary * 5;
    } else {
        const hiringCost = member.salary * 10;
        if (gameState.money < hiringCost) {
            showNotification(scene, '❌ Недостаточно денег!', '#e74c3c');
            return;
        }
        if (gameState.crew.length >= GAME_DATA.maxCrew) {
            showNotification(scene, '❌ Экипаж укомплектован!', '#e74c3c');
            return;
        }
        
        gameState.crew.push(member.id);
        gameState.money -= hiringCost;
        showMoneyAnimation(scene, -hiringCost, false);
    }
    
    closeModal(scene, currentModal);
    setTimeout(() => openCrewModal(scene), 200);
}

function openEquipmentModal(scene) {
    const modal = createModal(scene, '🧰 СКЛАД СНАРЯЖЕНИЯ', 900, 600);
    
    const sections = [
        { title: '🧰 Снаряжение', items: GAME_DATA.equipment, y: -180 },
        { title: '💊 Медикаменты', items: GAME_DATA.medicine, y: 80 }
    ];
    
    sections.forEach(section => {
        const sectionTitle = scene.add.text(-400, section.y, section.title, { fontSize: '18px', fontFamily: 'Russo One', color: '#f39c12' });
        modal.add(sectionTitle);
        
        const shelf = scene.add.graphics();
        shelf.fillStyle(0x6d4c41, 1);
        shelf.fillRect(-400, section.y + 120, 800, 10);
        shelf.fillStyle(0x5d4037, 1);
        shelf.fillRect(-400, section.y + 130, 800, 5);
        modal.add(shelf);
        
        section.items.forEach((item, index) => {
            const x = -360 + (index % 4) * 200;
            const y = section.y + 35 + Math.floor(index / 4) * 80;
            const itemCard = createShopItem(scene, item, x, y);
            modal.add(itemCard);
        });
    });
    
    addCargoIndicator(scene, modal, 240);
}

function openFoodModal(scene) {
    const modal = createModal(scene, '🍞 ПРОДОВОЛЬСТВЕННЫЙ СКЛАД', 900, 500);
    
    const sectionTitle = scene.add.text(-400, -150, '🍞 Продукты и припасы', { fontSize: '18px', fontFamily: 'Russo One', color: '#27ae60' });
    modal.add(sectionTitle);
    
    for (let i = 0; i < 2; i++) {
        const shelf = scene.add.graphics();
        shelf.fillStyle(0x6d4c41, 1);
        shelf.fillRect(-400, -30 + i * 130, 800, 10);
        shelf.fillStyle(0x5d4037, 1);
        shelf.fillRect(-400, -20 + i * 130, 800, 5);
        modal.add(shelf);
    }
    
    GAME_DATA.food.forEach((item, index) => {
        const x = -360 + (index % 4) * 200;
        const y = -115 + Math.floor(index / 4) * 130;
        const itemCard = createShopItem(scene, item, x, y, true);
        modal.add(itemCard);
    });
    
    addCargoIndicator(scene, modal, 180);
    
    const foodInfo = scene.add.text(0, 210, `🍞 Запас еды: ${gameState.foodDays} дней`, { fontSize: '16px', fontFamily: 'Nunito', color: '#2ecc71' });
    foodInfo.setOrigin(0.5);
    modal.add(foodInfo);
}

function createShopItem(scene, item, x, y, isFood = false) {
    const container = scene.add.container(x, y);
    const isPurchased = gameState.equipment.includes(item.id) || gameState.food.includes(item.id) || gameState.medicine.includes(item.id);
    
    const bg = scene.add.graphics();
    bg.fillStyle(isPurchased ? 0x27ae60 : 0x2c3e50, 1);
    bg.fillRoundedRect(0, 0, 180, 70, 10);
    bg.lineStyle(2, isPurchased ? 0x2ecc71 : 0x4fc3f7, 0.5);
    bg.strokeRoundedRect(0, 0, 180, 70, 10);
    container.add(bg);
    
    const icon = scene.add.text(10, 35, item.icon, { fontSize: '30px' });
    icon.setOrigin(0, 0.5);
    container.add(icon);
    
    const name = scene.add.text(50, 12, item.name, { fontSize: '12px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold', wordWrap: { width: 120 } });
    container.add(name);
    
    const effect = scene.add.text(50, 35, item.effect, { fontSize: '10px', fontFamily: 'Nunito', color: '#4fc3f7', wordWrap: { width: 120 } });
    container.add(effect);
    
    const priceText = scene.add.text(50, 52, `💰${formatMoney(item.price)} | ⚖️${item.weight}кг`, { fontSize: '10px', fontFamily: 'Nunito', color: '#95a5a6' });
    container.add(priceText);
    
    const hitArea = scene.add.rectangle(90, 35, 180, 70, 0xffffff, 0);
    hitArea.setInteractive({ useHandCursor: true });
    hitArea.on('pointerdown', () => togglePurchaseItem(scene, item));
    container.add(hitArea);
    
    return container;
}

function togglePurchaseItem(scene, item) {
    const category = item.category;
    const stateKey = category === 'equipment' ? 'equipment' : category === 'medicine' ? 'medicine' : 'food';
    const isPurchased = gameState[stateKey].includes(item.id);
    
    if (isPurchased) {
        gameState[stateKey] = gameState[stateKey].filter(id => id !== item.id);
        gameState.money += Math.floor(item.price * 0.8);
        gameState.cargoUsed -= item.weight;
        if (item.days) gameState.foodDays -= item.days;
        showMoneyAnimation(scene, Math.floor(item.price * 0.8), true);
    } else {
        if (gameState.money < item.price) {
            showNotification(scene, '❌ Недостаточно денег!', '#e74c3c');
            return;
        }
        if (gameState.cargoUsed + item.weight > GAME_DATA.maxCargoCapacity) {
            showNotification(scene, '❌ Нет места в трюме!', '#e74c3c');
            return;
        }
        
        gameState[stateKey].push(item.id);
        gameState.money -= item.price;
        gameState.cargoUsed += item.weight;
        if (item.days) gameState.foodDays += item.days;
        showMoneyAnimation(scene, -item.price, false);
    }
    
    closeModal(scene, currentModal);
    setTimeout(() => {
        if (category === 'food') openFoodModal(scene);
        else openEquipmentModal(scene);
    }, 200);
}

function addCargoIndicator(scene, modal, yPos) {
    const container = scene.add.container(0, yPos);
    
    const label = scene.add.text(-200, 0, '📦 Загрузка трюма:', { fontSize: '14px', fontFamily: 'Nunito', color: '#ffffff' });
    container.add(label);
    
    const barBg = scene.add.graphics();
    barBg.fillStyle(0x2c3e50, 1);
    barBg.fillRoundedRect(-50, -8, 250, 20, 5);
    container.add(barBg);
    
    const fillPercent = gameState.cargoUsed / GAME_DATA.maxCargoCapacity;
    const fillColor = fillPercent > 0.9 ? 0xe74c3c : fillPercent > 0.7 ? 0xf39c12 : 0x27ae60;
    
    const barFill = scene.add.graphics();
    barFill.fillStyle(fillColor, 1);
    barFill.fillRoundedRect(-50, -8, 250 * fillPercent, 20, 5);
    container.add(barFill);
    
    const cargoText = scene.add.text(210, 0, `${gameState.cargoUsed}/${GAME_DATA.maxCargoCapacity} кг`, { fontSize: '12px', fontFamily: 'Nunito', color: '#ffffff' });
    cargoText.setOrigin(0, 0.5);
    container.add(cargoText);
    
    modal.add(container);
}

function openJobsModal(scene) {
    const modal = createModal(scene, '💼 ДОСКА ОБЪЯВЛЕНИЙ ПОРТА', 1000, 600);
    
    const subtitle = scene.add.text(0, -230, '🔥 Возьмите попутный груз — заработайте дополнительные деньги!', { fontSize: '14px', fontFamily: 'Nunito', color: '#f39c12' });
    subtitle.setOrigin(0.5);
    modal.add(subtitle);
    
    GAME_DATA.jobs.forEach((job, index) => {
        const row = Math.floor(index / 2);
        const col = index % 2;
        const x = -230 + col * 460;
        const y = -170 + row * 140;
        
        const jobCard = createJobCard(scene, job, x, y);
        modal.add(jobCard);
    });
    
    const warning = scene.add.text(0, 250, '⚠️ Каждое задание увеличивает нагрузку и может добавить времени к путешествию', { fontSize: '12px', fontFamily: 'Nunito', color: '#95a5a6' });
    warning.setOrigin(0.5);
    modal.add(warning);
}

function createJobCard(scene, job, x, y) {
    const card = scene.add.container(x, y);
    const isAccepted = gameState.jobs.includes(job.id);
    
    const bg = scene.add.graphics();
    bg.fillStyle(isAccepted ? 0x27ae60 : 0x2c3e50, 1);
    bg.fillRoundedRect(0, 0, 440, 120, 12);
    bg.lineStyle(2, isAccepted ? 0x2ecc71 : getRiskColor(job.riskLevel), 1);
    bg.strokeRoundedRect(0, 0, 440, 120, 12);
    card.add(bg);
    
    const icon = scene.add.text(25, 40, job.icon, { fontSize: '40px' });
    icon.setOrigin(0.5);
    card.add(icon);
    
    const title = scene.add.text(60, 10, job.title, { fontSize: '16px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
    card.add(title);
    
    const desc = scene.add.text(60, 32, job.description, { fontSize: '11px', fontFamily: 'Nunito', color: '#bdc3c7', wordWrap: { width: 250 } });
    card.add(desc);
    
    const reward = scene.add.text(60, 65, `💰 Награда: ${formatMoney(job.reward)}`, { fontSize: '14px', fontFamily: 'Nunito', color: '#2ecc71', fontStyle: 'bold' });
    card.add(reward);
    
    const riskColors = { 1: '#27ae60', 2: '#f39c12', 3: '#e74c3c' };
    const risk = scene.add.text(60, 85, `⚠️ Риск: ${job.risk}`, { fontSize: '12px', fontFamily: 'Nunito', color: riskColors[job.riskLevel] });
    card.add(risk);
    
    const req = scene.add.text(200, 85, `📋 ${job.requirement}`, { fontSize: '11px', fontFamily: 'Nunito', color: '#95a5a6' });
    card.add(req);
    
    const details = scene.add.text(320, 15, `⚖️ ${job.cargoWeight}кг\n⏱️ ${job.duration}`, { fontSize: '11px', fontFamily: 'Nunito', color: '#4fc3f7', align: 'right' });
    card.add(details);
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(isAccepted ? 0xe74c3c : 0x3498db, 1);
    btnBg.fillRoundedRect(320, 65, 100, 35, 8);
    card.add(btnBg);
    
    const btnText = scene.add.text(370, 82, isAccepted ? 'Отменить' : 'Взять', { fontSize: '14px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
    btnText.setOrigin(0.5);
    card.add(btnText);
    
    const btnHit = scene.add.rectangle(370, 82, 100, 35, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => toggleJob(scene, job));
    card.add(btnHit);
    
    return card;
}

function toggleJob(scene, job) {
    const isAccepted = gameState.jobs.includes(job.id);
    
    if (isAccepted) {
        gameState.jobs = gameState.jobs.filter(id => id !== job.id);
        gameState.cargoUsed -= job.cargoWeight;
        showNotification(scene, `❌ Отменено: ${job.title}`, '#e74c3c');
    } else {
        if (gameState.cargoUsed + job.cargoWeight > GAME_DATA.maxCargoCapacity) {
            showNotification(scene, '❌ Нет места в трюме для груза!', '#e74c3c');
            return;
        }
        gameState.jobs.push(job.id);
        gameState.cargoUsed += job.cargoWeight;
        showNotification(scene, `✅ Принято: ${job.title}`, '#27ae60');
    }
    
    closeModal(scene, currentModal);
    setTimeout(() => openJobsModal(scene), 200);
}

function getRiskColor(level) {
    return { 1: 0x27ae60, 2: 0xf39c12, 3: 0xe74c3c }[level] || 0x4fc3f7;
}

function checkReadyToDepart(scene) {
    const problems = [];
    
    const requiredCrew = GAME_DATA.crew.filter(m => m.required);
    requiredCrew.forEach(member => {
        if (!gameState.crew.includes(member.id)) {
            problems.push(`Нужен ${member.role}: ${member.name}`);
        }
    });
    
    if (gameState.crew.length < GAME_DATA.minCrewRequired) {
        problems.push(`Мало людей в команде (минимум ${GAME_DATA.minCrewRequired})`);
    }
    
    if (gameState.foodDays < 10) {
        problems.push('Мало продовольствия (минимум на 10 дней)');
    }
    
    if (problems.length > 0) {
        showProblemModal(scene, problems);
    } else {
        showDepartureModal(scene);
    }
}

function showProblemModal(scene, problems) {
    const modal = createModal(scene, '⚠️ НЕ ГОТОВЫ К ОТПЛЫТИЮ', 600, 400);
    
    const intro = scene.add.text(0, -120, 'Для отплытия необходимо:', { fontSize: '16px', fontFamily: 'Nunito', color: '#ffffff' });
    intro.setOrigin(0.5);
    modal.add(intro);
    
    problems.forEach((problem, index) => {
        const text = scene.add.text(-250, -70 + index * 35, `❌ ${problem}`, { fontSize: '14px', fontFamily: 'Nunito', color: '#e74c3c' });
        modal.add(text);
    });
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x3498db, 1);
    btnBg.fillRoundedRect(-60, 120, 120, 40, 10);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 140, 'Понятно', { fontSize: '16px', fontFamily: 'Nunito', color: '#ffffff', fontStyle: 'bold' });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.rectangle(0, 140, 120, 40, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => closeModal(scene, modal));
    modal.add(btnHit);
}

function showDepartureModal(scene) {
    const modal = createModal(scene, '🚢 ГОТОВЫ К ОТПЛЫТИЮ!', 700, 500);
    
    const summary = [
        `💰 Бюджет: ${formatMoney(gameState.money)}`,
        `🧑‍✈️ Экипаж: ${gameState.crew.length} человек`,
        `🍞 Продовольствие: на ${gameState.foodDays} дней`,
        `⚖️ Загрузка: ${gameState.cargoUsed}/${GAME_DATA.maxCargoCapacity} кг`,
        `💼 Подработки: ${gameState.jobs.length}`
    ];
    
    const expectedReward = gameState.jobs.reduce((sum, jobId) => {
        const job = GAME_DATA.jobs.find(j => j.id === jobId);
        return sum + (job ? job.reward : 0);
    }, 0);
    
    if (expectedReward > 0) {
        summary.push(`💵 Ожидаемый заработок: ${formatMoney(expectedReward)}`);
    }
    
    summary.forEach((line, index) => {
        const text = scene.add.text(-200, -120 + index * 35, line, { fontSize: '16px', fontFamily: 'Nunito', color: '#ffffff' });
        modal.add(text);
    });
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x27ae60, 1);
    btnBg.fillRoundedRect(-100, 130, 200, 50, 15);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 155, '⛵ ОТПЛЫТЬ!', { fontSize: '20px', fontFamily: 'Russo One', color: '#ffffff' });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.rectangle(0, 155, 200, 50, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => {
        closeModal(scene, modal);
        console.log('🎮 Состояние экспедиции:', gameState);
        scene.startVoyage();
    });
    modal.add(btnHit);
}

// ═══════════════════════════════════════════════════════════════════════════
// ЗАПУСК ИГРЫ
// ═══════════════════════════════════════════════════════════════════════════

function startPreparation() {
    const controlsBlock = document.getElementById('game-controls');
    controlsBlock.classList.add('game-started');
    
    game = new Phaser.Game(config);
}
</script>
@endpush
@endsection
