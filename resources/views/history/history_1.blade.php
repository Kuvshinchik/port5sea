
{{--
Этот раздел отрисовывает видео
--}}
<!-- Кнопка над видео, по центру -->
<div style="
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        height: 30px;
        z-index: 10;
    ">
    <a href="{{ route('index') }}">
        <img src="{{ asset('assets/img/video/кнопка1.png') }}" alt="Кнопка"
             style="height: 120px; object-fit: contain;">
    </a>
</div>

<br>

<!-- video start -->
<div class="video-wrapper" style="position: relative; display: inline-block; width: 100%;">
    <div class="modal fade" tabindex="-1" id="video-modal">
        <div class="modal-dialog modal-fullscreen"> <!-- 👈 полноэкранный модал -->
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body p-0"> <!-- Убираем отступы -->
                    <video controls autoplay style="width: 100%; height: 100%;">
                        <source src="{{ asset('assets/img/video/01_02.mp4') }}" type="video/mp4">
                        Ваше устройство не поддерживает видео.
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- video end -->
