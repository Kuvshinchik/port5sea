@extends('layouts.app') {{-- или твой базовый шаблон --}}

@section('content')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>
@endpush
@push('style')
    <style>
        body { 
            margin: 0; 
            padding: 20px;
            background: #2c3e50;
            font-family: Arial, sans-serif;
        }
        #game-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1 {
            color: white;
            text-align: center;
        }
        
        /* Стили для блока управления игрой */
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
            background: linear-gradient(135deg, #34495e, #2c3e50);
            border: 2px solid #3498db;
            border-radius: 15px;
            padding: 25px 30px;
            color: #ecf0f1;
            text-align: left;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        #game-description h3 {
            color: #3498db;
            margin-top: 0;
            font-size: 22px;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
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
        
        /* Скрытие блока после старта игры */
        #game-controls.game-started #start-button {
            display: none;
        }
        
        #game-controls.game-started #game-description {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@endpush

<div class="container mt-5">
    <h2 class="text-center mb-4">🚢 Путешествие теплохода: Мурманск - Архангельск → Анадырь</h2>
    <div id="game-container"></div>
    
    <!-- Блок управления игрой -->
    <div id="game-controls">
        <button id="start-button" onclick="startGame()">🚀 Начать путешествие</button>
        
        <div id="game-description">
            <h3>🗺️ Описание игры и правила</h3>
            
            <p>
                <span class="emoji-icon">⚓</span>
                Добро пожаловать на борт теплохода! Вам предстоит совершить увлекательное путешествие 
                из <span class="highlight">Мурманска</span> в <span class="highlight">Анадырь</span> 
                по легендарному <span class="highlight">Северному морскому пути</span>.
            </p>
            
            <p>
                <span class="emoji-icon">📋</span>
                <strong>Перед отправлением вам нужно:</strong>
            </p>
            <ul>
                <li>🧑‍🤝‍🧑 Набрать команду для путешествия</li>
                <li>🎒 Собрать необходимое снаряжение</li>
                <li>💰 Подготовить деньги на расходы</li>
                <li>🍞 Закупить продовольствие</li>
            </ul>
            
            <p>
                <span class="emoji-icon">🎯</span>
                <strong>Во время путешествия:</strong>
            </p>
            <ul>
                <li>На каждой остановке вы будете выполнять различные задания</li>
                <li>Нажмите <span class="highlight">"Начать уровень"</span> чтобы пройти задание</li>
                <li>После выполнения задания нажмите <span class="highlight">"Продолжить плавание"</span></li>
            </ul>
            
            <p>
                <span class="emoji-icon">🏆</span>
                Успешно пройдите все этапы путешествия и доберитесь до Анадыря!
            </p>
            
            <p style="text-align: center; margin-top: 20px; color: #95a5a6; font-size: 14px;">
                <!-- Здесь можно добавить дополнительную информацию -->
                Нажмите кнопку "Начать путешествие" чтобы приступить к игре
            </p>
        </div>
    </div>
</div>	
@push('scripts')    
    <script>
        // Конфигурация игры
        const config = {
            type: Phaser.AUTO,
            width: 1200,
            height: 800,
            backgroundColor: '#87CEEB',
            parent: 'game-container',
            scene: {
                preload: preload,
                create: create
            }
        };

        const game = new Phaser.Game(config);

        // Глобальные переменные
        let ship;
        let routePoints;
        let currentPoint = 0;
        let buttons;
        let isMoving = false;
        let stopNameText;
        let gameScene; // Сохраняем ссылку на сцену
        let gameStarted = false; // Флаг запуска игры

        function preload() {
            // Загружаем карту
            this.load.image('map', 'assets/game/murmansk/map_01.png');
            
            // Загружаем спрайт теплохода
            // Положи файл ship.png в ту же папку, что и этот HTML
            this.load.image('ship', 'assets/game/murmansk/ship.png');
        }

        function create() {
            // Сохраняем ссылку на сцену для использования в startGame()
            gameScene = this;
            
            // Добавляем карту как фон
            const map = this.add.image(600, 400, 'map');
            
            // Масштабируем карту под размер экрана
            const scaleX = 1200 / map.width;
            const scaleY = 800 / map.height;
            const scale = Math.max(scaleX, scaleY);
            map.setScale(scale);

            // Координаты точек маршрута
            // ВАЖНО: Эти координаты примерные, подбери их под свою карту!
            // Для этого раскомментируй код в конце функции create
            routePoints = [
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
            ];

            // Рисуем маршрут красной линией
            const graphics = this.add.graphics();
            graphics.lineStyle(4, 0xFF0000, 0.8);
            graphics.beginPath();
            graphics.moveTo(routePoints[0].x, routePoints[0].y);
            routePoints.forEach(point => {
                graphics.lineTo(point.x, point.y);
            });
            graphics.strokePath();

            // Рисуем черные кружочки на остановках
            routePoints.forEach(point => {
                if (point.isStop) {
                    graphics.fillStyle(0x000000, 1);
                    graphics.fillCircle(point.x, point.y, 10);
                    graphics.lineStyle(2, 0xFFFFFF, 1);
                    graphics.strokeCircle(point.x, point.y, 10);
                }
            });

            // Создаём теплоход из загруженного спрайта
            ship = this.add.image(
                routePoints[0].x, 
                routePoints[0].y, 
                'ship'
            );
            ship.setScale(0.3); // Масштабируем под размер карты
            ship.setOrigin(0.5, 0.5);
            
            // Добавляем тень под корабль
            const shadow = this.add.ellipse(
                ship.x, 
                ship.y + 15, 
                60, 
                20, 
                0x000000, 
                0.3
            );
            shadow.setDepth(-1);
            
            // Привязываем тень к кораблю
            ship.shadow = shadow;

            // Текст с названием остановки
            stopNameText = this.add.text(600, 50, '', {
                fontSize: '32px',
                fontFamily: 'Arial',
                color: '#ffffff',
                backgroundColor: '#000000',
                padding: { x: 20, y: 10 },
                align: 'center'
            });
            stopNameText.setOrigin(0.5);
            stopNameText.setVisible(false);

            // Создаём группу кнопок
            buttons = this.add.container(0, 0);
            buttons.setVisible(false);

            // Кнопка "Продолжить плавание"
            const continueBtn = createButton(
                this, 
                300, 
                200, 
                '⛵ Продолжить плавание',
                () => {
                    stopNameText.setVisible(false);
                    moveToNextPoint(this);
                }
            );

            // Кнопка "Перейти к следующему уровню"
            const levelBtn = createButton(
                this, 
                300, 
                280, 
                '🎮 Начать уровень',
                () => startLevel(this)
            );

            buttons.add([continueBtn, levelBtn]);

            // Показываем информационный текст до запуска игры
            const waitingText = this.add.text(600, 750, 
                '👇 Нажмите кнопку "Начать путешествие" ниже, чтобы начать игру', {
                fontSize: '18px',
                fontFamily: 'Arial',
                color: '#ffffff',
                backgroundColor: '#27ae60',
                padding: { x: 15, y: 8 }
            });
            waitingText.setOrigin(0.5);
            waitingText.setName('waitingText'); // Даём имя для последующего удаления

            // РАСКОММЕНТИРУЙ ЭТИ СТРОКИ, ЧТОБЫ УЗНАТЬ КООРДИНАТЫ ТОЧЕК НА КАРТЕ
            // Кликай по карте и смотри координаты в консоли браузера (F12)
            
            this.input.on('pointerdown', (pointer) => {
                console.log(`{ x: ${Math.round(pointer.x)}, y: ${Math.round(pointer.y)}, isStop: true, name: "Название" },`);
            });
        }

        // Функция запуска игры (вызывается при нажатии кнопки "Старт")
        function startGame() {
            if (gameStarted) return; // Предотвращаем повторный запуск
            gameStarted = true;
            
            // Скрываем кнопку старта и добавляем класс
            const controlsBlock = document.getElementById('game-controls');
            controlsBlock.classList.add('game-started');
            
            // Удаляем информационный текст ожидания
            const waitingText = gameScene.children.getByName('waitingText');
            if (waitingText) {
                waitingText.destroy();
            }
            
            // Показываем остановку в Мурманске (первая точка маршрута)
            // currentPoint уже равен 0
            const murmansk = routePoints[currentPoint];
            showButtons(gameScene, murmansk);
            
            console.log('🚀 Игра запущена! Первая остановка: Мурманск');
        }

        // Функция создания красивой кнопки
        function createButton(scene, x, y, text, callback) {
            const button = scene.add.container(x, y);

            // Фон кнопки с градиентом
            const bg = scene.add.rectangle(0, 0, 350, 60, 0x3498db);
            bg.setStrokeStyle(3, 0x2980b9);

            // Тень кнопки
            const shadow = scene.add.rectangle(3, 3, 350, 60, 0x000000, 0.3);

            // Текст кнопки
            const label = scene.add.text(0, 0, text, {
                fontSize: '22px',
                color: '#ffffff',
                fontFamily: 'Arial',
                fontStyle: 'bold'
            });
            label.setOrigin(0.5);

            button.add([shadow, bg, label]);

            // Интерактивность
            bg.setInteractive({ useHandCursor: true })
                .on('pointerover', () => {
                    bg.setFillStyle(0x2980b9);
                    scene.tweens.add({
                        targets: button,
                        scaleX: 1.05,
                        scaleY: 1.05,
                        duration: 100
                    });
                })
                .on('pointerout', () => {
                    bg.setFillStyle(0x3498db);
                    scene.tweens.add({
                        targets: button,
                        scaleX: 1,
                        scaleY: 1,
                        duration: 100
                    });
                })
                .on('pointerdown', () => {
                    scene.tweens.add({
                        targets: button,
                        scaleX: 0.95,
                        scaleY: 0.95,
                        duration: 50,
                        yoyo: true,
                        onComplete: callback
                    });
                });

            return button;
        }

        // Функция движения к следующей точке
        function moveToNextPoint(scene) {
            if (isMoving) return;
            
            buttons.setVisible(false);
            currentPoint++;

            if (currentPoint >= routePoints.length) {
                // Маршрут завершён!
                const endText = scene.add.text(600, 400, 
                    '🎉 Поздравляем!\nМаршрут Архангельск-Мурманск пройден!', {
                    fontSize: '36px',
                    fontFamily: 'Arial',
                    color: '#ffffff',
                    backgroundColor: '#27ae60',
                    padding: { x: 30, y: 20 },
                    align: 'center'
                });
                endText.setOrigin(0.5);
                
                // Ждем 2.5 секунды, затем плавно исчезаем за 0.5 секунды
                setTimeout(() => {
                    scene.tweens.add({
                        targets: endText,
                        alpha: 0,
                        scaleX: 0.8,
                        scaleY: 0.8,
                        duration: 500,
                        ease: 'Power2',
                        onComplete: () => {
                            endText.destroy();
                        }
                    });
                }, 2500);
                
                return;
            }

            isMoving = true;
            const nextPoint = routePoints[currentPoint];

            // Рассчитываем расстояние и угол поворота
            const distance = Phaser.Math.Distance.Between(
                ship.x, ship.y,
                nextPoint.x, nextPoint.y
            );
            
            // Поворачиваем корабль по направлению движения
            const angle = Phaser.Math.Angle.Between(
                ship.x, ship.y,
                nextPoint.x, nextPoint.y
            );
            
            scene.tweens.add({
                targets: ship,
                rotation: angle,
                duration: 300,
                ease: 'Power2'
            });

            const duration = distance * 8; // Скорость движения

            // Анимация движения корабля
            scene.tweens.add({
                targets: ship,
                x: nextPoint.x,
                y: nextPoint.y,
                duration: duration,
                ease: 'Sine.easeInOut',
                onUpdate: () => {
                    // Двигаем тень вместе с кораблём
                    ship.shadow.x = ship.x;
                    ship.shadow.y = ship.y + 15;
                },
                onComplete: () => {
                    isMoving = false;
                    
                    if (nextPoint.isStop) {
                        showButtons(scene, nextPoint);
                    } else {
                        // Продолжаем движение
                        setTimeout(() => moveToNextPoint(scene), 200);
                    }
                }
            });

            // Эффект волн за кораблём
            if (currentPoint % 2 === 0) {
                const wave = scene.add.ellipse(
                    ship.x, 
                    ship.y, 
                    30, 
                    15, 
                    0x4FC3F7, 
                    0.5
                );
                scene.tweens.add({
                    targets: wave,
                    scaleX: 2,
                    scaleY: 2,
                    alpha: 0,
                    duration: 1000,
                    onComplete: () => wave.destroy()
                });
            }
        }

        // Показываем кнопки на остановке
        function showButtons(scene, point) {
            console.log('🛑 Остановка:', point.name);
            
            // Показываем название остановки
            stopNameText.setText(`📍 ${point.name}`);
            stopNameText.setVisible(true);
            
            // Анимация появления названия
            stopNameText.setAlpha(0);
            stopNameText.setScale(0.8);
            scene.tweens.add({
                targets: stopNameText,
                alpha: 1,
                scaleX: 1,
                scaleY: 1,
                duration: 400,
                ease: 'Back.easeOut'
            });

            buttons.setVisible(true);

            // Анимация появления кнопок
            buttons.setAlpha(0);
            buttons.setScale(0.8);
            scene.tweens.add({
                targets: buttons,
                alpha: 1,
                scaleX: 1,
                scaleY: 1,
                duration: 400,
                ease: 'Back.easeOut'
            });
        }

        // Функция запуска уровня игры
        function startLevel(scene) {
            const currentStop = routePoints[currentPoint];
            console.log('🎮 Запуск уровня:', currentStop.level, '-', currentStop.name);
            
            // Здесь будет переход к игровому уровню
            // Например: scene.scene.start('Level' + currentStop.level);
			
            
            // Пока просто показываем сообщение
            const levelText = scene.add.text(600, 300, 
                `🎮 Уровень ${currentStop.level}\n"${currentStop.name}"\n\nЗдесь будет игровой уровень!`, {
                fontSize: '28px',
                fontFamily: 'Arial',
                color: '#ffffff',
                backgroundColor: '#e74c3c',
                padding: { x: 30, y: 20 },
                align: 'center'
            });
            levelText.setOrigin(0.5);
            
            // Закрываем через 3 секунды
            setTimeout(() => {
                levelText.destroy();
            }, 3000);
        }
    </script>
@endpush
@endsection