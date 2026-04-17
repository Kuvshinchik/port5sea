@extends('layouts.app') {{-- или твой базовый шаблон --}}

@section('content')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>  
@endpush
@push('style')
<style>
        @import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Nunito:wght@400;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a3a 0%, #0d1b2a 50%, #1b263b 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
            padding: 20px;
        }
        
        h1 {
            font-family: 'Russo One', sans-serif;
            color: #4fc3f7;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            text-shadow: 0 2px 10px rgba(79, 195, 247, 0.3);
            letter-spacing: 2px;
        }
        
        #game-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 
                0 0 60px rgba(79, 195, 247, 0.2),
                0 20px 40px rgba(0, 0, 0, 0.4),
                inset 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .instructions {
            max-width: 1200px;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(79, 195, 247, 0.2);
            border-radius: 12px;
            padding: 20px 30px;
            color: #b0bec5;
            text-align: center;
        }
        
        .instructions h3 {
            color: #4fc3f7;
            margin-bottom: 10px;
            font-family: 'Russo One', sans-serif;
        }
        
        .instructions p {
            line-height: 1.6;
        }
        
        .key {
            display: inline-block;
            background: rgba(79, 195, 247, 0.2);
            border: 1px solid rgba(79, 195, 247, 0.4);
            border-radius: 6px;
            padding: 2px 8px;
            margin: 0 4px;
            font-weight: 600;
            color: #4fc3f7;
        }
        
        .depart-button-container {
            max-width: 1200px;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        .btn-depart {
            padding: 15px 50px;
            font-size: 1.3rem;
            font-family: 'Russo One', sans-serif;
            letter-spacing: 1px;
        }
    </style>    
      
@endpush
<div class="container mt-5">
    <h2 class="text-center mb-4">⚓ ПОРТ МУРМАНСК — ПОДГОТОВКА ЭКСПЕДИЦИИ</h2>
    <div id="game-container"></div>
    
    {{-- Кнопка "В путь" между Canvas и инструкциями --}}
    <div class="depart-button-container">
        <button id="btn-depart" class="btn btn-success btn-lg btn-depart">В путь</button>
    </div>
    
    <div class="instructions">
        <h3>🎮 Как играть</h3>
        <p>
            Кликайте по зданиям порта, чтобы нанять команду, купить снаряжение и продовольствие. 
            Возьмите подработку на <span class="key">Доске объявлений</span>, чтобы заработать дополнительные деньги.
            Когда будете готовы — нажмите <span class="key">В путь</span>!
        </p>
    </div>
</div>
@push('scripts')    

    <script>
// ═══════════════════════════════════════════════════════════════════════════
// ДАННЫЕ ИГРЫ
// ═══════════════════════════════════════════════════════════════════════════

const GAME_DATA = {
    // Начальные ресурсы
    initialMoney: 250000,
    minCrewRequired: 6,
    maxCrew: 12,
    maxCargoCapacity: 100,
    
    // Члены экипажа для найма
    crew: [
        {
            id: 'captain',
            name: 'Иван Северов',
            role: 'Капитан',
            roleIcon: '👨‍✈️',
            salary: 2000,
            skill: 3,
            trait: 'Опытный навигатор',
            traitEffect: '+20% к скорости во льдах',
            quote: 'Я проведу наш корабль через любые льды!',
            hired: false,
            required: true,
            portrait: '#3498db'
        },
        {
            id: 'mechanic1',
            name: 'Пётр Гаечкин',
            role: 'Механик',
            roleIcon: '🔧',
            salary: 1500,
            skill: 3,
            trait: 'Экономит топливо',
            traitEffect: '-15% расход топлива',
            quote: 'Я умею чинить двигатель прямо во льдах!',
            hired: false,
            required: true,
            portrait: '#e67e22'
        },
        {
            id: 'mechanic2',
            name: 'Анна Болтова',
            role: 'Механик',
            roleIcon: '🔧',
            salary: 1200,
            skill: 2,
            trait: 'Быстрый ремонт',
            traitEffect: '-30% время ремонта',
            quote: 'Дайте мне гаечный ключ — и корабль будет как новый!',
            hired: false,
            required: false,
            portrait: '#9b59b6'
        },
        {
            id: 'doctor',
            name: 'Елена Айболитова',
            role: 'Врач',
            roleIcon: '👨‍⚕️',
            salary: 1800,
            skill: 3,
            trait: 'Профилактика болезней',
            traitEffect: '-50% шанс болезней',
            quote: 'Здоровье команды — моя главная забота!',
            hired: false,
            required: true,
            portrait: '#1abc9c'
        },
        {
            id: 'cook',
            name: 'Борис Поваров',
            role: 'Кок',
            roleIcon: '👨‍🍳',
            salary: 1000,
            skill: 2,
            trait: 'Экономная готовка',
            traitEffect: '-20% расход еды',
            quote: 'Накормлю всю команду так, что пальчики оближете!',
            hired: false,
            required: true,
            portrait: '#f39c12'
        },
        {
            id: 'navigator',
            name: 'Ольга Компасова',
            role: 'Штурман',
            roleIcon: '🧭',
            salary: 1400,
            skill: 2,
            trait: 'Знание маршрутов',
            traitEffect: '+15% к навигации',
            quote: 'Северный морской путь — моя вторая родина!',
            hired: false,
            required: true,
            portrait: '#e74c3c'
        },
        {
            id: 'radioman',
            name: 'Сергей Волнов',
            role: 'Радист',
            roleIcon: '📻',
            salary: 1100,
            skill: 2,
            trait: 'Связь с берегом',
            traitEffect: '+погодные предупреждения',
            quote: 'Всегда на связи, в любую погоду!',
            hired: false,
            required: true,
            portrait: '#2980b9'
        },
        {
            id: 'sailor1',
            name: 'Николай Морской',
            role: 'Матрос',
            roleIcon: '⚓',
            salary: 800,
            skill: 1,
            trait: 'Крепкий здоровьем',
            traitEffect: '+устойчивость к холоду',
            quote: 'Готов к любой работе на палубе!',
            hired: false,
            required: false,
            portrait: '#34495e'
        },
        {
            id: 'sailor2',
            name: 'Мария Якорева',
            role: 'Матрос',
            roleIcon: '⚓',
            salary: 800,
            skill: 2,
            trait: 'Ловкая',
            traitEffect: '+скорость при швартовке',
            quote: 'Женщина на корабле — к удаче!',
            hired: false,
            required: false,
            portrait: '#8e44ad'
        },
        {
            id: 'sailor3',
            name: 'Алексей Канатов',
            role: 'Матрос',
            roleIcon: '⚓',
            salary: 700,
            skill: 1,
            trait: 'Боится льдов',
            traitEffect: '-настроение во льдах',
            quote: 'Надеюсь, льдов будет не слишком много...',
            hired: false,
            required: false,
            portrait: '#7f8c8d'
        },
        {
            id: 'scientist',
            name: 'Виктор Наукин',
            role: 'Учёный',
            roleIcon: '🔬',
            salary: 1600,
            skill: 3,
            trait: 'Исследователь',
            traitEffect: '+бонусы за открытия',
            quote: 'Каждая экспедиция — это новые открытия!',
            hired: false,
            required: false,
            portrait: '#16a085'
        },
        {
            id: 'diver',
            name: 'Дмитрий Глубинов',
            role: 'Водолаз',
            roleIcon: '🤿',
            salary: 1300,
            skill: 2,
            trait: 'Подводный ремонт',
            traitEffect: '+ремонт без дока',
            quote: 'Под водой — как дома!',
            hired: false,
            required: false,
            portrait: '#0984e3'
        }
    ],
    
    // Снаряжение
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
    
    // Продовольствие
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
    
    // Медикаменты
    medicine: [
        { id: 'firstaid', name: 'Аптечка', icon: '🩹', price: 3000, weight: 2, effect: 'Базовая помощь', category: 'medicine' },
        { id: 'antibiotics', name: 'Антибиотики', icon: '💊', price: 8000, weight: 1, effect: 'Лечение инфекций', category: 'medicine' },
        { id: 'painkillers', name: 'Обезболивающие', icon: '💉', price: 4000, weight: 1, effect: 'Снятие боли', category: 'medicine' },
        { id: 'frostbite', name: 'Мазь от обморожения', icon: '🧴', price: 5000, weight: 2, effect: 'Лечение обморожений', category: 'medicine' },
    ],
    
    // Подработки
    jobs: [
        {
            id: 'job1',
            title: 'Доставка ящиков в Архангельск',
            icon: '📦',
            description: 'Перевезти 20 ящиков с оборудованием для метеостанции',
            reward: 25000,
            risk: 'Низкий',
            riskLevel: 1,
            requirement: 'Свободное место в трюме',
            cargoWeight: 15,
            duration: '+ 0 дней',
            accepted: false
        },
        {
            id: 'job2',
            title: 'Перевозка замороженной рыбы',
            icon: '🐟',
            description: 'Доставить партию свежемороженой трески в Диксон',
            reward: 40000,
            risk: 'Средний',
            riskLevel: 2,
            requirement: 'Нужен холодильник на борту',
            cargoWeight: 25,
            duration: '+ 1 день',
            accepted: false
        },
        {
            id: 'job3',
            title: 'Установка буя',
            icon: '📡',
            description: 'Установить навигационный буй в точке маршрута',
            reward: 15000,
            risk: 'Низкий',
            riskLevel: 1,
            requirement: 'Нужен водолаз',
            cargoWeight: 5,
            duration: '+ 0.5 дня',
            accepted: false
        },
        {
            id: 'job4',
            title: 'Перевозка учёных',
            icon: '🔬',
            description: 'Взять на борт группу исследователей до острова Диксон',
            reward: 35000,
            risk: 'Низкий',
            riskLevel: 1,
            requirement: 'Свободные каюты',
            cargoWeight: 0,
            duration: '+ 2 дня остановка',
            accepted: false
        },
        {
            id: 'job5',
            title: 'Срочная почта',
            icon: '✉️',
            description: 'Доставить важные документы на остров Врангеля',
            reward: 20000,
            risk: 'Высокий',
            riskLevel: 3,
            requirement: 'Быстрый корабль',
            cargoWeight: 1,
            duration: 'Срок ограничен!',
            accepted: false
        }
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
    fuelPercent: 100
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
    scene: {
        preload: preload,
        create: create,
        update: update
    }
};

const game = new Phaser.Game(config);

let currentModal = null;
let statusPanel = null;
let moneyAnimations = [];
let gameScene = null; // Сохраняем ссылку на сцену для кнопки

// ═══════════════════════════════════════════════════════════════════════════
// PRELOAD
// ═══════════════════════════════════════════════════════════════════════════

function preload() {
    // Загрузка изображения фона порта
    this.load.image('port_bg', 'http://port5sea/assets/game/murmansk/port/port.png');
    
    // Загрузка PNG изображений зданий
    this.load.image('locker1', 'assets/game/murmansk/port/locker1.png');
    this.load.image('locker2', 'assets/game/murmansk/port/locker2.png');
    this.load.image('locker3', 'assets/game/murmansk/port/locker3.png');
    this.load.image('locker4', 'assets/game/murmansk/port/locker4.png');
}

// ═══════════════════════════════════════════════════════════════════════════
// CREATE - ОСНОВНАЯ СЦЕНА ПОРТА
// ═══════════════════════════════════════════════════════════════════════════

function create() {
    const scene = this;
    gameScene = scene; // Сохраняем ссылку для кнопки
    
    // Фон порта - загружаем изображение
    const portBg = scene.add.image(600, 400, 'port_bg');
    portBg.setDisplaySize(1200, 800);
    
    // Создаём здания (теперь с PNG изображениями)
    createBuildings(scene);
    
    // Панель состояния (всегда видна)
    createStatusPanel(scene);
    
    // Декоративные элементы
    createDecorations(scene);
    
    // Привязываем обработчик к внешней кнопке Bootstrap
    document.getElementById('btn-depart').addEventListener('click', function() {
        checkReadyToDepart(gameScene);
    });
}

// ═══════════════════════════════════════════════════════════════════════════
// ФОН ПОРТА
// ═══════════════════════════════════════════════════════════════════════════

function createPortBackground(scene) {

    // Градиентный фон неба
    const skyGradient = scene.add.graphics();
    skyGradient.fillGradientStyle(0x87CEEB, 0x87CEEB, 0x4a90a4, 0x4a90a4, 1);
    skyGradient.fillRect(0, 0, 1200, 400);
    
    // Горы на заднем плане
    const mountains = scene.add.graphics();
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
    mountains.beginPath();
    mountains.moveTo(450, 250);
    mountains.lineTo(430, 280);
    mountains.lineTo(470, 280);
    mountains.closePath();
    mountains.fill();
    
    mountains.beginPath();
    mountains.moveTo(750, 230);
    mountains.lineTo(725, 265);
    mountains.lineTo(775, 265);
    mountains.closePath();
    mountains.fill();
    
    // Море
    const sea = scene.add.graphics();
    sea.fillGradientStyle(0x2980b9, 0x2980b9, 0x1a5276, 0x1a5276, 1);
    sea.fillRect(0, 400, 1200, 400);
    
    // Волны
    for (let i = 0; i < 8; i++) {
        const waveY = 420 + i * 45;
        const wave = scene.add.graphics();
        wave.lineStyle(2, 0x3498db, 0.3 - i * 0.03);
        wave.beginPath();
        for (let x = 0; x <= 1200; x += 10) {
            const y = waveY + Math.sin(x * 0.02 + i) * 8;
            if (x === 0) wave.moveTo(x, y);
            else wave.lineTo(x, y);
        }
        wave.strokePath();
        
        // Анимация волн
        scene.tweens.add({
            targets: wave,
            x: -50,
            duration: 3000 + i * 500,
            repeat: -1,
            yoyo: true,
            ease: 'Sine.easeInOut'
        });
    }
    
    // Причал
    const dock = scene.add.graphics();
    dock.fillStyle(0x6d4c41, 1);
    dock.fillRect(50, 550, 400, 30);
    dock.fillStyle(0x5d4037, 1);
    dock.fillRect(50, 580, 400, 20);
    
    // Столбы причала
    for (let i = 0; i < 5; i++) {
        dock.fillStyle(0x4e342e, 1);
        dock.fillRect(70 + i * 95, 580, 15, 60);
    }
    
    // Земля/набережная
    const ground = scene.add.graphics();
    ground.fillStyle(0x7f8c8d, 1);
    ground.fillRect(0, 600, 1200, 200);
    
    // Текстура земли
    ground.fillStyle(0x6c7a7a, 1);
    for (let i = 0; i < 20; i++) {
        ground.fillRect(Math.random() * 1200, 620 + Math.random() * 160, 30 + Math.random() * 50, 5);
    }
    
    // Заголовок
    const titleBg = scene.add.graphics();
    titleBg.fillStyle(0x000000, 0.5);
    titleBg.fillRoundedRect(400, 15, 400, 50, 10);
    
    const title = scene.add.text(600, 40, '🏭 ПОРТ МУРМАНСК', {
        fontSize: '28px',
        fontFamily: 'Russo One, Arial',
        color: '#ffffff',
        stroke: '#000000',
        strokeThickness: 2
    });
    title.setOrigin(0.5);
}

// ═══════════════════════════════════════════════════════════════════════════
// ЗДАНИЯ (с PNG изображениями)
// ═══════════════════════════════════════════════════════════════════════════

function createBuildings(scene) {
    // Равномерное распределение 4 зданий по ширине 1200px
    // Здания размещены на уровне, где были бочки (y ~ 620)
    const buildings = [
        {
            x: 200, y: 620,
            imageKey: 'locker1',
            action: () => openCrewModal(scene)
        },
        {
            x: 450, y: 620,
            imageKey: 'locker2',
            action: () => openEquipmentModal(scene)
        },
        {
            x: 700, y: 620,
            imageKey: 'locker3',
            action: () => openFoodModal(scene)
        },
        {
            x: 950, y: 620,
            imageKey: 'locker4',
            action: () => openJobsModal(scene)
        }
    ];
    
    buildings.forEach(b => createBuildingFromImage(scene, b));
}

function createBuildingFromImage(scene, config) {
    const container = scene.add.container(config.x, config.y);
    
    // Добавляем PNG изображение
    const buildingImage = scene.add.image(0, 0, config.imageKey);
    buildingImage.setOrigin(0.5, 1); // Центр внизу для правильного позиционирования
    container.add(buildingImage);
    
    // Получаем размеры изображения для хитбокса
    const width = buildingImage.width;
    const height = buildingImage.height;
    
    // Интерактивность - хитбокс по размеру изображения
    const hitArea = scene.add.rectangle(0, -height / 2, width, height, 0xffffff, 0);
    hitArea.setInteractive({ useHandCursor: true });
    container.add(hitArea);
    
    hitArea.on('pointerover', () => {
        scene.tweens.add({
            targets: container,
            scaleX: 1.05,
            scaleY: 1.05,
            y: config.y - 10,
            duration: 150,
            ease: 'Back.easeOut'
        });
    });
    
    hitArea.on('pointerout', () => {
        scene.tweens.add({
            targets: container,
            scaleX: 1,
            scaleY: 1,
            y: config.y,
            duration: 150,
            ease: 'Back.easeIn'
        });
    });
    
    hitArea.on('pointerdown', () => {
        scene.tweens.add({
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

// ═══════════════════════════════════════════════════════════════════════════
// ДЕКОРАЦИИ
// ═══════════════════════════════════════════════════════════════════════════

function createDecorations(scene) {
    // Падающий снег с использованием эффекта частиц
    createSnowEffect(scene);
}

function createSnowEffect(scene) {
    // Создаём текстуру снежинки программно
    const snowGraphics = scene.make.graphics({ x: 0, y: 0, add: false });
    snowGraphics.fillStyle(0xffffff, 1);
    snowGraphics.fillCircle(4, 4, 4);
    snowGraphics.generateTexture('snowflake', 8, 8);
    snowGraphics.destroy();
    
    // Создаём эмиттер частиц для снега
    const snowEmitter = scene.add.particles(0, -10, 'snowflake', {
        x: { min: 0, max: 1200 },
        y: -10,
        lifespan: 8000,
        speedY: { min: 30, max: 80 },
        speedX: { min: -20, max: 20 },
        scale: { min: 0.2, max: 0.8 },
        alpha: { start: 0.8, end: 0.3 },
        rotate: { min: 0, max: 360 },
        frequency: 50,
        quantity: 2,
        blendMode: 'ADD'
    });
    
    // Устанавливаем глубину, чтобы снег был поверх фона, но под UI
    snowEmitter.setDepth(100);
}

// ═══════════════════════════════════════════════════════════════════════════
// ПАНЕЛЬ СОСТОЯНИЯ
// ═══════════════════════════════════════════════════════════════════════════

function createStatusPanel(scene) {
    statusPanel = scene.add.container(600, 760);
    
    // Фон панели
    const panelBg = scene.add.graphics();
    panelBg.fillStyle(0x1a1a2e, 0.95);
    panelBg.fillRoundedRect(-580, -35, 1160, 70, 15);
    panelBg.lineStyle(2, 0x4fc3f7, 0.5);
    panelBg.strokeRoundedRect(-580, -35, 1160, 70, 15);
    statusPanel.add(panelBg);
    
    // Элементы статуса
    const items = [
        { key: 'money', icon: '💰', label: 'Деньги', getValue: () => formatMoney(gameState.money) },
        { key: 'crew', icon: '🧑‍✈️', label: 'Экипаж', getValue: () => `${gameState.crew.length} / ${GAME_DATA.maxCrew}` },
        { key: 'food', icon: '🍞', label: 'Еда', getValue: () => `${gameState.foodDays} дней` },
        { key: 'fuel', icon: '⛽', label: 'Топливо', getValue: () => `${gameState.fuelPercent}%` },
        { key: 'cargo', icon: '⚖️', label: 'Груз', getValue: () => `${gameState.cargoUsed} / ${GAME_DATA.maxCargoCapacity}` },
        { key: 'morale', icon: '😊', label: 'Мораль', getValue: () => getMoraleEmoji() }
    ];
    
    const startX = -500;
    const spacing = 190;
    
    items.forEach((item, index) => {
        const x = startX + index * spacing;
        
        // Иконка
        const icon = scene.add.text(x, -5, item.icon, { fontSize: '24px' });
        icon.setOrigin(0.5);
        statusPanel.add(icon);
        
        // Метка
        const label = scene.add.text(x + 25, -15, item.label, {
            fontSize: '12px',
            fontFamily: 'Nunito, Arial',
            color: '#aaaaaa'
        });
        statusPanel.add(label);
        
        // Значение
        const value = scene.add.text(x + 25, 5, item.getValue(), {
            fontSize: '14px',
            fontFamily: 'Nunito, Arial',
            color: '#ffffff',
            fontStyle: 'bold'
        });
        value.setData('updateFunc', item.getValue);
        value.setData('key', item.key);
        statusPanel.add(value);
    });
}

function updateStatusPanel() {
    if (!statusPanel) return;
    
    statusPanel.list.forEach(child => {
        if (child.getData && child.getData('updateFunc')) {
            child.setText(child.getData('updateFunc')());
        }
    });
}

// ═══════════════════════════════════════════════════════════════════════════
// МОДАЛЬНЫЕ ОКНА
// ═══════════════════════════════════════════════════════════════════════════

function createModal(scene, title, width, height) {
    // Закрываем предыдущее модальное окно
    if (currentModal) {
        closeModal(scene, currentModal);
    }
    
    const modal = scene.add.container(600, 400);
    modal.setDepth(1000);
    
    // Затемнение фона
    const overlay = scene.add.rectangle(0, 0, 1200, 800, 0x000000, 0.7);
    overlay.setInteractive();
    modal.add(overlay);
    
    // Основной контейнер модального окна
    const modalBg = scene.add.graphics();
    modalBg.fillStyle(0x1a1a2e, 0.98);
    modalBg.fillRoundedRect(-width/2, -height/2, width, height, 20);
    modalBg.lineStyle(3, 0x4fc3f7, 0.8);
    modalBg.strokeRoundedRect(-width/2, -height/2, width, height, 20);
    modal.add(modalBg);
    
    // Заголовок
    const titleText = scene.add.text(0, -height/2 + 30, title, {
        fontSize: '24px',
        fontFamily: 'Russo One, Arial',
        color: '#4fc3f7'
    });
    titleText.setOrigin(0.5);
    modal.add(titleText);
    
    // Кнопка закрытия
    const closeBtn = scene.add.text(width/2 - 30, -height/2 + 15, '✕', {
        fontSize: '28px',
        color: '#e74c3c'
    });
    closeBtn.setOrigin(0.5);
    closeBtn.setInteractive({ useHandCursor: true });
    closeBtn.on('pointerdown', () => closeModal(scene, modal));
    closeBtn.on('pointerover', () => closeBtn.setColor('#ff6b6b'));
    closeBtn.on('pointerout', () => closeBtn.setColor('#e74c3c'));
    modal.add(closeBtn);
    
    // Анимация появления
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
    if (!modal) return;
    
    scene.tweens.add({
        targets: modal,
        scaleX: 0.8,
        scaleY: 0.8,
        alpha: 0,
        duration: 150,
        ease: 'Back.easeIn',
        onComplete: () => {
            modal.destroy();
            if (currentModal === modal) {
                currentModal = null;
            }
        }
    });
}

// ═══════════════════════════════════════════════════════════════════════════
// МОДАЛЬНОЕ ОКНО ЭКИПАЖА
// ═══════════════════════════════════════════════════════════════════════════

function openCrewModal(scene) {
    const modal = createModal(scene, '🧑‍✈️ КАДРОВОЕ АГЕНТСТВО', 900, 600);
    
    // Создаём скроллируемый список
    const startY = -200;
    const cardHeight = 80;
    const visibleCards = 5;
    
    GAME_DATA.crew.forEach((member, index) => {
        const card = createCrewCard(scene, member, index, startY, cardHeight);
        modal.add(card);
    });
}

function createCrewCard(scene, member, index, startY, cardHeight) {
    const card = scene.add.container(0, startY + index * (cardHeight + 10));
    
    const isHired = gameState.crew.includes(member.id);
    
    // Фон карточки
    const bg = scene.add.graphics();
    bg.fillStyle(isHired ? 0x27ae60 : 0x2a2a4a, 1);
    bg.fillRoundedRect(-400, -35, 800, cardHeight, 10);
    if (member.required) {
        bg.lineStyle(2, 0xf39c12, 1);
        bg.strokeRoundedRect(-400, -35, 800, cardHeight, 10);
    }
    card.add(bg);
    
    // Портрет (цветной круг)
    const portrait = scene.add.graphics();
    portrait.fillStyle(parseInt(member.portrait.replace('#', '0x')), 1);
    portrait.fillCircle(-350, 5, 25);
    card.add(portrait);
    
    // Иконка роли
    const roleIcon = scene.add.text(-350, 5, member.roleIcon, { fontSize: '20px' });
    roleIcon.setOrigin(0.5);
    card.add(roleIcon);
    
    // Имя и роль
    const nameText = scene.add.text(-310, -15, member.name, {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    card.add(nameText);
    
    const roleText = scene.add.text(-310, 8, `${member.role} • ${member.trait}`, {
        fontSize: '12px',
        fontFamily: 'Nunito, Arial',
        color: '#aaaaaa'
    });
    card.add(roleText);
    
    // Зарплата
    const salaryText = scene.add.text(100, -5, `💰 ${formatMoney(member.salary)}/день`, {
        fontSize: '14px',
        fontFamily: 'Nunito, Arial',
        color: '#f1c40f'
    });
    card.add(salaryText);
    
    // Навык
    const skillStars = '⭐'.repeat(member.skill);
    const skillText = scene.add.text(250, -5, skillStars, { fontSize: '14px' });
    card.add(skillText);
    
    // Кнопка найма/увольнения
    const btnColor = isHired ? 0xe74c3c : 0x3498db;
    const btnText = isHired ? 'Уволить' : 'Нанять';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(320, -18, 70, 36, 8);
    card.add(btn);
    
    const btnLabel = scene.add.text(355, 0, btnText, {
        fontSize: '12px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    // Хитбокс кнопки
    const btnHit = scene.add.rectangle(355, 0, 70, 36, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => {
        toggleCrewMember(scene, member);
    });
    card.add(btnHit);
    
    // Метка "Обязательно"
    if (member.required && !isHired) {
        const reqLabel = scene.add.text(-400, -30, '⚠️ Обязательно', {
            fontSize: '10px',
            fontFamily: 'Nunito, Arial',
            color: '#f39c12'
        });
        card.add(reqLabel);
    }
    
    return card;
}

function toggleCrewMember(scene, member) {
    const isHired = gameState.crew.includes(member.id);
    
    if (isHired) {
        gameState.crew = gameState.crew.filter(id => id !== member.id);
        showNotification(scene, `❌ ${member.name} уволен(а)`, '#e74c3c');
    } else {
        if (gameState.crew.length >= GAME_DATA.maxCrew) {
            showNotification(scene, '❌ Экипаж укомплектован!', '#e74c3c');
            return;
        }
        gameState.crew.push(member.id);
        showNotification(scene, `✅ ${member.name} нанят(а)!`, '#27ae60');
    }
    
    updateStatusPanel();
    
    // Перерисовываем модальное окно
    closeModal(scene, currentModal);
    setTimeout(() => openCrewModal(scene), 200);
}

// ═══════════════════════════════════════════════════════════════════════════
// МОДАЛЬНОЕ ОКНО СНАРЯЖЕНИЯ
// ═══════════════════════════════════════════════════════════════════════════

function openEquipmentModal(scene) {
    const modal = createModal(scene, '🧰 СКЛАД СНАРЯЖЕНИЯ', 800, 550);
    
    const startY = -180;
    const cardHeight = 55;
    
    GAME_DATA.equipment.forEach((item, index) => {
        const card = createItemCard(scene, item, index, startY, cardHeight, 'equipment');
        modal.add(card);
    });
    
    // Итого
    const totalWeight = gameState.equipment.reduce((sum, id) => {
        const item = GAME_DATA.equipment.find(e => e.id === id);
        return sum + (item ? item.weight : 0);
    }, 0);
    
    const totalText = scene.add.text(0, 220, `⚖️ Вес снаряжения: ${totalWeight} кг`, {
        fontSize: '14px',
        fontFamily: 'Nunito, Arial',
        color: '#4fc3f7'
    });
    totalText.setOrigin(0.5);
    modal.add(totalText);
}

function openFoodModal(scene) {
    const modal = createModal(scene, '🍞 ПРОДОВОЛЬСТВЕННЫЙ СКЛАД', 800, 600);
    
    const startY = -200;
    const cardHeight = 55;
    
    // Продукты
    GAME_DATA.food.forEach((item, index) => {
        const card = createItemCard(scene, item, index, startY, cardHeight, 'food');
        modal.add(card);
    });
    
    // Разделитель
    const divider = scene.add.graphics();
    divider.lineStyle(1, 0x4fc3f7, 0.3);
    divider.lineBetween(-350, 160, 350, 160);
    modal.add(divider);
    
    // Медикаменты
    const medTitle = scene.add.text(0, 175, '💊 Медикаменты', {
        fontSize: '16px',
        fontFamily: 'Russo One, Arial',
        color: '#e74c3c'
    });
    medTitle.setOrigin(0.5);
    modal.add(medTitle);
    
    GAME_DATA.medicine.forEach((item, index) => {
        const card = createItemCard(scene, item, index, 195, 45, 'medicine');
        modal.add(card);
    });
}

function createItemCard(scene, item, index, startY, cardHeight, category) {
    const card = scene.add.container(0, startY + index * (cardHeight + 5));
    
    const isPurchased = gameState[category].includes(item.id);
    
    // Фон
    const bg = scene.add.graphics();
    bg.fillStyle(isPurchased ? 0x27ae60 : 0x2a2a4a, 1);
    bg.fillRoundedRect(-350, -cardHeight/2 + 5, 700, cardHeight - 5, 8);
    card.add(bg);
    
    // Иконка
    const icon = scene.add.text(-320, 5, item.icon, { fontSize: '24px' });
    icon.setOrigin(0.5);
    card.add(icon);
    
    // Название
    const name = scene.add.text(-290, -5, item.name, {
        fontSize: '14px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    card.add(name);
    
    // Эффект
    const effect = scene.add.text(-290, 12, item.effect, {
        fontSize: '11px',
        fontFamily: 'Nunito, Arial',
        color: '#aaaaaa'
    });
    card.add(effect);
    
    // Цена
    const priceColor = gameState.money >= item.price ? '#f1c40f' : '#e74c3c';
    const price = scene.add.text(150, 5, formatMoney(item.price), {
        fontSize: '14px',
        fontFamily: 'Nunito, Arial',
        color: priceColor
    });
    price.setOrigin(0.5);
    card.add(price);
    
    // Вес
    const weight = scene.add.text(230, 5, `${item.weight}кг`, {
        fontSize: '12px',
        fontFamily: 'Nunito, Arial',
        color: '#95a5a6'
    });
    weight.setOrigin(0.5);
    card.add(weight);
    
    // Кнопка
    const btnColor = isPurchased ? 0xe74c3c : 0x3498db;
    const btnText = isPurchased ? '✕' : '+';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(290, -12, 40, 30, 6);
    card.add(btn);
    
    const btnLabel = scene.add.text(310, 3, btnText, {
        fontSize: '18px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    const btnHit = scene.add.rectangle(310, 3, 40, 30, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => {
        toggleItem(scene, item, category);
    });
    card.add(btnHit);
    
    return card;
}

function toggleItem(scene, item, category) {
    const isPurchased = gameState[category].includes(item.id);
    
    if (isPurchased) {
        // Продаём (возврат 80%)
        const refund = Math.floor(item.price * 0.8);
        gameState.money += refund;
        gameState[category] = gameState[category].filter(id => id !== item.id);
        gameState.cargoUsed -= item.weight;
        
        if (item.days) {
            gameState.foodDays -= item.days;
        }
        
        showNotification(scene, `💰 Возврат: ${formatMoney(refund)}`, '#e67e22');
    } else {
        // Покупаем
        if (gameState.money < item.price) {
            showNotification(scene, '❌ Недостаточно денег!', '#e74c3c');
            return;
        }
        
        if (gameState.cargoUsed + item.weight > GAME_DATA.maxCargoCapacity) {
            showNotification(scene, '❌ Нет места в трюме!', '#e74c3c');
            return;
        }
        
        gameState.money -= item.price;
        gameState[category].push(item.id);
        gameState.cargoUsed += item.weight;
        
        if (item.days) {
            gameState.foodDays += item.days;
        }
        
        showNotification(scene, `✅ Куплено: ${item.name}`, '#27ae60');
        showMoneyAnimation(scene, item.price, false);
    }
    
    updateStatusPanel();
    
    // Перерисовываем
    closeModal(scene, currentModal);
    setTimeout(() => {
        if (category === 'equipment') openEquipmentModal(scene);
        else openFoodModal(scene);
    }, 200);
}

// ═══════════════════════════════════════════════════════════════════════════
// МОДАЛЬНОЕ ОКНО ПОДРАБОТОК
// ═══════════════════════════════════════════════════════════════════════════

function openJobsModal(scene) {
    const modal = createModal(scene, '💼 ДОСКА ОБЪЯВЛЕНИЙ', 850, 600);
    
    const startY = -200;
    const cardHeight = 100;
    
    GAME_DATA.jobs.forEach((job, index) => {
        const card = createJobCard(scene, job, index, startY, cardHeight);
        modal.add(card);
    });
}

function createJobCard(scene, job, index, startY, cardHeight) {
    const card = scene.add.container(0, startY + index * (cardHeight + 10));
    
    const isAccepted = gameState.jobs.includes(job.id);
    
    // Фон
    const bg = scene.add.graphics();
    bg.fillStyle(isAccepted ? 0x27ae60 : 0x2a2a4a, 1);
    bg.fillRoundedRect(-380, -cardHeight/2, 760, cardHeight, 10);
    bg.lineStyle(2, getRiskColor(job.riskLevel), 0.5);
    bg.strokeRoundedRect(-380, -cardHeight/2, 760, cardHeight, 10);
    card.add(bg);
    
    // Иконка
    const icon = scene.add.text(-340, 0, job.icon, { fontSize: '32px' });
    icon.setOrigin(0.5);
    card.add(icon);
    
    // Название
    const title = scene.add.text(-300, -25, job.title, {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    card.add(title);
    
    // Описание
    const desc = scene.add.text(-300, -3, job.description, {
        fontSize: '11px',
        fontFamily: 'Nunito, Arial',
        color: '#aaaaaa',
        wordWrap: { width: 350 }
    });
    card.add(desc);
    
    // Награда
    const reward = scene.add.text(130, -20, `💰 ${formatMoney(job.reward)}`, {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#2ecc71',
        fontStyle: 'bold'
    });
    card.add(reward);
    
    // Риск
    const riskColors = { 1: '#27ae60', 2: '#f39c12', 3: '#e74c3c' };
    const risk = scene.add.text(130, 5, `⚠️ ${job.risk}`, {
        fontSize: '12px',
        fontFamily: 'Nunito, Arial',
        color: riskColors[job.riskLevel]
    });
    card.add(risk);
    
    // Груз
    const cargo = scene.add.text(130, 22, `📦 ${job.cargoWeight}кг • ${job.duration}`, {
        fontSize: '11px',
        fontFamily: 'Nunito, Arial',
        color: '#95a5a6'
    });
    card.add(cargo);
    
    // Кнопка
    const btnColor = isAccepted ? 0xe74c3c : 0x3498db;
    const btnText = isAccepted ? 'Отменить' : 'Принять';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(290, -18, 80, 36, 8);
    card.add(btn);
    
    const btnLabel = scene.add.text(330, 0, btnText, {
        fontSize: '13px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    const btnHit = scene.add.rectangle(330, 0, 80, 36, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => {
        toggleJob(scene, job);
    });
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
    
    // Перерисовываем
    closeModal(scene, currentModal);
    setTimeout(() => openJobsModal(scene), 200);
}

function getRiskColor(level) {
    const colors = { 1: 0x27ae60, 2: 0xf39c12, 3: 0xe74c3c };
    return colors[level] || 0x4fc3f7;
}

// ═══════════════════════════════════════════════════════════════════════════
// ПРОВЕРКА ГОТОВНОСТИ К ОТПЛЫТИЮ
// ═══════════════════════════════════════════════════════════════════════════

function checkReadyToDepart(scene) {
    const problems = [];
    
    // Проверка экипажа
    const requiredCrew = GAME_DATA.crew.filter(m => m.required);
    requiredCrew.forEach(member => {
        if (!gameState.crew.includes(member.id)) {
            problems.push(`Нужен ${member.role}: ${member.name}`);
        }
    });
    
    if (gameState.crew.length < GAME_DATA.minCrewRequired) {
        problems.push(`Мало людей в команде (минимум ${GAME_DATA.minCrewRequired})`);
    }
    
    // Проверка еды
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
    
    const intro = scene.add.text(0, -120, 'Для отплытия необходимо:', {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff'
    });
    intro.setOrigin(0.5);
    modal.add(intro);
    
    problems.forEach((problem, index) => {
        const text = scene.add.text(-250, -70 + index * 35, `❌ ${problem}`, {
            fontSize: '14px',
            fontFamily: 'Nunito, Arial',
            color: '#e74c3c'
        });
        modal.add(text);
    });
    
    // Кнопка "Понятно"
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x3498db, 1);
    btnBg.fillRoundedRect(-60, 120, 120, 40, 10);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 140, 'Понятно', {
        fontSize: '16px',
        fontFamily: 'Nunito, Arial',
        color: '#ffffff',
        fontStyle: 'bold'
    });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.rectangle(0, 140, 120, 40, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => closeModal(scene, modal));
    modal.add(btnHit);
}

function showDepartureModal(scene) {
    const modal = createModal(scene, '🚢 ГОТОВЫ К ОТПЛЫТИЮ!', 700, 500);
    
    // Сводка
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
        const text = scene.add.text(-200, -120 + index * 35, line, {
            fontSize: '16px',
            fontFamily: 'Nunito, Arial',
            color: '#ffffff'
        });
        modal.add(text);
    });
    
    // Кнопка отплытия
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x27ae60, 1);
    btnBg.fillRoundedRect(-100, 130, 200, 50, 15);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 155, '⛵ ОТПЛЫТЬ!', {
        fontSize: '20px',
        fontFamily: 'Russo One, Arial',
        color: '#ffffff'
    });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.rectangle(0, 155, 200, 50, 0xffffff, 0);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => {
        // Здесь переход к следующей сцене путешествия
        closeModal(scene, modal);
        showNotification(scene, '🚢 Отправляемся в путь! (переход к путешествию...)', '#27ae60');
        
        // Сохраняем состояние игры (можно передать в следующую сцену)
        console.log('🎮 Состояние экспедиции:', gameState);
        console.log('🎮 Ожидаемый заработок от подработок:', expectedReward);
    });
    modal.add(btnHit);
}

// ═══════════════════════════════════════════════════════════════════════════
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ═══════════════════════════════════════════════════════════════════════════

function formatMoney(amount) {
    return amount.toLocaleString('ru-RU') + ' ₽';
}

function darkenColor(color, percent) {
    const r = (color >> 16) & 0xFF;
    const g = (color >> 8) & 0xFF;
    const b = color & 0xFF;
    
    const factor = (100 - percent) / 100;
    
    const newR = Math.floor(r * factor);
    const newG = Math.floor(g * factor);
    const newB = Math.floor(b * factor);
    
    return (newR << 16) | (newG << 8) | newB;
}

function getMoraleEmoji() {
    if (gameState.morale >= 80) return '😊 Отлично';
    if (gameState.morale >= 60) return '🙂 Хорошо';
    if (gameState.morale >= 40) return '😐 Нормально';
    if (gameState.morale >= 20) return '😟 Плохо';
    return '😢 Ужасно';
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

function update() {
    // Обновляем статус панели каждый кадр (или по событиям)
}
    </script>    
	
	
	
@endpush
@endsection
