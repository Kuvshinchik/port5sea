{{--dd($massivTovars)--}}
@extends('layouts.app')  <!-- Наследуем layout -->

@section('content')
    
       
            <!-- slideshow start -->
            <div class="slideshow-section position-relative">
                <div class="slideshow-active activate-slider" data-slick='{
                    "slidesToShow": 1,
                    "slidesToScroll": 1,
                    "dots": true,
                    "arrows": true,
                    "responsive": [
                        {
                        "breakpoint": 768,
                        "settings": {
                            "arrows": false
                        }
                        }
                    ]
                }'>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{asset('assets/img/slideshow/f1.jpg')}}" alt="slide-1">
                        <img class="slide-img d-md-none" src="{{asset('assets/img/slideshow/f1-m.jpg')}}" alt="slide-1">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content slide-content-1 py-4">
                                      <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                            data-animation="animate__animated animate__fadeInUp">
                                         Сайт живых игрушек.
										 <br>
										 <p class="text_14 mt-2 primary-color_2">(Сайт в стадии разработки, приносим свои извинения за некоторые неудобства.)</p>
 {{--                                           Сайт на реконструкции--}}
                                      </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{asset('assets/img/slideshow/f2.jpg')}}" alt="slide-2">
                        <img class="slide-img d-md-none" src="{{asset('assets/img/slideshow/f2-m.jpg')}}" alt="slide-2">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content slide-content-1 py-4">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{asset('assets/img/slideshow/f3.jpg')}}" alt="slide-3">
                        <img class="slide-img d-md-none" src="{{asset('assets/img/slideshow/f3-m.jpg')}}" alt="slide-3">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content slide-content-1 py-4">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{asset('assets/img/slideshow/f4.jpg')}}" alt="slide-3">
                        <img class="slide-img d-md-none" src="{{asset('assets/img/slideshow/f4-m.jpg')}}" alt="slide-3">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content slide-content-1 py-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="activate-arrows"></div>
                <div class="activate-dots dot-tools"></div>
            </div>
            <!-- slideshow end -->

{{--
                      <!-- trusted badge start -->

                      @include('trusted')

            <!-- trusted badge end -->


            <!-- video start -->
            @include('video')
            <!-- video end -->
--}}
            <!-- banner start -->
            <div class="grid-banner mt-100 overflow-hidden" id="about">
                <div class="collection-tab-inner mt-0">
                    <div class="container">
                        <div class="section-header text-center">
                            <h2 class="section-heading primary-color">Что здесь происходит.</h2>
                        </div>
                        <br>
                        <div class="grid-container-2">
                            <a class="grid-item grid-item-1 position-relative rounded mt-0 d-flex" data-aos="fade-right" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('assets/img/banner/f1.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex">
                                        <div class="content-box banner-content p-4">
                                            <h2 class="heading_34 primary-color_2">
                                                Дети играют с игрушкой и ее аватаром.
                                            </h2>
                                            <p class="text_14 mt-2 primary-color_2">Ребенок держит мягкую игрушку, а рядом на экране планшета или телефона оживает ее анимированный аватар.</p>
											{{--                                            <span class="text_12 mt-4 link-underline d-block primary-color_2">
                                                Подробнее ...
                                            </span>--}}
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a class="grid-item grid-item-2 position-relative rounded mt-0 d-flex" data-aos="fade-right" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('assets/img/banner/f3.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex justify-content-end">
                                        <div class="content-box banner-content p-4 text-end">
                                            <h2 class="heading_34 primary-color_2">Продукты ориентированы на детей от 7 до 12 лет.</h2>
                                            <p class="text_14 mt-2 primary-color_2"> Акцент на развитие воображения и социальных навыков</p>
											{{--           <span class="text_12 mt-4 link-underline d-block primary-color_2">
                                                Подробнее ...
                                            </span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a class="grid-item grid-item-3 position-relative rounded mt-0 d-flex" data-aos="fade-left" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('assets/img/banner/f2.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex">
                                        <div class="content-box banner-content p-4">
                                            <h2 class="heading_34 primary-color_2">Аватар помогает в обучении</h2>
                                            <p class="text_14 mt-2 primary-color_2">Анимированный персонаж объясняет что-то ребенку.</p>
											{{--           <span class="text_12 mt-4 link-underline d-block primary-color_2">
                                                Подробнее ...
                                            </span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- banner end -->

            @include('product')

            {{--
            <!-- shop by category start -->
             @include('category')
            <!-- shop by category end -->
            --}}
	{{--
            <!-- single banner start -->
            @include('single_banner')
            <!-- single banner end -->

		
            <!-- testimonial start -->
            <div class="testimonial-section mt-100 overflow-hidden home-section">
                <div class="testimonial-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-md-12 col-12" data-aos="fade-right" data-aos-duration="700">
                                <div class="section-header">
                                    <h2 class="section-heading primary-color">Что говорят покупатели.</h2>
                                    <p class="section-subheading">
                                        Здесь родители — наши главные эксперты, делятся впечатлениями. Реальные истории, искренние эмоции и честные мнения о том, как наши игрушки вдохновляют детей и облегчают жизнь взрослым.
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 offset-lg-1 col-md-12 col-12" data-aos="fade-left"
                                data-aos-duration="700">
                                <div class="testimonial-container position-relative">
                                    <div class="testimonial-slideshow common-slider" data-slick='{
                                            "slidesToShow": 1,
                                            "slidesToScroll": 1,
                                            "dots": false,
                                            "arrows": true
                                        }'>
                                        <div class="testimonial-item">
                                            <div class="testimonial-icon-wrap d-flex align-items-center">
                                                <div class="testimonial-icon-quote">
                                                    <svg width="40" height="29" viewBox="0 0 40 29" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0 28.99L11.7 0H19.5L12.22 28.99H0ZM20.28 28.99L32.11 0H39.91L32.5 28.99H20.28Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </div>
                                                <div class="testimonial-icon-star d-flex align-items-center ms-3">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “Это просто волшебство! Раньше я не могла отвлечься ни на минуту – сын то скучал, то требовал внимания. Но с этой игрушкой всё изменилось! Ребенок полностью погружен в игру: его цифровой друг учит его буквам, рисует вместе с ним и даже разговаривает, как настоящий товарищ. Саша не просто занят – он развивается, а у меня наконец-то есть время на дела. Лучшее сочетание технологии и детства!”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Мария К.
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">мама 5-летнего Саши</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="testimonial-item">
                                            <div class="testimonial-icon-wrap d-flex align-items-center">
                                                <div class="testimonial-icon-quote">
                                                    <svg width="40" height="29" viewBox="0 0 40 29" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0 28.99L11.7 0H19.5L12.22 28.99H0ZM20.28 28.99L32.11 0H39.91L32.5 28.99H20.28Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </div>
                                                <div class="testimonial-icon-star d-flex align-items-center ms-3">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “ I am purchasing furniture from Bisum since the last 6 years. I love
                                                their
                                                prompt service and so far I have faced no complaints with their
                                                furniture.”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Floyd Miles
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">Executive, Hypebeast</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="testimonial-item">
                                            <div class="testimonial-icon-wrap d-flex align-items-center">
                                                <div class="testimonial-icon-quote">
                                                    <svg width="40" height="29" viewBox="0 0 40 29" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0 28.99L11.7 0H19.5L12.22 28.99H0ZM20.28 28.99L32.11 0H39.91L32.5 28.99H20.28Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </div>
                                                <div class="testimonial-icon-star d-flex align-items-center ms-3">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “ I am purchasing furniture from Bisum since the last 6 years. I love
                                                their
                                                prompt service and so far I have faced no complaints with their
                                                furniture.”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Floyd Miles
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">Executive, Hypebeast</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- testimonial end -->
--}}


            <!-- latest blog start -->
            @include('blog')
            <!-- latest blog end -->



                <div class="faq-section mt-100 overflow-hidden">
                    <div class="faq-inner">
                        <div class="container">
                            <div class="section-header text-center">
                                <h2 class="section-heading">Часто задаваемые вопросы</h2>
                                <p class="section-subheading">и мы ответим на все ваши вопросы</p>
                            </div>
                            <div class="faq-container">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq1">
                                                Что такое интерактивная платформа для «живых» игрушек?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq1" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">Ответ: Это уникальная экосистема, которая сочетает
                                                    оригинальные мягкие игрушки с AI-аватарами.
                                                    Сканируя QR-код на игрушке, ребенок получает доступ к
                                                    персонализированному цифровому контенту, где игрушка «оживает» как AI-персонаж,
                                                    взаимодействуя с ребенком.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq2">
                                                Для какого возраста предназначена платформа?

                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq2" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">Ответ: Платформа рассчитана на детей от 6 до 14 лет,
                                                    с контентом, адаптированным под возраст, пол и интересы ребенка.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq3">
                                                Как работает QR-код на игрушке?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq3" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: QR-код на ярлыке игрушки сканируется смартфоном или планшетом,
                                                    после чего автоматически открывается лендинг с интерактивным контентом,
                                                    связанным с конкретной игрушкой.


                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq4">
                                                Нужно ли покупать игрушку, чтобы пользоваться платформой?

                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq4" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Да, для доступа к платформе необходима покупка игрушки с QR-кодом,
                                                    который активирует интерактивный контент.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq5">
                                                Какие типы контента доступны на платформе?

                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq5" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Платформа предлагает развлекательный (игры, истории) и
                                                    познавательный контент (викторины, уроки),
                                                    адаптированный под интересы и возраст ребенка.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq6">
                                                Как обеспечивается персонализация контента?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq6" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: При первом посещении AI-аватар запрашивает имя,
                                                    возраст и предпочтения ребенка, на основе которых подбирается подходящий контент.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq7">
                                                Требуется ли подписка для доступа к контенту?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq7" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Базовый контент доступен бесплатно после покупки игрушки.
                                                    Для регулярного обновления контента предлагаются подписки (базовая, премиум, семейная).
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq8">
                                                Какие устройства поддерживают платформу?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq8" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Платформа работает на любых устройствах с браузером
                                                    (смартфоны, планшеты, компьютеры) на iOS, Android или других системах.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq9">
                                                Безопасна ли платформа для детей?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq9" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Да, мы соблюдаем стандарты безопасности данных.
                                                    Контент тщательно модерируется, а личных данных детей у нас нет.
                                                    При знакомстве ребенок называет только имя, также он может назвать псевдоним.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq10">
                                                Можно ли использовать одну игрушку для нескольких детей?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq10" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Да, но для персонализированного опыта рекомендуется
                                                    создавать отдельные профили для каждого ребенка через платформу.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq11">
                                                Что делать, если QR-код не работает?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq11" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Проверьте качество сканирования и интернет-соединение.
                                                    Если проблема сохраняется, обратитесь в нашу службу поддержки через сайт.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq12">
                                                Можно ли заменить или вернуть игрушку?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq12" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Да, мы предлагаем стандартную политику возврата в
                                                    течение 14 дней при условии сохранения товарного вида. Подробности — на сайте.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq13">
                                                Будет ли контент обновляться?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq13" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Да, подписчики получают регулярные обновления контента
                                                    (ежедневно, еженедельно или ежемесячно, в зависимости от типа подписки).
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="faq-item rounded">
                                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" data-bs-target="#faq14">
                                                Можно ли использовать платформу без интернета?
                                                <span class="faq-heading-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                            </h2>
                                            <div id="faq14" class="accordion-collapse collapse">
                                                <p class="faq-body text_14">
                                                    Ответ: Для доступа к контенту требуется интернет-соединение,
                                                    так как контент загружается динамически через платформу.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @include('pricingbox')
       

@endsection

