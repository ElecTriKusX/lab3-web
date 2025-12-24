@extends('layouts.app')

@section('title', 'Все продукты')

@section('content')

    <h1>Все продукты</h1>

    @if($products->isEmpty())
        <div class="alert alert-info">
            Продукты отсутствуют. <a href="{{ route('products.create') }}">Добавить первый продукт</a>
        </div>
    @else
        <div class="row" id="productsContainer">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4">
                    <div class="card h-100">
                        <!-- Кнопки действий -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="btn-group btn-group-sm gap-1">
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-outline-warning"
                                   style="width: 35px; height: 35px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- ФОРМА ДЛЯ УДАЛЕНИЯ -->
                                <form action="{{ route('products.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Вы уверены, что хотите удалить этот продукт?')">
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
                        
                        <!-- Изображение -->
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $product->title }}">
                        
                        <!-- Категория -->
                        <span class="badge category-{{ $product->category }} position-absolute top-0 start-0 m-2">
                            {{ $product->category_name }}
                        </span>
                        
                        <!-- Содержимое карточки -->
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->short_text, 100) }}</p>
                            
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