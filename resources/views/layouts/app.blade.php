<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CrOpsCaLcUlatOr</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column h-100">
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('products.index') }}">
                <img src="{{ Vite::asset('resources/assets/images/Logo.png') }}" 
                     alt="Лого" width="40" height="40" class="me-2">
                <span class="brand-name">CrOpsCaLcUlatOr</span>
            </a>
            
            <div class="navbar-nav ms-auto">
                <a href="{{ route('products.create') }}" class="btn btn-outline-light">
                    Добавить продукт
                </a>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="flex-shrink-0">
        <div class="container my-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Футер -->
    <footer class="bg-dark text-light py-4 mt-auto">
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
</body>
</html>