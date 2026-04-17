
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #cookieConsent {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #212529;
      color: white;
      padding: 20px;
      z-index: 1050;
    }
  </style>


<div id="cookieConsent" class="d-flex justify-content-between align-items-center d-none">
  <div>
    Этот сайт использует cookies для улучшения пользовательского опыта.
    <a href="/privacy-policy" class="text-info text-decoration-underline" target="_blank">Подробнее</a>
  </div>
  <button class="btn btn-primary ms-3" id="acceptCookiesBtn">Принять</button>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const consentBanner = document.getElementById("cookieConsent");
    const acceptBtn = document.getElementById("acceptCookiesBtn");

    if (localStorage.getItem("cookiesAccepted") !== "true") {
      consentBanner.classList.remove("d-none");  // Показываем баннер
    }

    acceptBtn.addEventListener("click", function () {
      localStorage.setItem("cookiesAccepted", "true");
      consentBanner.classList.add("d-none");  // Скрываем баннер
    });
  });
</script>

{{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  --}}
