@extends('layouts.app')

@section('title', 'Продукты пользователя ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Продукты пользователя: {{ $user->name }}
            @if($user->is_admin)
                <span class="badge bg-danger">Admin</span>
            @endif
        </h1>
        
        <div>
            <a href="{{ route('products.users') }}" class="btn btn-secondary">
                <i class="fas fa-users"></i> Все пользователи
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Все продукты
            </a>
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