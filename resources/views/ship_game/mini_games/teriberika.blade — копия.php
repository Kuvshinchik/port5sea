@extends('layouts.app')

@section('content')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>
@endpush

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Nunito:wght@400;600;700;800&display=swap');
    
    .game-wrapper {
        background: linear-gradient(135deg, #1a3a4a 0%, #0d2b3a 50%, #1b3b4b 100%);
        min-height: 100vh;
        padding: 20px;
    }
    
    h2.game-title {
        font-family: 'Russo One', sans-serif;
        color: #7dd3fc;
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.8rem;
        text-shadow: 0 2px 10px rgba(125, 211, 252, 0.3);
    }
    
    #game-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 60px rgba(125, 211, 252, 0.2), 0 20px 40px rgba(0, 0, 0, 0.4);
        margin: 0 auto;
        max-width: 1000px;
        position: relative;
    }
    
    .game-hud {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        pointer-events: none;
        z-index: 100;
    }
    
    .hud-panel {
        background: rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(125, 211, 252, 0.5);
        border-radius: 12px;
        padding: 10px 15px;
        color: white;
        font-family: 'Nunito', sans-serif;
        backdrop-filter: blur(5px);
    }
    
    .hud-panel h4 {
        font-size: 0.9rem;
        color: #7dd3fc;
        margin: 0 0 5px 0;
    }
    
    .hud-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #fbbf24;
    }
    
    .tide-indicator {
        width: 40px;
        height: 150px;
        background: rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(125, 211, 252, 0.5);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .tide-fill {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, #3b82f6, #60a5fa);
        transition: height 0.5s ease;
        border-radius: 0 0 18px 18px;
    }
    
    .tide-label {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-size: 0.8rem;
        color: #7dd3fc;
    }
    
    .cook-reaction {
        position: absolute;
        bottom: 10px;
        left: 10px;
        width: 120px;
        height: 150px;
        background: rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(125, 211, 252, 0.5);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 100;
    }
    
    .cook-sprite {
        font-size: 3rem;
        animation: cook-idle 2s ease-in-out infinite;
    }
    
    .cook-sprite.happy { animation: cook-happy 0.5s ease; }
    .cook-sprite.sad { animation: cook-sad 0.5s ease; }
    
    @keyframes cook-idle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    @keyframes cook-happy {
        0% { transform: scale(1); }
        50% { transform: scale(1.2) rotate(10deg); }
        100% { transform: scale(1); }
    }
    
    @keyframes cook-sad {
        0% { transform: rotate(0); }
        25% { transform: rotate(-15deg); }
        75% { transform: rotate(15deg); }
        100% { transform: rotate(0); }
    }
    
    .cook-text {
        font-size: 0.75rem;
        color: white;
        text-align: center;
        margin-top: 5px;
        padding: 0 5px;
    }
    
    .dialog-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .dialog-box {
        background: linear-gradient(135deg, #1e3a5f, #0f2847);
        border: 3px solid #7dd3fc;
        border-radius: 20px;
        padding: 30px;
        max-width: 600px;
        text-align: center;
        box-shadow: 0 0 50px rgba(125, 211, 252, 0.3);
        animation: dialogAppear 0.3s ease-out;
    }
    
    @keyframes dialogAppear {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    .dialog-character {
        font-size: 5rem;
        margin-bottom: 15px;
    }
    
    .dialog-title {
        font-family: 'Russo One', sans-serif;
        color: #fbbf24;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    
    .dialog-text {
        color: #e0f2fe;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }
    
    .btn-start {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        padding: 15px 40px;
        font-size: 1.3rem;
        font-family: 'Russo One', sans-serif;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
        transition: all 0.3s ease;
    }
    
    .btn-start:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
    }
    
    .result-box {
        background: linear-gradient(135deg, #1e3a5f, #0f2847);
        border: 3px solid;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        text-align: center;
    }
    
    .result-box.success { border-color: #22c55e; }
    .result-box.failure { border-color: #ef4444; }
    
    .result-title {
        font-family: 'Russo One', sans-serif;
        font-size: 2rem;
        margin-bottom: 20px;
    }
    
    .result-title.success { color: #22c55e; }
    .result-title.failure { color: #ef4444; }
    
    .result-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
    }
    
    .stat-label { color: #94a3b8; font-size: 0.9rem; }
    .stat-value { color: #fbbf24; font-size: 1.5rem; font-weight: bold; }
    
    .rewards-section {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.5);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .rewards-title {
        color: #22c55e;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .reward-item {
        color: white;
        font-size: 1.1rem;
    }
    
    .seagull-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(251, 191, 36, 0.2);
        border: 2px solid #fbbf24;
        border-radius: 10px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 100;
    }
    
    .seagull-icon { font-size: 1.5rem; }
    .seagull-text { color: #fbbf24; font-weight: bold; font-size: 0.9rem; }
    
    .instructions {
        max-width: 1000px;
        margin: 20px auto 0;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(125, 211, 252, 0.2);
        border-radius: 12px;
        padding: 20px 30px;
        color: #b0bec5;
        text-align: center;
    }
    
    .instructions h3 { 
        color: #7dd3fc; 
        margin-bottom: 10px; 
        font-family: 'Russo One', sans-serif; 
    }
    
    .zone-legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 15px;
    }
    
    .zone-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .zone-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    .zone-far { background: linear-gradient(135deg, #dc2626, #ef4444); }
    .zone-middle { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .zone-near { background: linear-gradient(135deg, #22c55e, #4ade80); }
    
    .back-button {
        display: block;
        max-width: 200px;
        margin: 20px auto;
        text-align: center;
        padding: 12px 25px;
        background: rgba(125, 211, 252, 0.2);
        border: 1px solid #7dd3fc;
        color: #7dd3fc;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .back-button:hover {
        background: rgba(125, 211, 252, 0.3);
        color: white;
    }
</style>
@endpush

<div class="game-wrapper">
    <div class="container">
        <h2 class="game-title">🦪 Сбор моллюсков в Териберке</h2>
        
        <div id="game-container">
            <!-- HUD будет добавлен динамически -->
        </div>
        
        <div class="instructions">
            <h3>🎮 Как играть</h3>
            <p>
                Кликайте на моллюсков, чтобы собрать их до того, как их зальёт приливом!<br>
                Начинайте с дальней зоны — там самые ценные, но вода придёт туда первой.
            </p>
            <div class="zone-legend">
                <div class="zone-item">
                    <div class="zone-color zone-far"></div>
                    <span>Дальняя (×3 очка)</span>
                </div>
                <div class="zone-item">
                    <div class="zone-color zone-middle"></div>
                    <span>Средняя (×2 очка)</span>
                </div>
                <div class="zone-item">
                    <div class="zone-color zone-near"></div>
                    <span>Ближняя (×1 очко)</span>
                </div>
            </div>
            @if($hasSeagull)
            <p style="color: #fbbf24; margin-top: 15px;">
                🐦 Чайка Вперёдсмотрящая поможет вам найти лучших моллюсков!
            </p>
            @endif
        </div>
        
        <a href="{{ route('ship_game.index') }}" class="back-button">← Вернуться на карту</a>
    </div>
</div>

<!-- Диалоги -->
<div class="dialog-overlay" id="intro-dialog">
    <div class="dialog-box">
        <div class="dialog-character">👨‍🍳</div>
        <div class="dialog-title">Кок Макарони</div>
        <div class="dialog-text">
            Скорее! Отлив в Териберке недолгий.<br><br>
            Собирай моллюсков, начиная с самой кромки воды, пока их не залило!<br><br>
            <small style="color: #94a3b8;">Чем дальше от берега — тем ценнее добыча, но вода придёт туда первой!</small>
        </div>
        <button class="btn-start" onclick="startGame()">🦪 Погнали!</button>
    </div>
</div>

<div class="dialog-overlay" id="result-dialog" style="display: none;">
    <div class="result-box" id="result-box">
        <div class="result-title" id="result-title"></div>
        <div class="dialog-character" id="result-emoji"></div>
        <div class="result-stats" id="result-stats"></div>
        <div class="rewards-section" id="rewards-section" style="display: none;">
            <div class="rewards-title">🎁 Награды:</div>
            <div id="rewards-list"></div>
        </div>
        <button class="btn-start" onclick="continueJourney()">Продолжить путь</button>
    </div>
</div>

@push('scripts')
<script>
// Конфигурация из Laravel
const API_ROUTES = {
    startGame: '{{ route("ship_game.teriberika.start") }}',
    submitResult: '{{ route("ship_game.teriberika.submit") }}',
    leaderboard: '{{ route("ship_game.teriberika.leaderboard") }}',
};
const CSRF_TOKEN = '{{ csrf_token() }}';
const GAME_CONFIG = @json($config);
const HAS_SEAGULL = {{ $hasSeagull ? 'true' : 'false' }};
const CREW_DATA = @json($crew);
const ALREADY_COMPLETED = {{ $alreadyCompleted ? 'true' : 'false' }};

// Состояние игры
let gameSessionId = null;
let gameScene = null;
let score = 0;
let mollusksCollected = 0;
let farZoneCollected = 0;
let middleZoneCollected = 0;
let nearZoneCollected = 0;
let crabsClicked = 0;
let timeRemaining = GAME_CONFIG.gameDuration;
let currentTideLevel = 0; // 0-3
let isGameActive = false;

// Phaser конфигурация
const config = {
    type: Phaser.AUTO,
    width: 1000,
    height: 600,
    backgroundColor: '#87CEEB',
    parent: 'game-container',
    scene: {
        preload: preload,
        create: create,
        update: update
    }
};

let game;
let mollusks = [];
let tideTimer = null;
let gameTimer = null;
let seagullSprite = null;
let backgroundSprites = [];
let cookSprite = null;
let hudElements = {};

// Зоны игрового поля
const ZONES = {
    far: { yStart: 0, yEnd: 200, points: GAME_CONFIG.pointsFarZone, spawnChance: 0.15 },
    middle: { yStart: 200, yEnd: 400, points: GAME_CONFIG.pointsMiddleZone, spawnChance: 0.3 },
    near: { yStart: 400, yEnd: 600, points: GAME_CONFIG.pointsNearZone, spawnChance: 0.55 }
};

function preload() {
    // Создаём placeholder текстуры программно
    this.textures.generate('mollusk_common', { data: generateMolluskData('#8B4513'), pixelWidth: 4 });
    this.textures.generate('mollusk_rare', { data: generateMolluskData('#FFD700'), pixelWidth: 4 });
    this.textures.generate('mollusk_epic', { data: generateMolluskData('#9400D3'), pixelWidth: 4 });
    this.textures.generate('crab', { data: generateCrabData(), pixelWidth: 4 });
    this.textures.generate('seagull', { data: generateSeagullData(), pixelWidth: 4 });
    this.textures.generate('highlight', { data: generateHighlightData(), pixelWidth: 4 });
}

function generateMolluskData(color) {
    // Простой пиксель-арт моллюска 8x8
    return [
        '..0000..',
        '.011110.',
        '01111110',
        '01111110',
        '01111110',
        '01111110',
        '.011110.',
        '..0000..'
    ].map(row => row.replace(/1/g, color[1]).replace(/0/g, '#654321'));
}

function generateCrabData() {
    return [
        '0......0',
        '.0....0.',
        '..0000..',
        '.011110.',
        '01111110',
        '.011110.',
        '0..00..0',
        '........'
    ].map(row => row.replace(/1/g, 'D').replace(/0/g, '#E74C3C'));
}

function generateSeagullData() {
    return [
        '....0...',
        '...000..',
        '..00000.',
        '.0000000',
        '00000000',
        '.0000000',
        '..0...0.',
        '........'
    ].map(row => row.replace(/0/g, '#FFFFFF'));
}

function generateHighlightData() {
    return [
        '..0000..',
        '.0....0.',
        '0......0',
        '0......0',
        '0......0',
        '0......0',
        '.0....0.',
        '..0000..'
    ].map(row => row.replace(/0/g, '#FFFF00'));
}

function create() {
    gameScene = this;
    
    // Рисуем фон с зонами
    drawBackground(this);
    
    // Создаём HUD
    createHUD(this);
    
    // Создаём группу для моллюсков
    this.mollusksGroup = this.add.group();
    
    // Создаём чайку если есть бонус
    if (HAS_SEAGULL) {
        createSeagull(this);
    }
    
    // Создаём кока
    createCook(this);
    
    // Таймеры не запускаем до начала игры
}

function drawBackground(scene) {
    // Небо
    scene.add.rectangle(500, 50, 1000, 100, 0x87CEEB);
    
    // Зона Дальняя (у воды) - будет затоплена первой
    const farZone = scene.add.rectangle(500, 100, 1000, 200, 0x2d5a7b);
    farZone.setAlpha(0.3);
    farZone.setData('zone', 'far');
    
    // Зона Средняя
    const middleZone = scene.add.rectangle(500, 300, 1000, 200, 0x6b8e7a);
    middleZone.setAlpha(0.3);
    middleZone.setData('zone', 'middle');
    
    // Зона Ближняя (безопасная)
    const nearZone = scene.add.rectangle(500, 500, 1000, 200, 0x8fbc8f);
    nearZone.setAlpha(0.3);
    nearZone.setData('zone', 'near');
    
    // Текстура камней/песка
    for (let i = 0; i < 50; i++) {
        const x = Phaser.Math.Between(0, 1000);
        const y = Phaser.Math.Between(0, 600);
        const rock = scene.add.circle(x, y, Phaser.Math.Between(3, 8), 0x696969, 0.4);
    }
    
    // Водяная анимация (overlay для прилива)
    scene.waterOverlay = [];
    for (let i = 0; i < 3; i++) {
        const water = scene.add.rectangle(500, 100 + i * 200, 1000, 200, 0x1e90ff);
        water.setAlpha(0);
        water.setDepth(5);
        scene.waterOverlay.push(water);
    }
    
    // Линии разделения зон
    scene.add.line(500, 200, 0, 0, 1000, 0, 0xffffff, 0.3).setDepth(10);
    scene.add.line(500, 400, 0, 0, 1000, 0, 0xffffff, 0.3).setDepth(10);
    
    // Подписи зон
    scene.add.text(10, 10, '🔴 ДАЛЬНЯЯ ЗОНА (×3)', { fontSize: '14px', color: '#ff6b6b', fontStyle: 'bold' }).setDepth(10);
    scene.add.text(10, 210, '🟡 СРЕДНЯЯ ЗОНА (×2)', { fontSize: '14px', color: '#ffd93d', fontStyle: 'bold' }).setDepth(10);
    scene.add.text(10, 410, '🟢 БЛИЖНЯЯ ЗОНА (×1)', { fontSize: '14px', color: '#6bcb77', fontStyle: 'bold' }).setDepth(10);
}

function createHUD(scene) {
    const hudContainer = document.createElement('div');
    hudContainer.className = 'game-hud';
    hudContainer.innerHTML = `
        <div class="hud-panel">
            <h4>🦪 Собрано</h4>
            <div class="hud-value" id="hud-mollusks">0</div>
        </div>
        <div class="hud-panel">
            <h4>⭐ Очки</h4>
            <div class="hud-value" id="hud-score">0</div>
        </div>
        <div class="tide-indicator">
            <div class="tide-label">🌊 Прилив</div>
            <div class="tide-fill" id="tide-fill" style="height: 0%"></div>
        </div>
        <div class="hud-panel">
            <h4>⏱️ Время</h4>
            <div class="hud-value" id="hud-time">${GAME_CONFIG.gameDuration}</div>
        </div>
    `;
    document.getElementById('game-container').appendChild(hudContainer);
    
    // Индикатор чайки
    if (HAS_SEAGULL) {
        const seagullIndicator = document.createElement('div');
        seagullIndicator.className = 'seagull-indicator';
        seagullIndicator.innerHTML = `
            <span class="seagull-icon">🐦</span>
            <span class="seagull-text">Чайка помогает!</span>
        `;
        document.getElementById('game-container').appendChild(seagullIndicator);
    }
    
    // Реакция кока
    const cookReaction = document.createElement('div');
    cookReaction.className = 'cook-reaction';
    cookReaction.innerHTML = `
        <div class="cook-sprite" id="cook-sprite">👨‍🍳</div>
        <div class="cook-text" id="cook-text">Давай-давай!</div>
    `;
    document.getElementById('game-container').appendChild(cookReaction);
}

function createSeagull(scene) {
    seagullSprite = scene.add.container(500, -50);
    
    const body = scene.add.circle(0, 0, 20, 0xffffff);
    const beak = scene.add.triangle(25, 0, 0, -5, 0, 5, 15, 0, 0xffa500);
    const wingLeft = scene.add.ellipse(-15, 0, 30, 10, 0xe0e0e0);
    const wingRight = scene.add.ellipse(15, 0, 30, 10, 0xe0e0e0);
    const eye = scene.add.circle(10, -5, 3, 0x000000);
    
    seagullSprite.add([wingLeft, wingRight, body, beak, eye]);
    seagullSprite.setDepth(20);
    seagullSprite.setScale(0.8);
    
    // Анимация полёта
    scene.tweens.add({
        targets: seagullSprite,
        y: 50,
        duration: 1000,
        ease: 'Sine.easeOut'
    });
}

function createCook(scene) {
    // Кок будет отображаться через HTML
}

function startGame() {
    document.getElementById('intro-dialog').style.display = 'none';
    
    // Отправляем запрос на сервер
    fetch(API_ROUTES.startGame, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            gameSessionId = data.session_id;
            isGameActive = true;
            
            // Запускаем игру
            startGameLoop();
        }
    })
    .catch(error => {
        console.error('Error starting game:', error);
        // Всё равно запускаем локально
        isGameActive = true;
        startGameLoop();
    });
}

function startGameLoop() {
    // Запускаем спавн моллюсков
    spawnMollusks();
    
    // Таймер игры
    gameTimer = setInterval(() => {
        if (!isGameActive) return;
        
        timeRemaining--;
        updateHUD();
        
        if (timeRemaining <= 0) {
            endGame();
        }
    }, 1000);
    
    // Таймер прилива (с учётом бонуса чайки)
    const tideInterval = HAS_SEAGULL 
        ? GAME_CONFIG.tideInterval * 1.5  // 50% замедление
        : GAME_CONFIG.tideInterval;
    
    tideTimer = setInterval(() => {
        if (!isGameActive) return;
        
        currentTideLevel++;
        updateTide();
        
        if (currentTideLevel >= 3) {
            endGame();
        }
    }, tideInterval * 1000);
    
    // Спавн моллюсков каждые 0.5 секунды
    setInterval(() => {
        if (isGameActive) {
            spawnMollusks();
        }
    }, 500);
    
    // Движение чайки
    if (HAS_SEAGULL) {
        setInterval(() => {
            if (isGameActive) {
                seagullHighlight();
            }
        }, 3000);
    }
}

function spawnMollusks() {
    if (!gameScene || !isGameActive) return;
    
    // Определяем сколько моллюсков спавнить
    const spawnCount = Phaser.Math.Between(1, 3);
    
    for (let i = 0; i < spawnCount; i++) {
        // Выбираем зону с учётом текущего уровня прилива
        const availableZones = getAvailableZones();
        if (availableZones.length === 0) return;
        
        const zoneKey = Phaser.Math.RND.pick(availableZones);
        const zone = ZONES[zoneKey];
        
        // Позиция
        const x = Phaser.Math.Between(50, 950);
        const y = Phaser.Math.Between(zone.yStart + 20, zone.yEnd - 20);
        
        // Тип моллюска (редкий в дальней зоне)
        const isRare = zoneKey === 'far' && Math.random() < (HAS_SEAGULL ? 0.4 : 0.25);
        const isCrab = Math.random() < 0.1; // 10% шанс краба
        
        createMollusk(gameScene, x, y, zoneKey, isRare, isCrab);
    }
}

function getAvailableZones() {
    const zones = [];
    if (currentTideLevel < 1) zones.push('far');
    if (currentTideLevel < 2) zones.push('middle');
    if (currentTideLevel < 3) zones.push('near');
    return zones;
}

function createMollusk(scene, x, y, zone, isRare, isCrab) {
    const container = scene.add.container(x, y);
    
    let sprite;
    let points = ZONES[zone].points;
    
    if (isCrab) {
        // Краб - красный круг
        sprite = scene.add.circle(0, 0, 18, 0xe74c3c);
        const claw1 = scene.add.circle(-12, -8, 6, 0xc0392b);
        const claw2 = scene.add.circle(12, -8, 6, 0xc0392b);
        container.add([claw1, claw2, sprite]);
        container.setData('type', 'crab');
        points = -10; // Краб отнимает очки
    } else if (isRare) {
        // Редкий моллюск - золотой
        sprite = scene.add.circle(0, 0, 20, 0xffd700);
        const glow = scene.add.circle(0, 0, 25, 0xffd700, 0.3);
        container.add([glow, sprite]);
        container.setData('type', 'rare');
        points *= 2; // Двойные очки
        
        // Пульсация для редких
        scene.tweens.add({
            targets: glow,
            alpha: 0.6,
            scale: 1.2,
            duration: 500,
            yoyo: true,
            repeat: -1
        });
    } else {
        // Обычный моллюск
        const colors = [0x8b4513, 0x654321, 0x3d2914];
        sprite = scene.add.circle(0, 0, 15, Phaser.Math.RND.pick(colors));
        container.add(sprite);
        container.setData('type', 'common');
    }
    
    container.setData('zone', zone);
    container.setData('points', points);
    container.setData('collected', false);
    container.setDepth(3);
    
    // Интерактивность
    sprite.setInteractive({ useHandCursor: true });
    
    sprite.on('pointerdown', () => {
        if (container.getData('collected')) return;
        
        container.setData('collected', true);
        collectMollusk(scene, container);
    });
    
    // Добавляем в массив для отслеживания
    mollusks.push(container);
    
    // Автоудаление через 8 секунд если не собран
    scene.time.delayedCall(8000, () => {
        if (!container.getData('collected')) {
            container.destroy();
            mollusks = mollusks.filter(m => m !== container);
        }
    });
    
    // Анимация появления
    container.setScale(0);
    scene.tweens.add({
        targets: container,
        scale: 1,
        duration: 200,
        ease: 'Back.easeOut'
    });
}

function collectMollusk(scene, container) {
    const type = container.getData('type');
    const zone = container.getData('zone');
    const points = container.getData('points');
    
    if (type === 'crab') {
        crabsClicked++;
        score = Math.max(0, score + points); // points отрицательный
        updateCookReaction('sad', 'Ай! Краб!');
        
        // Эффект урона
        scene.cameras.main.shake(100, 0.01);
    } else {
        mollusksCollected++;
        score += points;
        
        // Статистика по зонам
        if (zone === 'far') farZoneCollected++;
        else if (zone === 'middle') middleZoneCollected++;
        else nearZoneCollected++;
        
        updateCookReaction('happy', type === 'rare' ? 'Отлично!' : 'Хорошо!');
    }
    
    // Текст с очками
    const pointsText = scene.add.text(
        container.x, 
        container.y - 20, 
        points > 0 ? `+${points}` : `${points}`,
        {
            fontSize: '24px',
            fontStyle: 'bold',
            color: points > 0 ? '#22c55e' : '#ef4444',
            stroke: '#000000',
            strokeThickness: 3
        }
    );
    pointsText.setOrigin(0.5);
    pointsText.setDepth(30);
    
    scene.tweens.add({
        targets: pointsText,
        y: pointsText.y - 40,
        alpha: 0,
        duration: 800,
        onComplete: () => pointsText.destroy()
    });
    
    // Анимация сбора
    scene.tweens.add({
        targets: container,
        scale: 0,
        alpha: 0,
        duration: 200,
        onComplete: () => {
            container.destroy();
            mollusks = mollusks.filter(m => m !== container);
        }
    });
    
    updateHUD();
}

function seagullHighlight() {
    if (!seagullSprite || !isGameActive) return;
    
    // Находим самого дорогого моллюска в зоне, которая скоро затопится
    const targetZones = currentTideLevel === 0 ? ['far'] : 
                        currentTideLevel === 1 ? ['middle'] : ['near'];
    
    const targets = mollusks.filter(m => 
        !m.getData('collected') && 
        targetZones.includes(m.getData('zone')) &&
        m.getData('type') !== 'crab'
    );
    
    if (targets.length === 0) return;
    
    // Сортируем по очкам
    targets.sort((a, b) => b.getData('points') - a.getData('points'));
    const target = targets[0];
    
    // Анимация пикирования чайки
    gameScene.tweens.add({
        targets: seagullSprite,
        x: target.x,
        y: target.y - 50,
        duration: 500,
        ease: 'Sine.easeInOut',
        onComplete: () => {
            // Подсветка моллюска
            const highlight = gameScene.add.circle(target.x, target.y, 30, 0xffff00, 0.5);
            highlight.setDepth(2);
            
            gameScene.tweens.add({
                targets: highlight,
                scale: 1.5,
                alpha: 0,
                duration: 1000,
                onComplete: () => highlight.destroy()
            });
            
            // Возврат чайки
            gameScene.tweens.add({
                targets: seagullSprite,
                y: 50,
                duration: 800,
                ease: 'Sine.easeInOut'
            });
        }
    });
}

function updateTide() {
    if (!gameScene) return;
    
    // Анимируем затопление соответствующей зоны
    if (currentTideLevel <= gameScene.waterOverlay.length) {
        const water = gameScene.waterOverlay[currentTideLevel - 1];
        
        gameScene.tweens.add({
            targets: water,
            alpha: 0.7,
            duration: 2000,
            ease: 'Sine.easeIn'
        });
        
        // Удаляем моллюсков в затопленной зоне
        const zoneToFlood = currentTideLevel === 1 ? 'far' : 
                           currentTideLevel === 2 ? 'middle' : 'near';
        
        mollusks.forEach(m => {
            if (m.getData('zone') === zoneToFlood && !m.getData('collected')) {
                gameScene.tweens.add({
                    targets: m,
                    alpha: 0,
                    y: m.y + 30,
                    duration: 500,
                    onComplete: () => m.destroy()
                });
            }
        });
        
        mollusks = mollusks.filter(m => m.getData('zone') !== zoneToFlood);
    }
    
    // Обновляем индикатор прилива
    const tidePercent = (currentTideLevel / 3) * 100;
    document.getElementById('tide-fill').style.height = `${tidePercent}%`;
    
    // Предупреждение кока
    if (currentTideLevel === 1) {
        updateCookReaction('warning', 'Вода прибывает!');
    } else if (currentTideLevel === 2) {
        updateCookReaction('warning', 'Скорее!');
    }
}

function updateHUD() {
    document.getElementById('hud-mollusks').textContent = mollusksCollected;
    document.getElementById('hud-score').textContent = score;
    document.getElementById('hud-time').textContent = timeRemaining;
}

function updateCookReaction(type, text) {
    const cookSprite = document.getElementById('cook-sprite');
    const cookText = document.getElementById('cook-text');
    
    cookSprite.className = 'cook-sprite ' + (type === 'happy' ? 'happy' : type === 'sad' ? 'sad' : '');
    cookText.textContent = text;
    
    // Сброс анимации через секунду
    setTimeout(() => {
        cookSprite.className = 'cook-sprite';
    }, 500);
}

function endGame() {
    isGameActive = false;
    
    clearInterval(gameTimer);
    clearInterval(tideTimer);
    
    // Отправляем результат на сервер
    const timePlayed = GAME_CONFIG.gameDuration - timeRemaining;
    
    fetch(API_ROUTES.submitResult, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
            session_id: gameSessionId,
            score: score,
            mollusks_collected: mollusksCollected,
            far_zone_collected: farZoneCollected,
            middle_zone_collected: middleZoneCollected,
            near_zone_collected: nearZoneCollected,
            crabs_clicked: crabsClicked,
            time_played: timePlayed
        })
    })
    .then(response => response.json())
    .then(data => {
        showResult(data);
    })
    .catch(error => {
        console.error('Error submitting result:', error);
        // Показываем локальный результат
        showResult({
            is_success: mollusksCollected >= GAME_CONFIG.minMollusks,
            score: score,
            rewards: mollusksCollected >= GAME_CONFIG.minMollusks ? {
                food_days: 5,
                money: score * 10
            } : {}
        });
    });
}

function showResult(data) {
    const dialog = document.getElementById('result-dialog');
    const box = document.getElementById('result-box');
    const title = document.getElementById('result-title');
    const emoji = document.getElementById('result-emoji');
    const stats = document.getElementById('result-stats');
    const rewardsSection = document.getElementById('rewards-section');
    const rewardsList = document.getElementById('rewards-list');
    
    if (data.is_success) {
        box.className = 'result-box success';
        title.className = 'result-title success';
        title.textContent = '🎉 Отлично!';
        emoji.textContent = '👨‍🍳😄';
    } else {
        box.className = 'result-box failure';
        title.className = 'result-title failure';
        title.textContent = '😔 Не хватило...';
        emoji.textContent = '👨‍🍳😞';
    }
    
    stats.innerHTML = `
        <div class="stat-item">
            <div class="stat-label">Собрано моллюсков</div>
            <div class="stat-value">${mollusksCollected}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Очки</div>
            <div class="stat-value">${score}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Дальняя зона</div>
            <div class="stat-value">${farZoneCollected}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Средняя зона</div>
            <div class="stat-value">${middleZoneCollected}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Ближняя зона</div>
            <div class="stat-value">${nearZoneCollected}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Крабов поймано</div>
            <div class="stat-value">${crabsClicked}</div>
        </div>
    `;
    
    if (data.is_success && data.rewards) {
        rewardsSection.style.display = 'block';
        rewardsList.innerHTML = `
            <div class="reward-item">🍖 +${data.rewards.food_days} дней еды</div>
            <div class="reward-item">💰 +${data.rewards.money.toLocaleString()} ₽</div>
        `;
    } else {
        rewardsSection.style.display = 'none';
    }
    
    dialog.style.display = 'flex';
}

function continueJourney() {
    window.location.href = '{{ route("ship_game.index") }}';
}

function update() {
    // Обновление каждый кадр если нужно
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    if (ALREADY_COMPLETED) {
        document.getElementById('intro-dialog').innerHTML = `
            <div class="dialog-box">
                <div class="dialog-character">✅</div>
                <div class="dialog-title">Уже пройдено!</div>
                <div class="dialog-text">
                    Вы уже успешно собрали моллюсков в этой остановке.<br>
                    Можете продолжить путешествие!
                </div>
                <button class="btn-start" onclick="continueJourney()">Продолжить путь</button>
            </div>
        `;
    }
    
    game = new Phaser.Game(config);
});
</script>
@endpush
@endsection
