@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .debug-panel {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        min-height: 100vh;
        padding: 30px;
        color: #fff;
    }
    
    .debug-title {
        color: #ff6b6b;
        font-size: 2rem;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .debug-title span {
        background: linear-gradient(90deg, #ff6b6b, #feca57);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .debug-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }
    
    .debug-card h3 {
        color: #4fc3f7;
        margin-bottom: 20px;
        font-size: 1.2rem;
        border-bottom: 1px solid rgba(79, 195, 247, 0.3);
        padding-bottom: 10px;
    }
    
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .stat-item {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
    }
    
    .stat-item .label {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 5px;
    }
    
    .stat-item .value {
        font-size: 1.3rem;
        font-weight: bold;
        color: #4fc3f7;
    }
    
    .btn-debug {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 5px;
    }
    
    .btn-danger { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4); }
    
    .btn-warning { background: linear-gradient(135deg, #f39c12, #d68910); color: white; }
    .btn-warning:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4); }
    
    .btn-info { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
    .btn-info:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4); }
    
    .btn-success { background: linear-gradient(135deg, #27ae60, #1e8449); color: white; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4); }
    
    .input-group {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .input-group label {
        min-width: 120px;
        color: #aaa;
    }
    
    .input-group input, .input-group select {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 10px 15px;
        color: white;
        flex: 1;
        max-width: 200px;
    }
    
    .input-group input:focus, .input-group select:focus {
        outline: none;
        border-color: #4fc3f7;
    }
    
    .log-container {
        max-height: 300px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        padding: 15px;
    }
    
    .log-item {
        padding: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.9rem;
    }
    
    .log-item:last-child {
        border-bottom: none;
    }
    
    .log-item .time {
        color: #888;
        font-size: 0.8rem;
    }
    
    .log-item .type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-right: 10px;
    }
    
    .log-item .type.hire { background: #27ae60; }
    .log-item .type.fire { background: #e74c3c; }
    .log-item .type.spend { background: #f39c12; }
    .log-item .type.earn { background: #2ecc71; }
    .log-item .type.debug_reset { background: #9b59b6; }
    .log-item .type.debug_set { background: #e91e63; }
    .log-item .type.debug_teleport { background: #00bcd4; }
    
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 10px;
        color: white;
        font-weight: bold;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }
    
    .notification.success { background: #27ae60; }
    .notification.error { background: #e74c3c; }
    
    @keyframes slideIn {
        from { transform: translateX(100px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .warning-banner {
        background: linear-gradient(90deg, #e74c3c, #c0392b);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .no-game {
        text-align: center;
        padding: 50px;
        color: #888;
    }
    
    .no-game a {
        color: #4fc3f7;
    }
</style>
@endpush

<div class="debug-panel">
    <div class="container">
        <h1 class="debug-title">🔧 <span>Панель отладки игры</span></h1>
        
        <div class="warning-banner">
            ⚠️ Внимание! Эта панель только для разработчиков. Изменения необратимы!
        </div>
        
        @if($progress)
        <div class="row">
            <!-- Статистика -->
            <div class="col-lg-6">
                <div class="debug-card">
                    <h3>📊 Текущее состояние</h3>
                    <div class="stat-grid">
                        <div class="stat-item">
                            <div class="label">Деньги</div>
                            <div class="value">{{ number_format($stats['money'], 0, ',', ' ') }} ₽</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Еда</div>
                            <div class="value">{{ $stats['food_days'] }} дней</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Топливо</div>
                            <div class="value">{{ $stats['fuel_percent'] }}%</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Груз</div>
                            <div class="value">{{ $stats['cargo_used'] }} кг</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Команда</div>
                            <div class="value">{{ $stats['crew_count'] }} чел.</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Снаряжение</div>
                            <div class="value">{{ $stats['equipment_count'] }} шт.</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Подработки</div>
                            <div class="value">{{ $stats['jobs_count'] }} шт.</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Мораль</div>
                            <div class="value">{{ $stats['morale'] }}%</div>
                        </div>
                    </div>
                    <div style="margin-top: 15px; padding: 10px; background: rgba(0,0,0,0.3); border-radius: 8px;">
                        <strong>Остановка:</strong> {{ $stats['current_stop'] }}<br>
                        <strong>Фаза:</strong> {{ $stats['game_phase'] }}<br>
                        <strong>Дней в пути:</strong> {{ $stats['days_traveled'] }}
                    </div>
                </div>
            </div>
            
            <!-- Сброс -->
            <div class="col-lg-6">
                <div class="debug-card">
                    <h3>🗑️ Сброс данных</h3>
                    <p style="color: #888; margin-bottom: 20px;">Удаление данных без возможности восстановления</p>
                    
                    <button class="btn-debug btn-danger" onclick="fullReset()">
                        🔄 Полный сброс игры
                    </button>
                    <button class="btn-debug btn-warning" onclick="resetCrew()">
                        👥 Уволить всю команду
                    </button>
                    <button class="btn-debug btn-warning" onclick="resetEquipment()">
                        🧰 Удалить снаряжение
                    </button>
                    <button class="btn-debug btn-warning" onclick="resetJobs()">
                        💼 Отменить подработки
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Установка значений -->
            <div class="col-lg-6">
                <div class="debug-card">
                    <h3>⚙️ Установка значений</h3>
                    
                    <div class="input-group">
                        <label>💰 Деньги:</label>
                        <input type="number" id="money-input" value="{{ $stats['money'] }}" min="0" max="99999999">
                        <button class="btn-debug btn-info" onclick="setMoney()">Установить</button>
                    </div>
                    
                    <div class="input-group">
                        <label>🍞 Еда (дни):</label>
                        <input type="number" id="food-input" value="{{ $stats['food_days'] }}" min="0" max="365">
                        <button class="btn-debug btn-info" onclick="setFood()">Установить</button>
                    </div>
                    
                    <div class="input-group">
                        <label>⛽ Топливо (%):</label>
                        <input type="number" id="fuel-input" value="{{ $stats['fuel_percent'] }}" min="0" max="100">
                        <button class="btn-debug btn-info" onclick="setFuel()">Установить</button>
                    </div>
                    
                    <div class="input-group">
                        <label>🎮 Фаза игры:</label>
                        <select id="phase-select">
                            <option value="preparation" {{ $stats['game_phase'] == 'preparation' ? 'selected' : '' }}>Подготовка</option>
                            <option value="traveling" {{ $stats['game_phase'] == 'traveling' ? 'selected' : '' }}>В пути</option>
                            <option value="at_stop" {{ $stats['game_phase'] == 'at_stop' ? 'selected' : '' }}>На остановке</option>
                            <option value="completed" {{ $stats['game_phase'] == 'completed' ? 'selected' : '' }}>Завершено</option>
                        </select>
                        <button class="btn-debug btn-info" onclick="setPhase()">Установить</button>
                    </div>
                </div>
            </div>
            
            <!-- Телепортация -->
            <div class="col-lg-6">
                <div class="debug-card">
                    <h3>🚀 Телепортация</h3>
                    <p style="color: #888; margin-bottom: 20px;">Мгновенное перемещение к остановке</p>
                    
                    <div class="input-group">
                        <label>📍 Остановка:</label>
                        <select id="stop-select">
                            @foreach($routeStops as $stop)
                            <option value="{{ $stop->id }}" {{ $progress->current_stop_id == $stop->id ? 'selected' : '' }}>
                                {{ $stop->order_index }}. {{ $stop->name }}
                            </option>
                            @endforeach
                        </select>
                        <button class="btn-debug btn-success" onclick="teleport()">Телепорт!</button>
                    </div>
                </div>
                
                <div class="debug-card">
                    <h3>📜 Лог событий (последние 50)</h3>
                    <button class="btn-debug btn-info" onclick="loadLogs()" style="margin-bottom: 15px;">Обновить лог</button>
                    <div class="log-container" id="log-container">
                        <div class="no-game">Нажмите "Обновить лог" для загрузки</div>
                    </div>
                </div>
            </div>
        </div>
        
        @else
        <div class="debug-card no-game">
            <h3>Игра не найдена</h3>
            <p>У вас нет активной игры. <a href="{{ route('ship_game.index') }}">Начать игру</a></p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
const API_BASE = '/ship-game/debug/api';

function showNotification(message, type = 'success') {
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    
    const notif = document.createElement('div');
    notif.className = `notification ${type}`;
    notif.textContent = message;
    document.body.appendChild(notif);
    
    setTimeout(() => notif.remove(), 3000);
}

async function apiCall(endpoint, method = 'POST', data = null) {
    try {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        };
        
        if (data) options.body = JSON.stringify(data);
        
        const response = await fetch(API_BASE + endpoint, options);
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(result.message, 'error');
        }
        
        return result;
    } catch (error) {
        showNotification('Ошибка: ' + error.message, 'error');
    }
}

function fullReset() {
    if (confirm('⚠️ ВЫ УВЕРЕНЫ?\n\nЭто полностью удалит весь прогресс игры и создаст новую игру с начальными данными.\n\nДействие необратимо!')) {
        apiCall('/full-reset');
    }
}

function resetCrew() {
    if (confirm('Уволить всю команду?')) {
        apiCall('/reset-crew');
    }
}

function resetEquipment() {
    if (confirm('Удалить всё снаряжение и еду?')) {
        apiCall('/reset-equipment');
    }
}

function resetJobs() {
    if (confirm('Отменить все подработки?')) {
        apiCall('/reset-jobs');
    }
}

function setMoney() {
    const amount = parseInt(document.getElementById('money-input').value);
    apiCall('/set-money', 'POST', { amount });
}

function setFood() {
    const days = parseInt(document.getElementById('food-input').value);
    apiCall('/set-food', 'POST', { days });
}

function setFuel() {
    const percent = parseInt(document.getElementById('fuel-input').value);
    apiCall('/set-fuel', 'POST', { percent });
}

function setPhase() {
    const phase = document.getElementById('phase-select').value;
    apiCall('/set-phase', 'POST', { phase });
}

function teleport() {
    const stopId = parseInt(document.getElementById('stop-select').value);
    apiCall('/teleport', 'POST', { stop_id: stopId });
}

async function loadLogs() {
    try {
        const response = await fetch(API_BASE + '/logs', {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();
        
        const container = document.getElementById('log-container');
        
        if (result.logs && result.logs.length > 0) {
            container.innerHTML = result.logs.map(log => `
                <div class="log-item">
                    <span class="type ${log.type}">${log.type}</span>
                    ${log.description}
                    ${log.money_change !== 0 ? `<span style="color: ${log.money_change > 0 ? '#2ecc71' : '#e74c3c'}">(${log.money_change > 0 ? '+' : ''}${log.money_change} ₽)</span>` : ''}
                    <div class="time">${log.time}</div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="no-game">Лог пуст</div>';
        }
    } catch (error) {
        showNotification('Ошибка загрузки лога', 'error');
    }
}
</script>
@endpush
@endsection
