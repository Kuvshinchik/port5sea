{{--
Этот раздел отрисовывает видео
--}}
          <!-- video start -->
            <div class="video-section mt-100 overflow-hidden">
                <div class="overlay-furniture section-spacing"
                    style="background: url("{{asset('assets/img/video/video-furniture.jpg')}}") no-repeat fixed bottom center/cover">
                    <div class="container video-container">
                        <div class="row">
                            <div class="col-12">
                                <div class="video-tools d-flex align-items-center justify-content-center">
                                    <div class="video-button-area">
                                        <a class="video-button" href="#video-modal" data-bs-toggle="modal">
                                            <svg width="22" height="26" viewBox="0 0 22 26" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21.5 12.134C22.1667 12.5189 22.1667 13.4811 21.5 13.866L2 25.1244C1.33333 25.5093 0.499999 25.0281 0.499999 24.2583L0.5 1.74167C0.5 0.971867 1.33333 0.490743 2 0.875643L21.5 12.134Z"
                                                    fill="#FEFEFE" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="video-modal">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <iframe height="600" src="https://www.youtube.com/embed/tvPnrfQCiCo"
                                    title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- video end -->