@extends('layouts.app')

@section('content')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.min.js"></script>
@endpush

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Nunito:wght@400;600;700;800&display=swap');
    
    .game-wrapper {
        background: linear-gradient(135deg, #1a2a3a 0%, #0d1b2a 50%, #1b263b 100%);
        min-height: 100vh;
        padding: 20px;
    }
    
    h2.game-title {
        font-family: 'Russo One', sans-serif;
        color: #4fc3f7;
        text-align: center;
        margin-bottom: 20px;
        font-size: 2rem;
        text-shadow: 0 2px 10px rgba(79, 195, 247, 0.3);
    }
    
    #game-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 60px rgba(79, 195, 247, 0.2), 0 20px 40px rgba(0, 0, 0, 0.4);
        margin: 0 auto;
        max-width: 1200px;
    }
    
    .instructions {
        max-width: 1200px;
        margin: 20px auto 0;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(79, 195, 247, 0.2);
        border-radius: 12px;
        padding: 20px 30px;
        color: #b0bec5;
        text-align: center;
    }
    
    .instructions h3 { color: #4fc3f7; margin-bottom: 10px; font-family: 'Russo One', sans-serif; }
    
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
    
    .depart-button-container { max-width: 1200px; text-align: center; margin: 20px auto 10px; }
    
    .btn-depart {
        padding: 15px 50px;
        font-size: 1.3rem;
        font-family: 'Russo One', sans-serif;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        border: none;
        color: white;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        transition: all 0.3s ease;
    }
    
    .btn-depart:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(39, 174, 96, 0.5); }
    .btn-depart:disabled { background: #7f8c8d; cursor: not-allowed; transform: none; }
    
    .game-notification {
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10000;
        padding: 15px 30px;
        border-radius: 10px;
        font-family: 'Nunito', Arial;
        font-weight: bold;
        color: white;
        animation: slideDown 0.3s ease-out;
    }
    
    .game-notification.success { background: #27ae60; }
    .game-notification.error { background: #e74c3c; }
    .game-notification.warning { background: #f39c12; }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        to { opacity: 1; transform: translateX(-50%) translateY(0); }
    }
    
    .loading-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #4fc3f7;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

<div class="game-wrapper">
    <div class="container">
        <h2 class="game-title">⚓ ПОРТ МУРМАНСК – ПОДГОТОВКА ЭКСПЕДИЦИИ</h2>
        <div id="game-container"></div>
        <div class="depart-button-container">
            <button id="btn-depart" class="btn-depart">🚢 В путь!</button>
        </div>
        <div class="instructions">
            <h3>🎮 Как играть</h3>
            <p>
                Кликайте по зданиям порта, чтобы нанять команду, купить снаряжение и продовольствие. 
                Возьмите подработку на <span class="key">Доске объявлений</span>, чтобы заработать дополнительные деньги.
                Когда будете готовы – нажмите <span class="key">В путь</span>!
            </p>
            @if(count($gameState['user_toys']) > 0)
            <p style="margin-top: 10px; color: #4fc3f7;">
                🎁 У вас активировано {{ count($gameState['user_toys']) }} игрушек! 
            </p>
            @endif
            @if($gameState['has_infinite_fuel'])
            <p style="color: #2ecc71;">⛽ Бонус «Бесконечное топливо» активен!</p>
            @endif
        </div>
    </div>
</div>

<div class="loading-overlay" id="loading">
    <div class="loading-spinner"></div>
</div>

@push('scripts')
<script src="{{ asset('js/ship_game/port_config.js') }}"></script>
<script>
// Данные из Laravel
const API_ROUTES = {
    state: '{{ route("ship_game.api.state") }}',
    hireCrew: '{{ route("ship_game.api.crew.hire") }}',
    fireCrew: '{{ route("ship_game.api.crew.fire") }}',
    buyEquipment: '{{ route("ship_game.api.equipment.buy") }}',
    sellEquipment: '{{ route("ship_game.api.equipment.sell") }}',
    acceptJob: '{{ route("ship_game.api.job.accept") }}',
    cancelJob: '{{ route("ship_game.api.job.cancel") }}',
    readyToDepart: '{{ route("ship_game.api.ready") }}',
    depart: '{{ route("ship_game.api.depart") }}',
};
const CSRF_TOKEN = '{{ csrf_token() }}';
const INITIAL_GAME_STATE = @json($gameState);
const CREW_MEMBERS = @json($crewMembers);
const EQUIPMENT_ITEMS = @json($equipment);
const FOOD_ITEMS = @json($food);
const MEDICINE_ITEMS = @json($medicine);
const JOB_OFFERS = @json($jobs);
const MAX_CREW = {{ $maxCrew }};
const MIN_CREW_REQUIRED = {{ $minCrewRequired }};
const MAX_CARGO_CAPACITY = {{ $maxCargoCapacity }};
</script>
<script src="{{ asset('js/ship_game/port_game.js') }}"></script>
@endpush
@endsection
