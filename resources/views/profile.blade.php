@extends('layouts.app')  <!-- Использует тот же layout с head и footer -->

@section('content')
{{--
    <h1>Привет, {{ auth()->user()->name }}!</h1>  <!-- Пример: имя пользователя -->
    <p>Это ваша защищенная страница. Вы вошли в систему.</p>
    <!-- Добавьте любой контент, похожий на index.blade.php, но без слайдера и т.д. -->
--}}


<div class="container mt-5">
    <h2 class="text-center mb-4">Колесо Фортуны</h2>

    <div class="text-center mb-4">
        <p>Ваш баланс: <strong>{{ $prizePoints->balance }}</strong> баллов</p>
        @if($prizePoints->last_spin_at)
            <p>Последнее вращение: {{ $prizePoints->last_spin_at->format('d.m.Y H:i') }}</p>
        @endif
    </div>

    <!-- Контейнер колеса -->
    <div class="wheel-container position-relative d-inline-block" style="width: 400px; height: 400px;">
        <!-- Внешний обод с призами -->
        <div class="prize-icon" style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%);">
            <img src="{{ asset('images/prize_12.png') }}" alt="12 баллов" width="50">
        </div>
        <div class="prize-icon" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);">
            <img src="{{ asset('images/prize_3.png') }}" alt="3 балла" width="50">
        </div>
        <div class="prize-icon" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);">
            <img src="{{ asset('images/prize_6.png') }}" alt="6 баллов" width="50">
        </div>
        <div class="prize-icon" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%);">
            <img src="{{ asset('images/prize_9.png') }}" alt="9 баллов" width="50">
        </div>

        <!-- Само колесо -->
        <div id="wheel" class="position-relative" style="width: 350px; height: 350px; margin: auto;">
            <img id="compass" src="{{ asset('images/Compass_2.png') }}" alt="Колесо" style="width: 100%; height: auto; transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);">
        </div>
    </div>

    <!-- Кнопка вращения -->
    <div class="text-center mt-4">
        <button id="spin-btn" class="btn btn-success btn-lg">Крутить!</button>
    </div>

    <!-- Сообщения -->
    <div id="message" class="mt-3 text-center text-danger fw-bold"></div>
</div>

@push('scripts')
<script>
document.getElementById('spin-btn').addEventListener('click', async function () {
    const btn = this;
    const messageEl = document.getElementById('message');
    const compass = document.getElementById('compass');

    // Отключаем кнопку
    btn.disabled = true;
    messageEl.textContent = '';

    try {
        const res = await fetch("{{ route('fortune.spin') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {
            // Вычисляем итоговый угол вращения (несколько оборотов + нужный сектор)
            const extraRotations = 5; // например, 5 полных оборотов
            const finalRotation = 360 * extraRotations + (360 - data.angle); // потому что вращение по часовой

            // Применяем вращение
            compass.style.transform = `rotate(${finalRotation}deg)`;

            // Показываем результат
            setTimeout(() => {
                alert(`Вы выиграли ${data.points} баллов!\nВаш баланс: ${data.total_balance}`);
                location.reload(); // обновляем страницу, чтобы обновить баланс и время
            }, 4100); // чуть дольше анимации

        } else {
            messageEl.textContent = data.error || 'Ошибка при вращении.';
        }
    } catch (err) {
        console.error(err);
        messageEl.textContent = 'Произошла ошибка. Попробуйте позже.';
    } finally {
        btn.disabled = false;
    }
});
</script>
@endpush


@endsection




