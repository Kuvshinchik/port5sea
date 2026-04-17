@extends('layouts.app')

@section('content')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>
@endpush

@push('styles')
<style>
    body { margin: 0; padding: 20px; background: #2c3e50; font-family: Arial, sans-serif; }
    #game-container { display: flex; justify-content: center; align-items: center; }
    h1, h2 { color: white; text-align: center; }
    
    #game-controls {
        max-width: 1200px;
        margin: 20px auto;
        text-align: center;
    }
    
    #start-button {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        padding: 40px 60px;
        font-size: 24px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        transition: all 0.3s ease;
        margin-bottom: 25px;
    }
    
    #start-button:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(39, 174, 96, 0.5); }
    #start-button:disabled { background: #7f8c8d; cursor: not-allowed; }
    
    #game-description {
        background: linear-gradient(135deg, #34495e, #2c3e50);
        border: 2px solid #3498db;
        border-radius: 15px;
        padding: 25px 30px;
        color: #ecf0f1;
        text-align: left;
        max-width: 900px;
        margin: 0 auto;
    }
    
    #game-description h3 { color: #3498db; text-align: center; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px; }
    #game-description .highlight { color: #f39c12; font-weight: bold; }
    
    #game-controls.game-started #start-button { display: none; }
    #game-controls.game-started #game-description { opacity: 0.5; }
    
    .loading-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none; justify-content: center; align-items: center; z-index: 9999;
    }
    .loading-spinner {
        width: 50px; height: 50px;
        border: 5px solid #4fc3f7; border-top-color: transparent;
        border-radius: 50%; animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    
    /* Стили для кнопок мини-игр */
    .mini-game-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
    }
    
    .btn-mini-game {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 25px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-mini-game:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(155, 89, 182, 0.5);
        color: white;
    }
    
    .stop-info-panel {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        border: 2px solid #f39c12;
        border-radius: 15px;
        padding: 20px;
        max-width: 600px;
        margin: 20px auto;
        color: white;
        text-align: center;
    }
    
    .stop-info-panel h3 {
        color: #f39c12;
        margin-bottom: 15px;
    }
    
    .stop-info-panel p {
        color: #bdc3c7;
        margin-bottom: 15px;
    }
</style>
@endpush

<div class="container mt-5">
    <h2 class="text-center mb-4">🚢 Путешествие теплохода: Мурманск → Анадырь</h2>
    <div id="game-container"></div>
    
    <div id="game-controls">
        @if($progress->game_phase === 'preparation')
        <a href="{{ route('ship_game.murmansk_port') }}" class="btn btn-primary btn-lg" id="start-button">
            🚀 Подготовка к путешествию
        </a>
        @elseif($progress->game_phase === 'at_stop')
        {{-- Показываем информацию об остановке и кнопку мини-игры --}}
        <div class="stop-info-panel">
            <h3>📍 {{ $progress->currentStop->name ?? 'Остановка' }}</h3>
            
            @php
                $stopName = $progress->currentStop->name ?? '';
                $levelNumber = $progress->currentStop->level_number ?? 0;
            @endphp
            
            @if(str_contains($stopName, 'Териберка'))
                <p>Кок Макарони сообщает, что запасы еды не такие свежие. Нужно собрать моллюсков во время отлива!</p>
                <div class="mini-game-buttons">
                    <a href="{{ route('ship_game.teriberika.index') }}" class="btn-mini-game">
                        🦪 Собрать моллюсков
                    </a>
                </div>
            @elseif(str_contains($stopName, 'Диксон'))
                <p>Здесь можно пополнить запасы топлива и провести ремонт судна.</p>
                <div class="mini-game-buttons">
                    <button class="btn-mini-game" onclick="alert('Мини-игра в разработке!')">
                        ⛽ Заправка судна
                    </button>
                </div>
            @elseif(str_contains($stopName, 'Тикси'))
                <p>Легендарный порт Тикси. Можно взять груз для доставки.</p>
                <div class="mini-game-buttons">
                    <button class="btn-mini-game" onclick="alert('Мини-игра в разработке!')">
                        📦 Погрузка товаров
                    </button>
                </div>
            @elseif(str_contains($stopName, 'Певек'))
                <p>Самый северный город России. Здесь суровые условия, но команда готова!</p>
                <div class="mini-game-buttons">
                    <button class="btn-mini-game" onclick="alert('Мини-игра в разработке!')">
                        ❄️ Борьба со льдом
                    </button>
                </div>
            @elseif(str_contains($stopName, 'Анадырь'))
                <p>🎉 Поздравляем! Вы достигли конечной точки маршрута!</p>
            @else
                <p>Исследуйте эту остановку.</p>
            @endif
            
            <button id="continue-journey-btn" class="btn-mini-game" style="background: linear-gradient(135deg, #27ae60, #2ecc71); margin-top: 15px;" onclick="startGame()">
                ⛵ Продолжить путешествие
            </button>
        </div>
        @else
        <button id="start-button" onclick="startGame()">🚀 Продолжить путешествие</button>
        @endif
         
        <div id="game-description" class="mt-48">
            <h3>🗺️ Описание игры</h3>
            <p>
                ⚓ Добро пожаловать на борт теплохода! Вам предстоит совершить увлекательное путешествие 
                из <span class="highlight">Мурманска</span> в <span class="highlight">Анадырь</span> 
                по легендарному <span class="highlight">Северному морскому пути</span>.
            </p>
            
            <p><strong>📋 Перед отправлением вам нужно:</strong></p>
            <ul>
                <li>🧑‍🤝‍🧑 Набрать команду для путешествия</li>
                <li>🎒 Собрать необходимое снаряжение</li>
                <li>💰 Подготовить деньги на расходы</li>
                <li>🍞 Закупить продовольствие</li>
            </ul>
            
            <p><strong>🎯 Во время путешествия:</strong></p>
            <ul>
                <li>На каждой остановке вы будете выполнять различные задания</li>
                <li>Нажмите <span class="highlight">"Начать уровень"</span> чтобы пройти задание</li>
                <li>После выполнения нажмите <span class="highlight">"Продолжить плавание"</span></li>
            </ul>
            
            <p>🏆 Успешно пройдите все этапы путешествия и доберитесь до Анадыря!</p>
            
            @if(count($gameState['user_toys']) > 0)
            <p style="color: #4fc3f7;">🎁 У вас активировано {{ count($gameState['user_toys']) }} игрушек!</p>
            @endif
        </div>
    </div>
</div>

<div class="loading-overlay" id="loading">
    <div class="loading-spinner"></div>
</div>

@push('scripts')
<script>
const API_ROUTES = {
    state: '{{ route("ship_game.api.state") }}',
    moveNext: '{{ route("ship_game.api.move") }}',
};
const CSRF_TOKEN = '{{ csrf_token() }}';
const INITIAL_GAME_STATE = @json($gameState);
const ROUTE_POINTS = @json($routePoints);

// Phaser конфигурация
const config = {
    type: Phaser.AUTO,
    width: 1200,
    height: 800,
    backgroundColor: '#87CEEB',
    parent: 'game-container',
    scene: { preload, create }
};

const game = new Phaser.Game(config);

let ship, currentPoint = {{ $progress->current_point_index }}, buttons, isMoving = false, stopNameText, gameScene;
let gameStarted = {{ $progress->game_phase !== 'preparation' ? 'true' : 'false' }};

function preload() {
    this.load.image('map', '/assets/game/murmansk/map_01.png');
    this.load.image('ship', '/assets/game/murmansk/ship.png');
}

function create() {
    gameScene = this;
    
    const map = this.add.image(600, 400, 'map');
    const scaleX = 1200 / map.width;
    const scaleY = 800 / map.height;
    map.setScale(Math.max(scaleX, scaleY));

    // Рисуем маршрут
    const graphics = this.add.graphics();
    graphics.lineStyle(4, 0xFF0000, 0.8);
    graphics.beginPath();
    graphics.moveTo(ROUTE_POINTS[0].x, ROUTE_POINTS[0].y);
    ROUTE_POINTS.forEach(point => graphics.lineTo(point.x, point.y));
    graphics.strokePath();

    // Рисуем остановки
    ROUTE_POINTS.forEach((point, index) => {
        if (point.isStop) {
            // Разные цвета для пройденных/текущей/будущих остановок
            let fillColor = 0x000000;
            let strokeColor = 0xFFFFFF;
            
            if (index < currentPoint) {
                fillColor = 0x27ae60; // Зелёный - пройдено
            } else if (index === currentPoint) {
                fillColor = 0xf39c12; // Оранжевый - текущая
            }
            
            graphics.fillStyle(fillColor, 1);
            graphics.fillCircle(point.x, point.y, 10);
            graphics.lineStyle(2, strokeColor, 1);
            graphics.strokeCircle(point.x, point.y, 10);
            
            // Название остановки
            const nameText = this.add.text(point.x, point.y + 20, point.name, {
                fontSize: '12px',
                color: '#ffffff',
                backgroundColor: 'rgba(0,0,0,0.7)',
                padding: { x: 4, y: 2 }
            });
            nameText.setOrigin(0.5, 0);
        }
    });

    // Корабль
    const startPoint = ROUTE_POINTS[currentPoint];
    ship = this.add.image(startPoint.x, startPoint.y, 'ship');
    ship.setScale(0.3);
    ship.setOrigin(0.5, 0.5);
    
    const shadow = this.add.ellipse(ship.x, ship.y + 15, 60, 20, 0x000000, 0.3);
    shadow.setDepth(-1);
    ship.shadow = shadow;

    // Текст остановки
    stopNameText = this.add.text(600, 50, '', {
        fontSize: '32px', fontFamily: 'Arial', color: '#ffffff',
        backgroundColor: '#000000', padding: { x: 20, y: 10 }, align: 'center'
    });
    stopNameText.setOrigin(0.5);
    stopNameText.setVisible(false);

    // Кнопки
    buttons = this.add.container(0, 0);
    buttons.setVisible(false);

    const continueBtn = createButton(this, 300, 200, '⛵ Продолжить плавание', () => {
        stopNameText.setVisible(false);
        moveToNextPoint(this);
    });

    const levelBtn = createButton(this, 300, 280, '🎮 Начать уровень', () => startLevel(this));

    buttons.add([continueBtn, levelBtn]);

    if (gameStarted && ROUTE_POINTS[currentPoint].isStop) {
        showButtons(this, ROUTE_POINTS[currentPoint]);
    }
    
    this.input.on('pointerdown', (pointer) => {
        console.log(`{ x: ${Math.round(pointer.x)}, y: ${Math.round(pointer.y)} }`);
    });
}

function startGame() {
    if (gameStarted) {
        // Если уже в режиме игры, двигаемся к следующей точке
        moveToNextPoint(gameScene);
        return;
    }
    
    gameStarted = true;
    document.getElementById('game-controls').classList.add('game-started');
    
    const startPoint = ROUTE_POINTS[currentPoint];
    showButtons(gameScene, startPoint);
}

function createButton(scene, x, y, text, callback) {
    const button = scene.add.container(x, y);
    const bg = scene.add.rectangle(0, 0, 350, 60, 0x3498db);
    bg.setStrokeStyle(3, 0x2980b9);
    const shadow = scene.add.rectangle(3, 3, 350, 60, 0x000000, 0.3);
    const label = scene.add.text(0, 0, text, { fontSize: '22px', color: '#ffffff', fontFamily: 'Arial', fontStyle: 'bold' });
    label.setOrigin(0.5);
    button.add([shadow, bg, label]);
    
    bg.setInteractive({ useHandCursor: true })
        .on('pointerover', () => { bg.setFillStyle(0x2980b9); scene.tweens.add({ targets: button, scaleX: 1.05, scaleY: 1.05, duration: 100 }); })
        .on('pointerout', () => { bg.setFillStyle(0x3498db); scene.tweens.add({ targets: button, scaleX: 1, scaleY: 1, duration: 100 }); })
        .on('pointerdown', () => { scene.tweens.add({ targets: button, scaleX: 0.95, scaleY: 0.95, duration: 50, yoyo: true, onComplete: callback }); });
    
    return button;
}

async function moveToNextPoint(scene) {
    if (isMoving) return;
    
    buttons.setVisible(false);
    
    try {
        const response = await fetch(API_ROUTES.moveNext, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
        });
        const result = await response.json();
        
        if (result.completed) {
            const endText = scene.add.text(600, 400, '🎉 Поздравляем!\nМаршрут пройден!', {
                fontSize: '36px', fontFamily: 'Arial', color: '#ffffff',
                backgroundColor: '#27ae60', padding: { x: 30, y: 20 }, align: 'center'
            });
            endText.setOrigin(0.5);
            return;
        }
        
        currentPoint++;
        isMoving = true;
        const nextPoint = ROUTE_POINTS[currentPoint];
        
        const angle = Phaser.Math.Angle.Between(ship.x, ship.y, nextPoint.x, nextPoint.y);
        scene.tweens.add({ targets: ship, rotation: angle, duration: 300, ease: 'Power2' });
        
        const distance = Phaser.Math.Distance.Between(ship.x, ship.y, nextPoint.x, nextPoint.y);
        const duration = distance * 8;
        
        scene.tweens.add({
            targets: ship,
            x: nextPoint.x,
            y: nextPoint.y,
            duration: duration,
            ease: 'Sine.easeInOut',
            onUpdate: () => { ship.shadow.x = ship.x; ship.shadow.y = ship.y + 15; },
            onComplete: () => {
                isMoving = false;
                if (nextPoint.isStop) {
                    // Перезагружаем страницу чтобы показать панель мини-игры
                    window.location.reload();
                } else {
                    setTimeout(() => moveToNextPoint(scene), 200);
                }
            }
        });
        
    } catch (error) {
        console.error('Error:', error);
        isMoving = false;
    }
}

function showButtons(scene, point) {
    stopNameText.setText(`📍 ${point.name}`);
    stopNameText.setVisible(true);
    stopNameText.setAlpha(0);
    stopNameText.setScale(0.8);
    scene.tweens.add({ targets: stopNameText, alpha: 1, scaleX: 1, scaleY: 1, duration: 400, ease: 'Back.easeOut' });
    
    buttons.setVisible(true);
    buttons.setAlpha(0);
    buttons.setScale(0.8);
    scene.tweens.add({ targets: buttons, alpha: 1, scaleX: 1, scaleY: 1, duration: 400, ease: 'Back.easeOut' });
}

function startLevel(scene) {
    const currentStop = ROUTE_POINTS[currentPoint];
    console.log('🎮 Запуск уровня:', currentStop.level, '-', currentStop.name);
    
    if (currentStop.level === 1) {
        window.location.href = '{{ route("ship_game.murmansk_port") }}';
        return;
    }
    
    // Перенаправление на мини-игры в зависимости от остановки
    if (currentStop.name.includes('Териберка')) {
        window.location.href = '{{ route("ship_game.teriberika.index") }}';
        return;
    }
    
    const levelText = scene.add.text(600, 300, 
        `🎮 Уровень ${currentStop.level}\n"${currentStop.name}"\n\nЗдесь будет игровой уровень!`, {
        fontSize: '28px', fontFamily: 'Arial', color: '#ffffff',
        backgroundColor: '#e74c3c', padding: { x: 30, y: 20 }, align: 'center'
    });
    levelText.setOrigin(0.5);
    
    setTimeout(() => levelText.destroy(), 3000);
}
</script>
@endpush
@endsection
