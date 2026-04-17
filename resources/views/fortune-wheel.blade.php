@extends('layouts.app') {{-- или твой базовый шаблон --}}

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Колесо Фортуны</h2>

    <div class="text-center mb-4">
        <p>Ваш баланс: <strong id="balance-value">{{ $prizePoints->balance }}</strong> баллов</p>
        <p id="last-spin-info">
            @if($prizePoints->last_spin_at)
                Последнее вращение: <span id="last-spin-time">{{ $prizePoints->last_spin_at->format('d.m.Y H:i') }}</span>
            @else
                <span id="last-spin-time">Вы ещё не вращали колесо</span>
            @endif
        </p>
    </div>

    <!-- ============================================== -->
    <!-- КОНТЕЙНЕР КОЛЕСА                              -->
    <!-- ============================================== -->
    <div class="text-center mb-4">
        <div class="wheel-container position-relative d-inline-block mt-5" style="width: 500px; height: 500px;">
            
            <!-- ============================================== -->
            <!-- 4 ОСНОВНЫХ НАПРАВЛЕНИЯ (стороны света)        -->
            <!-- ============================================== -->
            
            <!-- ВЕРХ (0°) - 12 баллов -->
            <div class="prize-icon" style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%);">
                <img src="{{ asset('assets/game/images/prize_12.png') }}" alt="12 баллов" width="50">
            </div>
            
            <!-- ПРАВО (90°) - 3 балла -->
            <div class="prize-icon" style="position: absolute; top: 50%; right: -10px; transform: translateY(-50%);">
                <img src="{{ asset('assets/game/images/prize_3.png') }}" alt="3 балла" width="50">
            </div>
            
            <!-- НИЗ (180°) - 6 баллов -->
            <div class="prize-icon" style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%);">
                <img src="{{ asset('assets/game/images/prize_6.png') }}" alt="6 баллов" width="50">
            </div>
            
            <!-- ЛЕВО (270°) - 9 баллов -->
            <div class="prize-icon" style="position: absolute; top: 50%; left: -10px; transform: translateY(-50%);">
                <img src="{{ asset('assets/game/images/prize_9.png') }}" alt="9 баллов" width="50">
            </div>

            <!-- ============================================== -->
            <!-- 4 ДИАГОНАЛЬНЫХ НАПРАВЛЕНИЯ (новые)            -->
            <!-- ============================================== -->
            
            <!-- ВЕРХ-ПРАВО (45°) - 1 балл -->
            <div class="prize-icon" style="position: absolute; top: 12%; right: 12%;">
                <img src="{{ asset('assets/game/images/prize_1.png') }}" alt="1 балл" width="45">
            </div>
            
            <!-- НИЗ-ПРАВО (135°) - 2 балла -->
            <div class="prize-icon" style="position: absolute; bottom: 12%; right: 12%;">
                <img src="{{ asset('assets/game/images/prize_2.png') }}" alt="2 балла" width="45">
            </div>
            
            <!-- НИЗ-ЛЕВО (225°) - 5 баллов -->
            <div class="prize-icon" style="position: absolute; bottom: 12%; left: 12%;">
                <img src="{{ asset('assets/game/images/prize_5.png') }}" alt="5 баллов" width="45">
            </div>
            
            <!-- ВЕРХ-ЛЕВО (315°) - 15 баллов -->
            <div class="prize-icon" style="position: absolute; top: 12%; left: 12%;">
                <img src="{{ asset('assets/game/images/prize_15.png') }}" alt="15 баллов" width="45">
            </div>

            <!-- ============================================== -->
            <!-- САМО КОЛЕСО                                   -->
            <!-- ============================================== -->
            <div id="wheel" class="position-relative" style="width: 350px; height: 350px; margin: 50px auto;">
                <img id="compass" src="{{ asset('assets/game/images/Compass_2.png') }}" alt="Колесо" style="width: 100%; height: auto;">
            </div>
        </div>
    </div>

    <!-- Кнопка вращения -->
    <div class="text-center" style="margin-top: 30px;">
        <button id="spin-btn" class="btn btn-success btn-lg">Крутить!</button>
    </div>

    <!-- Сообщения об ошибках -->
    <div id="message" class="mt-3 text-center fw-bold"></div>
    
    <!-- Блок результата (появляется после вращения) -->
    <div id="result-block" class="mt-3 text-center" style="display: none;">
        <div class="alert alert-success d-inline-block">
            <strong>Поздравляем!</strong> Вы выиграли <span id="won-points"></span> баллов!
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // ============================================
    // ПОЛУЧАЕМ ЭЛЕМЕНТЫ СТРАНИЦЫ
    // ============================================
    const btn = document.getElementById('spin-btn');
    const messageEl = document.getElementById('message');
    const compass = document.getElementById('compass');
    const balanceEl = document.getElementById('balance-value');
    const lastSpinEl = document.getElementById('last-spin-time');
    const resultBlock = document.getElementById('result-block');
    const wonPointsEl = document.getElementById('won-points');
    
    // ============================================
    // ПЕРЕМЕННЫЕ СОСТОЯНИЯ
    // ============================================
    let currentRotation = 0;
    let isSpinning = false;

    // ============================================
    // ОБРАБОТЧИК КЛИКА ПО КНОПКЕ "КРУТИТЬ"
    // ============================================
    btn.addEventListener('click', async function () {
        
        if (isSpinning) return;
        
        isSpinning = true;
        btn.disabled = true;
        
        messageEl.textContent = '';
        messageEl.className = 'mt-3 text-center fw-bold';
        resultBlock.style.display = 'none';

        try {
            // Отправляем запрос на сервер
            const res = await fetch("{{ route('fortune.spin') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            if (data.success) {
                // ============================================
                // АНИМАЦИЯ ВРАЩЕНИЯ
                // ============================================
                const extraRotations = 5;
                const targetAngle = 360 - data.angle;
                const currentAngleInCircle = currentRotation % 360;
                const angleToAdd = (targetAngle - currentAngleInCircle + 360) % 360;
                const newRotation = currentRotation + (360 * extraRotations) + angleToAdd;
                
                compass.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99)';
                compass.style.transform = `rotate(${newRotation}deg)`;
                
                currentRotation = newRotation;

                // После завершения анимации
                setTimeout(() => {
                    balanceEl.textContent = data.total_balance;
                    
                    const now = new Date();
                    const formattedDate = now.toLocaleDateString('ru-RU', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }) + ' ' + now.toLocaleTimeString('ru-RU', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    lastSpinEl.textContent = formattedDate;
                    
                    wonPointsEl.textContent = data.points;
                    resultBlock.style.display = 'block';
                    
                    compass.style.transition = 'none';
                    
                    isSpinning = false;
                    btn.disabled = false;
                    
                }, 4100);

            } else {
                messageEl.className = 'mt-3 text-center fw-bold text-danger';
                messageEl.textContent = data.error || 'Ошибка при вращении.';
                
                if (data.next_spin_in_hours) {
                    messageEl.textContent += ` Следующее вращение через ${data.next_spin_in_hours} ч.`;
                }
                
                isSpinning = false;
                btn.disabled = false;
            }
            
        } catch (err) {
            console.error(err);
            messageEl.className = 'mt-3 text-center fw-bold text-danger';
            messageEl.textContent = 'Произошла ошибка. Попробуйте позже.';
            isSpinning = false;
            btn.disabled = false;
        }
    });
})();
</script>
@endpush
@endsection
