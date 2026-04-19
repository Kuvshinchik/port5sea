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
        overflow: hidden;
    }
    
    .cook-sprite-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .cook-sprite-img.happy { animation: cook-happy 0.5s ease; }
    .cook-sprite-img.sad { animation: cook-sad 0.5s ease; }
    
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
    
    .dialog-character img {
        width: 100px;
        height: 100px;
        object-fit: contain;
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
    
    .rewards-title { color: #22c55e; font-weight: bold; margin-bottom: 10px; }
    .reward-item { color: white; font-size: 1.1rem; }
    
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
    
    .instructions h3 { color: #7dd3fc; margin-bottom: 10px; font-family: 'Russo One', sans-serif; }
    
    .zone-legend { display: flex; justify-content: center; gap: 20px; margin-top: 15px; }
    .zone-item { display: flex; align-items: center; gap: 8px; }
    .zone-color { width: 20px; height: 20px; border-radius: 4px; }
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
    
    .back-button:hover { background: rgba(125, 211, 252, 0.3); color: white; }
    
    .loading-screen {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: #1a3a4a;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 50;
    }
    
    .loading-screen.hidden { display: none; }
    
    .loading-spinner {
        width: 60px; height: 60px;
        border: 4px solid rgba(125, 211, 252, 0.3);
        border-top-color: #7dd3fc;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .loading-text { color: #7dd3fc; margin-top: 15px; font-family: 'Nunito', sans-serif; }
    
    .loading-progress {
        width: 200px; height: 8px;
        background: rgba(125, 211, 252, 0.2);
        border-radius: 4px;
        margin-top: 15px;
        overflow: hidden;
    }
    
    .loading-progress-bar { height: 100%; background: #7dd3fc; width: 0%; transition: width 0.3s ease; }
    
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

<div class="game-wrapper">
    <div class="container">
        <h2 class="game-title">🦪 Сбор моллюсков в Териберке</h2>
        
        <div id="game-container">
            <div class="loading-screen" id="loading-screen">
                <div class="loading-spinner"></div>
                <div class="loading-text" id="loading-text">Загрузка ресурсов...</div>
                <div class="loading-progress">
                    <div class="loading-progress-bar" id="loading-progress-bar"></div>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>🎮 Как играть</h3>
            <p>
                Кликайте на моллюсков, чтобы собрать их до того, как их зальёт приливом!<br>
                Начинайте с дальней зоны — там самые ценные, но вода придёт туда первой.
            </p>
            <div class="zone-legend">
                <div class="zone-item"><div class="zone-color zone-far"></div><span>Дальняя (×3 очка)</span></div>
                <div class="zone-item"><div class="zone-color zone-middle"></div><span>Средняя (×2 очка)</span></div>
                <div class="zone-item"><div class="zone-color zone-near"></div><span>Ближняя (×1 очко)</span></div>
            </div>
            @if($hasSeagull)
            <p style="color: #fbbf24; margin-top: 15px;">🐦 Чайка Вперёдсмотрящая поможет вам найти лучших моллюсков!</p>
            @endif
        </div>
        
        <a href="{{ route('ship_game.index') }}" class="back-button">← Вернуться на карту</a>
    </div>
</div>

<!-- Диалоги -->
<div class="dialog-overlay" id="intro-dialog" style="display: none;">
    <div class="dialog-box">
        <div class="dialog-character">
            <img src="{{ asset('assets/game/teriberika/cook_happy.png') }}" alt="Кок" onerror="this.parentElement.innerHTML='👨‍🍳'">
        </div>
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
// ═══════════════════════════════════════════════════════════════════
// КОНФИГУРАЦИЯ
// ═══════════════════════════════════════════════════════════════════

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

// Пути к ассетам
const ASSET_BASE = '{{ asset("assets/game/teriberika") }}';
const ASSETS = {
    bg_low_tide: `${ASSET_BASE}/background_low_tide.png`,
    bg_mid_tide: `${ASSET_BASE}/background_mid_tide.png`,
    bg_high_tide: `${ASSET_BASE}/background_high_tide.png`,
    mollusk_common: `${ASSET_BASE}/mollusk_common.png`,
    mollusk_rare: `${ASSET_BASE}/mollusk_rare.png`,
    crab: `${ASSET_BASE}/crab.png`,
    seagull: `${ASSET_BASE}/seagull.png`,
    cook_happy: `${ASSET_BASE}/cook_happy.png`,
    cook_sad: `${ASSET_BASE}/cook_sad.png`,
    water_overlay: `${ASSET_BASE}/water_overlay.png`,
};

// ═══════════════════════════════════════════════════════════════════
// СОСТОЯНИЕ ИГРЫ
// ═══════════════════════════════════════════════════════════════════

let gameSessionId = null;
let gameScene = null;
let score = 0;
let mollusksCollected = 0;
let farZoneCollected = 0;
let middleZoneCollected = 0;
let nearZoneCollected = 0;
let crabsClicked = 0;
let timeRemaining = GAME_CONFIG.gameDuration;
let currentTideLevel = 0;
let isGameActive = false;

let mollusks = [];
let backgroundSprites = [];
let seagullSprite = null;
let waterOverlaySprite = null;
let tideTimer = null;
let gameTimer = null;
let spawnTimer = null;
let seagullTimer = null;

const SCENE_HEIGHT = 600;
const SPAWN_TOP_OFFSET = Math.round(SCENE_HEIGHT * 0.33); // верхняя четверть — небо, спавн отключен
const PLAYABLE_HEIGHT = SCENE_HEIGHT - SPAWN_TOP_OFFSET;
const ZONE_HEIGHT = Math.floor(PLAYABLE_HEIGHT / 3);

const ZONES = {
    far: { yStart: SPAWN_TOP_OFFSET, yEnd: SPAWN_TOP_OFFSET + ZONE_HEIGHT, points: GAME_CONFIG.pointsFarZone, spawnChance: 0.15 },
    middle: { yStart: SPAWN_TOP_OFFSET + ZONE_HEIGHT, yEnd: SPAWN_TOP_OFFSET + ZONE_HEIGHT * 2, points: GAME_CONFIG.pointsMiddleZone, spawnChance: 0.3 },
    near: { yStart: SPAWN_TOP_OFFSET + ZONE_HEIGHT * 2, yEnd: SCENE_HEIGHT, points: GAME_CONFIG.pointsNearZone, spawnChance: 0.55 }
};

// ═══════════════════════════════════════════════════════════════════
// PHASER
// ═══════════════════════════════════════════════════════════════════

const config = {
    type: Phaser.AUTO,
    width: 1000,
    height: 600,
    backgroundColor: '#1a3a4a',
    parent: 'game-container',
    scene: { preload, create, update }
};

let game;

function preload() {
    const progressBar = document.getElementById('loading-progress-bar');
    const loadingText = document.getElementById('loading-text');
    
    this.load.on('progress', (value) => {
        progressBar.style.width = `${Math.round(value * 100)}%`;
        loadingText.textContent = `Загрузка... ${Math.round(value * 100)}%`;
    });
    
    this.load.on('complete', () => {
        loadingText.textContent = 'Готово!';
        setTimeout(() => {
            document.getElementById('loading-screen').classList.add('hidden');
            document.getElementById('intro-dialog').style.display = 'flex';
        }, 300);
    });
    
    this.load.on('loaderror', (file) => {
        console.warn(`Ошибка загрузки: ${file.key}`);
    });
    
    // Загрузка всех ассетов
    this.load.image('bg_low_tide', ASSETS.bg_low_tide);
    this.load.image('bg_mid_tide', ASSETS.bg_mid_tide);
    this.load.image('bg_high_tide', ASSETS.bg_high_tide);
    this.load.image('mollusk_common', ASSETS.mollusk_common);
    this.load.image('mollusk_rare', ASSETS.mollusk_rare);
    this.load.image('crab', ASSETS.crab);
    this.load.image('seagull', ASSETS.seagull);
    this.load.image('water_overlay', ASSETS.water_overlay);
}

function create() {
    gameScene = this;
    
    createBackgrounds(this);
    createZoneOverlay(this);
    createHUD();
    
    if (HAS_SEAGULL) createSeagull(this);
    if (ALREADY_COMPLETED) showAlreadyCompletedDialog();
}

// ═══════════════════════════════════════════════════════════════════
// ПРОВЕРКА ТЕКСТУРЫ
// ═══════════════════════════════════════════════════════════════════

function hasTexture(scene, key) {
    if (!scene.textures.exists(key)) return false;
    const source = scene.textures.get(key).getSourceImage();
    return source && source.width > 0;
}

// ═══════════════════════════════════════════════════════════════════
// ФОНЫ
// ═══════════════════════════════════════════════════════════════════

function createBackgrounds(scene) {
    const bgKeys = ['bg_low_tide', 'bg_mid_tide', 'bg_high_tide'];
    
    bgKeys.forEach((key, index) => {
        let sprite;
        
        if (hasTexture(scene, key)) {
            sprite = scene.add.image(500, 300, key);
            sprite.setDisplaySize(1000, 600);
        } else {
            sprite = drawFallbackBackground(scene, index);
        }
        
        sprite.setDepth(-10 + index);
        sprite.setAlpha(index === 0 ? 1 : 0);
        backgroundSprites.push({ sprite, level: index });
    });
    
    // Оверлей воды
    if (hasTexture(scene, 'water_overlay')) {
        waterOverlaySprite = scene.add.tileSprite(500, 300, 1000, 600, 'water_overlay');
        waterOverlaySprite.setDepth(-5);
        waterOverlaySprite.setAlpha(0);
    }
}

function drawFallbackBackground(scene, tideLevel) {
    const container = scene.add.container(500, 300);
    
    // Небо
    container.add(scene.add.rectangle(0, -200, 1000, 200, 0x87CEEB));
    
    // Зоны с водой в зависимости от уровня прилива
    const zoneColors = [0x2d5a7b, 0x6b8e7a, 0x8fbc8f];
    const waterColor = 0x1e90ff;
    
    for (let i = 0; i < 3; i++) {
        const y = -100 + i * 200;
        const isFlooded = i < tideLevel;
        const color = isFlooded ? waterColor : zoneColors[i];
        const alpha = isFlooded ? 0.7 : 1;
        container.add(scene.add.rectangle(0, y, 1000, 200, color, alpha));
    }
    
    // Камни
    for (let i = 0; i < 30; i++) {
        container.add(scene.add.circle(
            Phaser.Math.Between(-450, 450),
            Phaser.Math.Between(-250, 250),
            Phaser.Math.Between(3, 8),
            0x696969, 0.4
        ));
    }
    
    return container;
}

function createZoneOverlay(scene) {
    scene.add.line(500, ZONES.middle.yStart, 0, 0, 1000, 0, 0xffffff, 0.3).setDepth(10);
    scene.add.line(500, ZONES.near.yStart, 0, 0, 1000, 0, 0xffffff, 0.3).setDepth(10);
    
    const labels = [
        { text: '🔴 ДАЛЬНЯЯ (×3)', y: ZONES.far.yStart + 10, color: '#ff6b6b' },
        { text: '🟡 СРЕДНЯЯ (×2)', y: ZONES.middle.yStart + 10, color: '#ffd93d' },
        { text: '🟢 БЛИЖНЯЯ (×1)', y: ZONES.near.yStart + 10, color: '#6bcb77' }
    ];
    
    labels.forEach(l => {
        scene.add.text(10, l.y, l.text, {
            fontSize: '14px', color: l.color, fontStyle: 'bold',
            backgroundColor: 'rgba(0,0,0,0.5)', padding: { x: 5, y: 3 }
        }).setDepth(15);
    });
}

// ═══════════════════════════════════════════════════════════════════
// HUD
// ═══════════════════════════════════════════════════════════════════

function createHUD() {
    const container = document.getElementById('game-container');
    
    const hud = document.createElement('div');
    hud.className = 'game-hud';
    hud.innerHTML = `
        <div class="hud-panel"><h4>🦪 Собрано</h4><div class="hud-value" id="hud-mollusks">0</div></div>
        <div class="hud-panel"><h4>⭐ Очки</h4><div class="hud-value" id="hud-score">0</div></div>
        <div class="tide-indicator"><div class="tide-label">🌊 Прилив</div><div class="tide-fill" id="tide-fill"></div></div>
        <div class="hud-panel"><h4>⏱️ Время</h4><div class="hud-value" id="hud-time">${GAME_CONFIG.gameDuration}</div></div>
    `;
    container.appendChild(hud);
    
    if (HAS_SEAGULL) {
        const seagull = document.createElement('div');
        seagull.className = 'seagull-indicator';
        seagull.innerHTML = `<span class="seagull-icon">🐦</span><span class="seagull-text">Чайка помогает!</span>`;
        container.appendChild(seagull);
    }
    
    const cook = document.createElement('div');
    cook.className = 'cook-reaction';
    cook.innerHTML = `
        <img src="${ASSETS.cook_happy}" class="cook-sprite-img" id="cook-sprite" alt="Кок" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
        <div style="font-size:3rem;display:none;">👨‍🍳</div>
        <div class="cook-text" id="cook-text">Давай-давай!</div>
    `;
    container.appendChild(cook);
}

// ═══════════════════════════════════════════════════════════════════
// ЧАЙКА
// ═══════════════════════════════════════════════════════════════════

function createSeagull(scene) {
    if (hasTexture(scene, 'seagull')) {
        seagullSprite = scene.add.image(500, -50, 'seagull');
        seagullSprite.setDisplaySize(90, 70);
    } else {
        seagullSprite = scene.add.container(500, -50);
        seagullSprite.add([
            scene.add.ellipse(-15, 0, 30, 10, 0xe0e0e0),
            scene.add.ellipse(15, 0, 30, 10, 0xe0e0e0),
            scene.add.circle(0, 0, 20, 0xffffff),
            scene.add.triangle(25, 0, 0, -5, 0, 5, 15, 0, 0xffa500),
            scene.add.circle(10, -5, 3, 0x000000)
        ]);
    }
    
    seagullSprite.setDepth(20);
    scene.tweens.add({ targets: seagullSprite, y: 50, duration: 1000, ease: 'Sine.easeOut' });
}

// ═══════════════════════════════════════════════════════════════════
// ИГРОВОЙ ЦИКЛ
// ═══════════════════════════════════════════════════════════════════

function startGame() {
    document.getElementById('intro-dialog').style.display = 'none';
    
    fetch(API_ROUTES.startGame, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
    })
    .then(r => r.json())
    .then(data => { if (data.success) gameSessionId = data.session_id; })
    .catch(console.error)
    .finally(() => { isGameActive = true; startGameLoop(); });
}

function startGameLoop() {
    spawnMollusks();
    
    gameTimer = setInterval(() => {
        if (!isGameActive) return;
        timeRemaining--;
        updateHUD();
        if (timeRemaining <= 0) endGame();
    }, 1000);
    
    const tideInterval = HAS_SEAGULL ? GAME_CONFIG.tideInterval * 1.5 : GAME_CONFIG.tideInterval;
    tideTimer = setInterval(() => {
        if (!isGameActive) return;
        currentTideLevel++;
        updateTide();
        if (currentTideLevel >= 3) endGame();
    }, tideInterval * 1000);
    
    spawnTimer = setInterval(() => { if (isGameActive) spawnMollusks(); }, 500);
    
    if (HAS_SEAGULL) {
        seagullTimer = setInterval(() => { if (isGameActive) seagullHighlight(); }, 3000);
    }
    
    if (waterOverlaySprite) {
        gameScene.tweens.add({ targets: waterOverlaySprite, alpha: 0.3, duration: 5000 });
    }
}

// ═══════════════════════════════════════════════════════════════════
// МОЛЛЮСКИ
// ═══════════════════════════════════════════════════════════════════

function spawnMollusks() {
    if (!gameScene || !isGameActive) return;
    
    const count = Phaser.Math.Between(1, 3);
    const available = getAvailableZones();
    if (!available.length) return;
    
    for (let i = 0; i < count; i++) {
        const zoneKey = Phaser.Math.RND.pick(available);
        const zone = ZONES[zoneKey];
        const x = Phaser.Math.Between(50, 950);
        const y = Phaser.Math.Between(zone.yStart + 30, zone.yEnd - 30);
        const weights = getSpawnWeights(zoneKey);
        const roll = Math.random();
        const isCrab = roll < weights.crab;
        const isRare = !isCrab && roll < (weights.crab + weights.rare);
        
        createMollusk(gameScene, x, y, zoneKey, isRare, isCrab);
    }
}

function getSpawnWeights(zoneKey) {
    const baseWeights = {
        far: { crab: 0.15, rare: 0.45 },
        middle: { crab: 0.15, rare: 0.35 },
        near: { crab: 0.15, rare: 0.25 }
    };

    const weights = { ...(baseWeights[zoneKey] || baseWeights.middle) };

    if (HAS_SEAGULL && zoneKey === 'far') {
        weights.rare = 0.55;
        weights.crab = 0.10;
    }

    return weights;
}

function getAvailableZones() {
    const z = [];
    if (currentTideLevel < 1) z.push('far');
    if (currentTideLevel < 2) z.push('middle');
    if (currentTideLevel < 3) z.push('near');
    return z;
}

function createMollusk(scene, x, y, zone, isRare, isCrab) {
    let sprite;
    let points = ZONES[zone].points;
    let hitRadius = 20;
    let baseScaleX = 1;
    let baseScaleY = 1;
    
    if (isCrab) {
        // ═══ КРАБ ═══
        if (hasTexture(scene, 'crab')) {
            sprite = scene.add.image(x, y, 'crab').setDisplaySize(64, 64);
            hitRadius = 25;
            baseScaleX = sprite.scaleX;
            baseScaleY = sprite.scaleY;
        } else {
            // Fallback - используем Graphics для рисования краба
            sprite = scene.add.graphics();
            sprite.fillStyle(0xc0392b, 1);
            sprite.fillCircle(-15, -10, 8); // левая клешня
            sprite.fillCircle(15, -10, 8);  // правая клешня
            sprite.fillStyle(0xe74c3c, 1);
            sprite.fillCircle(0, 0, 18);    // тело
            sprite.setPosition(x, y);
        }
        sprite.setData('type', 'crab');
        points = -10;
        
    } else if (isRare) {
        // ═══ РЕДКИЙ МОЛЛЮСК ═══
        if (hasTexture(scene, 'mollusk_rare')) {
            sprite = scene.add.image(x, y, 'mollusk_rare').setDisplaySize(58, 58);
            hitRadius = 25;
            baseScaleX = sprite.scaleX;
            baseScaleY = sprite.scaleY;
        } else {
            // Fallback - золотой круг со свечением
            sprite = scene.add.graphics();
            sprite.fillStyle(0xffd700, 0.3);
            sprite.fillCircle(0, 0, 28); // свечение
            sprite.fillStyle(0xffd700, 1);
            sprite.fillCircle(0, 0, 22); // тело
            sprite.setPosition(x, y);
            
            // Пульсация
            scene.tweens.add({
                targets: sprite,
                scaleX: 1.2,
                scaleY: 1.2,
                duration: 500,
                yoyo: true,
                repeat: -1,
                ease: 'Sine.easeInOut'
            });
        }
        sprite.setData('type', 'rare');
        points *= 2;
        
    } else {
        // ═══ ОБЫЧНЫЙ МОЛЛЮСК ═══
        if (hasTexture(scene, 'mollusk_common')) {
            sprite = scene.add.image(x, y, 'mollusk_common').setDisplaySize(52, 52);
            hitRadius = 20;
            baseScaleX = sprite.scaleX;
            baseScaleY = sprite.scaleY;
        } else {
            // Fallback - коричневый круг
            const colors = [0x8b4513, 0x654321, 0x3d2914, 0x5d4037, 0x4e342e];
            const color = Phaser.Math.RND.pick(colors);
            sprite = scene.add.graphics();
            sprite.fillStyle(color, 1);
            sprite.fillCircle(0, 0, 15);
            sprite.setPosition(x, y);
        }
        sprite.setData('type', 'common');
    }
    
    // Сохраняем данные
    sprite.setData('zone', zone);
    sprite.setData('points', points);
    sprite.setData('collected', false);
    sprite.setData('posX', x);
    sprite.setData('posY', y);
    sprite.setData('baseScaleX', baseScaleX);
    sprite.setData('baseScaleY', baseScaleY);
    sprite.setDepth(5);
    
    // ═══ ИНТЕРАКТИВНОСТЬ ═══
    // Используем стандартную hit area Phaser, чтобы корректно работать с любым размером текстур
    sprite.setInteractive({ useHandCursor: true });
    scene.input.setDefaultCursor('pointer');
    
    // Обработчик клика
    sprite.on('pointerdown', function() {
        if (this.getData('collected')) return;
        this.setData('collected', true);
        collectMollusk(scene, this);
    });
    
    // Визуальная обратная связь при наведении
    sprite.on('pointerover', function() {
        if (!this.getData('collected')) {
            const hoverScaleX = (this.getData('baseScaleX') || 1) * 1.15;
            const hoverScaleY = (this.getData('baseScaleY') || 1) * 1.15;
            scene.tweens.add({
                targets: this,
                scaleX: hoverScaleX,
                scaleY: hoverScaleY,
                duration: 100
            });
        }
    });
    
    sprite.on('pointerout', function() {
        if (!this.getData('collected')) {
            scene.tweens.add({
                targets: this,
                scaleX: this.getData('baseScaleX') || 1,
                scaleY: this.getData('baseScaleY') || 1,
                duration: 100
            });
        }
    });
    
    // Добавляем в массив
    mollusks.push(sprite);
    
    // Автоудаление через 8 секунд
    scene.time.delayedCall(8000, () => {
        if (sprite.active && !sprite.getData('collected')) {
            scene.tweens.add({
                targets: sprite,
                alpha: 0,
                duration: 300,
                onComplete: () => {
                    sprite.destroy();
                    mollusks = mollusks.filter(m => m !== sprite);
                }
            });
        }
    });
    
    // Анимация появления
    sprite.setScale(0);
    sprite.setAlpha(1);
    scene.tweens.add({
        targets: sprite,
        scaleX: sprite.getData('baseScaleX') || 1,
        scaleY: sprite.getData('baseScaleY') || 1,
        duration: 200,
        ease: 'Back.easeOut'
    });
}

function collectMollusk(scene, sprite) {
    const type = sprite.getData('type');
    const zone = sprite.getData('zone');
    const points = sprite.getData('points');
    const posX = sprite.getData('posX') || sprite.x || 500;
    const posY = sprite.getData('posY') || sprite.y || 300;
    
    // Отключаем интерактивность сразу
    sprite.disableInteractive();
    
    if (type === 'crab') {
        crabsClicked++;
        score = Math.max(0, score + points);
        updateCookReaction('sad', 'Ай! Краб!');
        scene.cameras.main.shake(100, 0.01);
        
        // Красная вспышка
        const flash = scene.add.circle(posX, posY, 30, 0xff0000, 0.5).setDepth(30);
        scene.tweens.add({ targets: flash, alpha: 0, scale: 2, duration: 300, onComplete: () => flash.destroy() });
    } else {
        mollusksCollected++;
        score += points;
        
        if (zone === 'far') farZoneCollected++;
        else if (zone === 'middle') middleZoneCollected++;
        else nearZoneCollected++;
        
        updateCookReaction('happy', type === 'rare' ? 'Отлично!' : 'Хорошо!');
        
        // Зелёная вспышка
        const flash = scene.add.circle(posX, posY, 25, 0x22c55e, 0.5).setDepth(30);
        scene.tweens.add({ targets: flash, alpha: 0, scale: 1.5, duration: 200, onComplete: () => flash.destroy() });
    }
    
    // Текст с очками
    const text = scene.add.text(posX, posY - 20, points > 0 ? `+${points}` : `${points}`, {
        fontSize: '28px', fontStyle: 'bold', color: points > 0 ? '#22c55e' : '#ef4444',
        stroke: '#000000', strokeThickness: 4
    }).setOrigin(0.5).setDepth(35);
    
    scene.tweens.add({ targets: text, y: text.y - 50, alpha: 0, duration: 800, onComplete: () => text.destroy() });
    
    // Анимация исчезновения
    scene.tweens.add({
        targets: sprite,
        scaleX: 0,
        scaleY: 0,
        alpha: 0,
        duration: 150,
        onComplete: () => {
            sprite.destroy();
            mollusks = mollusks.filter(m => m !== sprite);
        }
    });
    
    updateHUD();
}

// ═══════════════════════════════════════════════════════════════════
// ЧАЙКА ПОДСКАЗКИ
// ═══════════════════════════════════════════════════════════════════

function seagullHighlight() {
    if (!seagullSprite || !isGameActive) return;
    
    const targetZones = currentTideLevel === 0 ? ['far'] : currentTideLevel === 1 ? ['middle'] : ['near'];
    const targets = mollusks.filter(m => 
        m.active && 
        !m.getData('collected') && 
        targetZones.includes(m.getData('zone')) && 
        m.getData('type') !== 'crab'
    );
    
    if (!targets.length) return;
    
    targets.sort((a, b) => b.getData('points') - a.getData('points'));
    const target = targets[0];
    const tx = target.getData('posX') || target.x || 500;
    const ty = target.getData('posY') || target.y || 100;
    
    gameScene.tweens.add({
        targets: seagullSprite, x: tx, y: ty - 60, duration: 500, ease: 'Sine.easeInOut',
        onComplete: () => {
            const hl = gameScene.add.circle(tx, ty, 35, 0xffff00, 0.4).setDepth(4).setStrokeStyle(3, 0xffff00);
            gameScene.tweens.add({ targets: hl, scale: 1.5, alpha: 0, duration: 1500, onComplete: () => hl.destroy() });
            gameScene.tweens.add({ targets: seagullSprite, x: Phaser.Math.Between(200, 800), y: 50, duration: 800, ease: 'Sine.easeInOut' });
        }
    });
}

// ═══════════════════════════════════════════════════════════════════
// ПРИЛИВ
// ═══════════════════════════════════════════════════════════════════

function updateTide() {
    if (!gameScene) return;
    
    const bg = backgroundSprites.find(b => b.level === currentTideLevel);
    if (bg) gameScene.tweens.add({ targets: bg.sprite, alpha: 1, duration: 2000, ease: 'Sine.easeIn' });
    
    const zoneToFlood = currentTideLevel === 1 ? 'far' : currentTideLevel === 2 ? 'middle' : 'near';
    
    mollusks.forEach(m => {
        if (m.active && m.getData('zone') === zoneToFlood && !m.getData('collected')) {
            m.disableInteractive();
            const py = m.getData('posY') || m.y || 0;
            gameScene.tweens.add({
                targets: m,
                alpha: 0,
                y: py + 30,
                duration: 500,
                onComplete: () => { if (m.active) m.destroy(); }
            });
        }
    });
    
    mollusks = mollusks.filter(m => m.active && m.getData('zone') !== zoneToFlood);
    
    document.getElementById('tide-fill').style.height = `${(currentTideLevel / 3) * 100}%`;
    
    if (currentTideLevel === 1) updateCookReaction('warning', 'Вода прибывает!');
    else if (currentTideLevel === 2) updateCookReaction('warning', 'Скорее!!!');
}

// ═══════════════════════════════════════════════════════════════════
// HUD UPDATES
// ═══════════════════════════════════════════════════════════════════

function updateHUD() {
    document.getElementById('hud-mollusks').textContent = mollusksCollected;
    document.getElementById('hud-score').textContent = score;
    document.getElementById('hud-time').textContent = timeRemaining;
    if (timeRemaining <= 10) document.getElementById('hud-time').style.color = '#ef4444';
}

function updateCookReaction(type, text) {
    const sprite = document.getElementById('cook-sprite');
    const cookText = document.getElementById('cook-text');
    
    sprite.src = type === 'sad' ? ASSETS.cook_sad : ASSETS.cook_happy;
    sprite.className = `cook-sprite-img ${type === 'sad' ? 'sad' : 'happy'}`;
    cookText.textContent = text;
    
    setTimeout(() => { sprite.className = 'cook-sprite-img'; }, 500);
}

// ═══════════════════════════════════════════════════════════════════
// ЗАВЕРШЕНИЕ
// ═══════════════════════════════════════════════════════════════════

function endGame() {
    isGameActive = false;
    [gameTimer, tideTimer, spawnTimer, seagullTimer].forEach(t => t && clearInterval(t));
    
    const timePlayed = GAME_CONFIG.gameDuration - timeRemaining;
    
    fetch(API_ROUTES.submitResult, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({
            session_id: gameSessionId, score, mollusks_collected: mollusksCollected,
            far_zone_collected: farZoneCollected, middle_zone_collected: middleZoneCollected,
            near_zone_collected: nearZoneCollected, crabs_clicked: crabsClicked, time_played: timePlayed
        })
    })
    .then(r => r.json())
    .then(showResult)
    .catch(() => {
        showResult({
            is_success: mollusksCollected >= GAME_CONFIG.minMollusks, score,
            rewards: mollusksCollected >= GAME_CONFIG.minMollusks ? { food_days: 5, money: score * 10 } : {}
        });
    });
}

function showResult(data) {
    const box = document.getElementById('result-box');
    const title = document.getElementById('result-title');
    const emoji = document.getElementById('result-emoji');
    const stats = document.getElementById('result-stats');
    const rewards = document.getElementById('rewards-section');
    const rewardsList = document.getElementById('rewards-list');
    
    box.className = `result-box ${data.is_success ? 'success' : 'failure'}`;
    title.className = `result-title ${data.is_success ? 'success' : 'failure'}`;
    title.textContent = data.is_success ? '🎉 Отлично!' : '😔 Не хватило...';
    emoji.innerHTML = `<img src="${data.is_success ? ASSETS.cook_happy : ASSETS.cook_sad}" style="width:80px;height:80px;" onerror="this.parentElement.textContent='${data.is_success ? '👨‍🍳😄' : '👨‍🍳😞'}'">`;
    
    stats.innerHTML = `
        <div class="stat-item"><div class="stat-label">Моллюсков</div><div class="stat-value">${mollusksCollected}</div></div>
        <div class="stat-item"><div class="stat-label">Очки</div><div class="stat-value">${score}</div></div>
        <div class="stat-item"><div class="stat-label">Дальняя</div><div class="stat-value">${farZoneCollected}</div></div>
        <div class="stat-item"><div class="stat-label">Средняя</div><div class="stat-value">${middleZoneCollected}</div></div>
        <div class="stat-item"><div class="stat-label">Ближняя</div><div class="stat-value">${nearZoneCollected}</div></div>
        <div class="stat-item"><div class="stat-label">Крабов</div><div class="stat-value">${crabsClicked}</div></div>
    `;
    
    if (data.is_success && data.rewards) {
        rewards.style.display = 'block';
        rewardsList.innerHTML = `<div class="reward-item">🍖 +${data.rewards.food_days} дней еды</div><div class="reward-item">💰 +${data.rewards.money.toLocaleString()} ₽</div>`;
    } else {
        rewards.style.display = 'none';
    }
    
    document.getElementById('result-dialog').style.display = 'flex';
}

function showAlreadyCompletedDialog() {
    document.getElementById('intro-dialog').innerHTML = `
        <div class="dialog-box">
            <div class="dialog-character">✅</div>
            <div class="dialog-title">Уже пройдено!</div>
            <div class="dialog-text">Вы уже собрали моллюсков на этой остановке.<br>Можете продолжить путешествие!</div>
            <button class="btn-start" onclick="continueJourney()">Продолжить путь</button>
        </div>
    `;
    document.getElementById('intro-dialog').style.display = 'flex';
}

function continueJourney() {
    window.location.href = '{{ route("ship_game.index") }}';
}

function update() {
    if (waterOverlaySprite && isGameActive) {
        waterOverlaySprite.tilePositionX += 0.5;
        waterOverlaySprite.tilePositionY += 0.2;
    }
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => { game = new Phaser.Game(config); });
</script>
@endpush
@endsection
