<!-- collection start -->
<div class="featured-collection mt-100 overflow-hidden" id="shop">
    <div class="collection-tab-inner">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-heading primary-color">Полосатая команда.</h2>
                <p class="section-subheading">
                    Подарите себе и близким незабываемые счастливые моменты с нашими уникальными игрушками ручной работы! Созданные из премиального хлопкового сатина и наполненные гипоаллергенным холлофайбером, они дарят тепло, уют и доброту при каждом прикосновении. Эти игрушки не только идеальны для декора или подарка, но и способны создать неповторимую атмосферу безмятежности для детских и семейных фотографий, помогая запечатлеть бесценные моменты радости больших мечтателей.
                </p>
            </div>
            <div class="row">

                @for ($i = 0; $i < 9; $i++)
                    @php
                        $dirPath = public_path("assets/img/products/furniture/" . ($i + 1));
                        $images = [];

                        if (is_dir($dirPath)) {
                            foreach (scandir($dirPath) as $file) {
                                if (is_file($dirPath . '/' . $file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'jpg') {
                                    $images[] = $file;
                                }
                            }
                            sort($images); // чтобы изображения шли по порядку (1.jpg, 2.jpg и т.д.)
                        }
                    @endphp

                    <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-duration="700">
                        <div class="product-card">
                            <div class="product-card-img">
                                <div id="carouselExample_{{ $i }}" class="carousel slide">
                                    <div class="carousel-inner">
                                        @foreach ($images as $index => $img)
                                            <div class="carousel-item @if($index === 0) active @endif">
                                                <img src="{{ asset("assets/img/products/furniture/" . ($i + 1) . "/$img") }}" class="d-block w-100" alt="...">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($images) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample_{{ $i }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Предыдущий</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample_{{ $i }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Следующий</span>
                                        </button>
                                    @endif
                                </div>

                                <div class="product-badge">
                                    @if (!$massivTovars[$i]->yarmarka)
                                        <span class="sale">Ожидается</span>
                                    @endif
                                </div>
                                <a class="btn-primary single-banner-btn" href="{{ $massivTovars[$i]->yarmarka }}" style="width: 100%; margin-top: 20px;">
                                    КУПИТЬ
                                </a>
                            </div>

                            <div class="product-card-details">
                                @include('faq')
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
        </div>
    </div>
</div>
<!-- collection end -->
