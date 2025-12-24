@extends('layouts.app')

@section('title', 'Продукты пользователя ' . $user->name)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>
                Продукты пользователя: {{ $user->name }}
                @if($user->is_admin)
                    <span class="badge bg-danger">Admin</span>
                @endif
                @auth
                    @if(auth()->user()->isFriend($user->id))
                        <span class="badge bg-primary">
                            <i class="fas fa-user-friends"></i> Друг
                        </span>
                    @endif
                @endauth
            </h1>
            
            <!-- Статистика пользователя -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h5>{{ $user->products->count() }}</h5>
                            <p class="text-muted">Продуктов</p>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('followers.followers', $user) }}" class="text-decoration-none">
                                <h5>{{ $user->followers->count() }}</h5>
                                <p class="text-muted">Подписчиков</p>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('followers.following', $user) }}" class="text-decoration-none">
                                <h5>{{ $user->following->count() }}</h5>
                                <p class="text-muted">Подписок</p>
                            </a>
                        </div>
                    </div>
                    
                    @auth
                        @if(auth()->id() !== $user->id)
                            <hr>
                            <div class="text-center">
                                @if(auth()->user()->isFollowing($user->id))
                                    <form action="{{ route('followers.unfollow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="fas fa-user-minus"></i> Отписаться
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('followers.follow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Подписаться
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('products.users') }}" class="btn btn-secondary">
                    <i class="fas fa-users"></i> Все пользователи
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Все продукты
                </a>
            </div>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            У пользователя {{ $user->name }} пока нет продуктов.
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Всего продуктов: <strong>{{ $products->total() }}</strong>
        </div>

        <div class="row" id="productsContainer">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4">
                    <div class="card h-100">
                        <!-- Кнопки действий -->
                        @auth
                            @if(auth()->id() === $product->user_id || auth()->user()->is_admin)
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                    <div class="btn-group btn-group-sm gap-1">
                                        @can('update-product', $product)
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-outline-warning"
                                               style="width: 35px; height: 35px;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('delete-product', $product)
                                            <form action="{{ route('products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Переместить продукт в корзину?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger p-0"
                                                        style="width: 35px; height: 35px;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endif
                        @endauth
                        
                        <!-- Категория -->
                        <span class="badge category-{{ $product->category }} position-absolute top-0 start-0 m-2" style="z-index: 10;">
                            {{ $product->category_name }}
                        </span>
                        
                        <!-- Изображение -->
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $product->title }}"
                             style="height: 200px;">
                        
                        <!-- Содержимое карточки -->
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->short_text, 80) }}</p>
                            
                            <!-- Комментарии -->
                            <p class="text-muted small">
                                <i class="fas fa-comments"></i> {{ $product->comments->count() }} комментариев
                            </p>
                            
                            <!-- Подробнее -->
                            <div class="text-center mt-3">
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
@endsection