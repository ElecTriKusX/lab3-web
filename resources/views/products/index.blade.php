@extends('layouts.app')

@section('title', 'Все продукты')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Все продукты</h1>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">
            Продукты отсутствуют. 
            @auth
                <a href="{{ route('products.create') }}">Добавить первый продукт</a>
            @else
                <a href="{{ route('login') }}">Войдите</a>, чтобы добавить продукт.
            @endauth
        </div>
    @else
        <div class="row" id="productsContainer">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4">
                    <div class="card h-100">
                        <!-- Кнопки действий - только для владельца или админа -->
                        @auth
                            @if(auth()->id() === $product->user_id || auth()->user()->is_admin)
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                    <div class="btn-group btn-group-sm gap-1">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-outline-warning"
                                           style="width: 35px; height: 35px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
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
                                    </div>
                                </div>
                            @endif
                        @endauth
                        
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
                                <i class="fas fa-user"></i> 
                                <a href="{{ route('products.user-products', $product->user->name) }}">
                                    {{ $product->user->name }}
                                </a>
                                @if($product->user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @endif
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
@endsection