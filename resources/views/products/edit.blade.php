@extends('layouts.app')

@section('title', 'Редактировать продукт')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Редактировать продукт: {{ $product->title }}</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Название *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $product->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Категория *</label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category"
                                    required>
                                <option value="fruit" {{ $product->category == 'fruit' ? 'selected' : '' }}>
                                    Плод/Ягода
                                </option>
                                <option value="vegetable" {{ $product->category == 'vegetable' ? 'selected' : '' }}>
                                    Овощ/Злак
                                </option>
                                <option value="flower" {{ $product->category == 'flower' ? 'selected' : '' }}>
                                    Цветок
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ $product->image_url }}" 
                                         alt="Текущее изображение" 
                                         class="img-thumbnail"
                                         style="max-height: 200px; height: 150px;">
                                    <p class="text-muted mt-1">
                                        Текущее изображение
                                    </p>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            <div class="form-text">
                                Оставьте пустым, чтобы сохранить текущее изображение
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_text" class="form-label">Краткое описание *</label>
                            <textarea class="form-control @error('short_text') is-invalid @enderror" 
                                      id="short_text" 
                                      name="short_text"
                                      rows="3"
                                      required>{{ old('short_text', $product->short_text) }}</textarea>
                            @error('short_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_text" class="form-label">Полное описание *</label>
                            <textarea class="form-control @error('full_text') is-invalid @enderror" 
                                      id="full_text" 
                                      name="full_text"
                                      rows="5"
                                      required>{{ old('full_text', $product->full_text) }}</textarea>
                            @error('full_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary me-md-2">
                                Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Обновить продукт
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection