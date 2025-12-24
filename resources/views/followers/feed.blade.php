@extends('layouts.app')

@section('title', 'Лента новостей')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-newspaper"></i> Лента новостей
        </h1>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Все продукты
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Здесь отображаются новые продукты от пользователей, на которых вы подписаны.
        <a href="{{ route('followers.following', auth()->user()) }}">Мои подписки ({{ auth()->user()->following->count() }})</a>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-warning">
            <h4>Лента пуста</h4>
            <p>Подпишитесь на других пользователей, чтобы видеть их новые продукты здесь!</p>
            <a href="{{ route('products.users') }}" class="btn btn-primary">
                <i class="fas fa-users"></i> Найти пользователей
            </a>
        </div>
    @else
        <div class="row" id="productsContainer">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4">
                    <div class="card h-100 border-primary">
                        <div class="ribbon ribbon-top-left">
                            <span>Друг</span>
                        </div>
                        
                        <!-- Изображение -->
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $product->title }}"
                             style="height: 200px;">
                        
                        <!-- Категория -->
                        <span class="badge category-{{ $product->category }} position-absolute top-0 start-0 m-2" style="z-index: 10;">
                            {{ $product->category_name }}
                        </span>
                        
                        <!-- Содержимое карточки -->
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->short_text, 100) }}</p>
                            
                            <!-- Автор продукта -->
                            <p class="text-muted small">
                                <i class="fas fa-user-friends text-primary"></i> 
                                <a href="{{ route('products.user-products', $product->user->name) }}">
                                    {{ $product->user->name }}
                                </a>
                                @if($product->user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @endif
                                <br>
                                <i class="fas fa-clock"></i> {{ $product->created_at }}
                            </p>
                            
                            <!-- Подробнее -->
                            <div class="text-center">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-primary"
                                   style="width: 100%;">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <style>
        .ribbon {
            width: 150px;
            height: 150px;
            overflow: hidden;
            position: absolute;
        }
        .ribbon::before,
        .ribbon::after {
            position: absolute;
            z-index: -1;
            content: '';
            display: block;
            border: 5px solid #2980b9;
        }
        .ribbon span {
            position: absolute;
            display: block;
            width: 225px;
            padding: 8px 0;
            background-color: #3498db;
            box-shadow: 0 5px 10px rgba(0,0,0,.1);
            color: #fff;
            font-size: 12px;
            text-shadow: 0 1px 1px rgba(0,0,0,.2);
            text-transform: uppercase;
            text-align: center;
        }
        .ribbon-top-left {
            top: -10px;
            left: -10px;
        }
        .ribbon-top-left::before,
        .ribbon-top-left::after {
            border-top-color: transparent;
            border-left-color: transparent;
        }
        .ribbon-top-left::before {
            top: 0;
            right: 0;
        }
        .ribbon-top-left::after {
            bottom: 0;
            left: 0;
        }
        .ribbon-top-left span {
            right: -25px;
            top: 30px;
            transform: rotate(-45deg);
        }
    </style>
@endsection