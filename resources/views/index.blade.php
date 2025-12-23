<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Лабораторная работа №2</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ Vite::asset('public/images/Logo.png') }}" 
             alt="Лого" width="40" height="40" class="me-2">
        <span class="brand-name">CrOpsCaLcUlatOr</span>
      </a>
      
      <div class="navbar-nav ms-auto">
        <button class="btn btn-outline-light" id="downloadBtn">Загрузить</button>
      </div>
    </div>
  </nav>

  <main class="container my-4">
    <div class="row" id="productsContainer">
      <!-- Содержимое будет генерироваться JavaScript -->
    </div>
  </main>

  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="author">Матвиенко Антон</div>
        </div>
        <div class="col-md-6">
          <ul class="social-links list-inline d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
            <li class="list-inline-item me-3">
              <a href="https://vk.com/ElecTriKusX" class="text-light">
                <i class="fab fa-vk fa-lg"></i>
              </a>
            </li>
            <li class="list-inline-item me-3">
              <a href="https://t.me/MoLighTrius" class="text-light">
                <i class="fab fa-telegram fa-lg"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="https://github.com/ElecTriKusX" class="text-light">
                <i class="fab fa-github fa-lg"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Модальное окно -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel">Название товара</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <!-- Путь для динамического изображения -->
          <img src="" class="modal-image img-fluid mb-3" alt="Изображение товара">
          <p class="modal-text"></p>
          <button type="button" class="btn btn-secondary" 
                  data-bs-toggle="popover" 
                  data-bs-placement="top"
                  data-bs-content="Информация о категории"
                  title="Информация">
            Подсказка
          </button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast-уведомления -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="fas fa-info-circle text-primary me-2"></i>
        <strong class="me-auto">Уведомление</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body d-flex align-items-center">
        <i class="fas fa-spinner fa-spin me-2"></i>
        Функционал загрузки в настоящее время недоступен.
      </div>
    </div>
  </div>

  <!-- JavaScript для Font Awesome (если используете) -->
  <script src="https://kit.fontawesome.com/ваш-код.js" crossorigin="anonymous"></script>
</body>
</html>