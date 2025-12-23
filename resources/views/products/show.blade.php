@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{ $product->image_url }}" 
                     class="card-img-top" 
                     alt="{{ $product->title }}"
                     style="height: 300px; object-fit: cover;">
                
                <div class="card-body">
                    <span class="badge bg-secondary">{{ $product->category_name }}</span>
                    <h5 class="card-title mt-2">{{ $product->title }}</h5>
                    
                    <div class="mt-3">
                        <small class="text-muted">Создано: {{ $product->created_at }}</small><br>
                        <small class="text-muted">Обновлено: {{ $product->updated_at }}</small>
                    </div>
                    
                    <!-- Вместо текущего div с btn-group замените на: -->
                    <div class="btn-group mt-3 w-100">
                        <a href="{{ route('products.edit', $product) }}" 
                        class="btn btn-outline-warning">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        
                        <!-- КНОПКА "НАЗАД" -->
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                        
                        <!-- ФОРМА ДЛЯ УДАЛЕНИЯ -->
                        <form action="{{ route('products.destroy', $product) }}" 
                            method="POST" 
                            class="d-inline">
                            @csrf
                            @method('DELETE') <!-- Важно! -->
                            <button type="submit" 
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот продукт?')">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Описание продукта</h4>
                </div>
                
                <div class="card-body">
                    <h5 class="card-subtitle mb-3">Краткое описание</h5>
                    <p class="card-text">{{ $product->short_text }}</p>
                    
                    <hr>
                    
                    <h5 class="card-subtitle mb-3">Полное описание</h5>
                    <p class="card-text">{{ $product->full_text }}</p>
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection