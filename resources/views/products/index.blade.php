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
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $product->title }}"
                             style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2">
                                {{ $product->category_name }}
                            </span>
                            
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->short_text, 100) }}</p>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.show', $product) }}" 
                                class="btn btn-sm btn-outline-primary">
                                    Подробнее
                                </a>
                                
                                <div class="btn-group">
                                    <a href="{{ route('products.edit', $product) }}" 
                                    class="btn btn-sm btn-outline-warning">
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
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-muted">
                            <small>Создано: {{ $product->created_at }}</small>
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