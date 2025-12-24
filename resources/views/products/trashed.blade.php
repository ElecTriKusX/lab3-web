@extends('layouts.app')

@section('title', 'Корзина удаленных продуктов')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Корзина удаленных продуктов
        </h1>
        
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Корзина пуста. Здесь будут отображаться удаленные продукты.
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            В корзине {{ $products->total() }} удаленных продуктов. 
            Они будут храниться здесь 30 дней, после чего будут удалены автоматически.
        </div>

        <div class="row" id="productsContainer">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4">
                    <div class="card h-100 border-danger">
                        <!-- Категория -->
                        <span class="badge category-{{ $product->category }} position-absolute top-0 start-0 m-2">
                            {{ $product->category_name }}
                        </span>
                        
                        <!-- Дата удаления -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <small class="text-muted">
                                Удалён: {{ $product->deleted_at->format('d.m.Y') }}
                            </small>
                        </div>
                        
                        <!-- Изображение -->
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $product->title }}"
                             style="height: 200px;">
                        
                        <!-- Содержимое карточки -->
                        <div class="card-body">
                            <h5 class="card-title text-muted">{{ $product->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->short_text, 80) }}</p>
                            
                            <!-- Кнопки восстановления и полного удаления -->
                            <div class="d-flex justify-content-between mt-3 gap-2">
                                <form action="{{ route('products.restore', $product->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Восстановить продукт?')">
                                        <i class="fas fa-undo"></i> Восстановить
                                    </button>
                                </form>
                                
                                <form action="{{ route('products.force-delete', $product->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Удалить продукт навсегда? Это действие нельзя отменить.')">
                                        <i class="fas fa-trash"></i> Удалить навсегда
                                    </button>
                                </form>
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
    
    <!-- Кнопка очистки всей корзины -->
    @if(!$products->isEmpty())
        <div class="card border-danger mt-4">
            <div class="card-body">
                <h5 class="card-title text-danger">
                    <i class="fas fa-exclamation-triangle"></i> Опасная зона
                </h5>
                <p class="card-text">
                    Вы можете полностью очистить корзину. Все удаленные продукты будут безвозвратно удалены.
                </p>
                <form action="{{ route('products.index') }}/force-delete-all" 
                      method="POST" 
                      onsubmit="return confirm('ВНИМАНИЕ! Все удаленные продукты будут удалены навсегда. Продолжить?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-broom"></i> Очистить всю корзину
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection