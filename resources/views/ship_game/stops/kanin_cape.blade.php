@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Канин мыс</h1>
    <p>Этап подготовки API для мини-игры «Канин мыс».</p>

    <div class="alert alert-info">
        Бонус «Крыс механик»: <strong>{{ $hasMechanicRat ? 'активен' : 'не активен' }}</strong>
    </div>
</div>

<script>
    window.kaninCapeConfig = {
        hasMechanicRat: @json($hasMechanicRat),
        saveProgressUrl: @json(route('ship_game.kanin_cape.progress')),
        completeUrl: @json(route('ship_game.kanin_cape.complete')),
        gameState: @json($gameState),
    };
</script>
@endsection
