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
                          class="needs-validation"
                          novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Название -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Название *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $product->title) }}"
                                   required
                                   minlength="3"
                                   maxlength="255"
                                   pattern=".*\S+.*"
                                   title="Название должно содержать хотя бы один непробельный символ">
                            <div class="invalid-feedback">
                                Пожалуйста, введите название продукта (минимум 3 символа).
                            </div>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Категория -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Категория *</label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category"
                                    required>
                                <option value="">Выберите категорию</option>
                                <option value="fruit" {{ old('category', $product->category) == 'fruit' ? 'selected' : '' }}>
                                    Плод/Ягода
                                </option>
                                <option value="vegetable" {{ old('category', $product->category) == 'vegetable' ? 'selected' : '' }}>
                                    Овощ/Злак
                                </option>
                                <option value="flower" {{ old('category', $product->category) == 'flower' ? 'selected' : '' }}>
                                    Цветок
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Пожалуйста, выберите категорию.
                            </div>
                            @error('category')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Изображение -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            
                            <!-- Текущее изображение -->
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ $product->image_url }}" 
                                         alt="Текущее изображение" 
                                         class="img-thumbnail mb-2"
                                         style="height: 150px;">
                                    <p class="text-muted">
                                        Текущее изображение. Оставьте поле пустым, чтобы сохранить это изображение.
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Поле для загрузки нового -->
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/jpeg,image/png,image/gif"
                                   onchange="validateImage(this)">
                            <div class="form-text">
                                Поддерживаемые форматы: JPEG, PNG, GIF. Максимальный размер: 1MB (150x150px).
                            </div>
                            <div class="invalid-feedback" id="imageError">
                                @error('image') 
                                    {{ $message }} 
                                @else 
                                    Файл должен быть изображением не более 1MB
                                @enderror
                            </div>
                            
                            <!-- Предпросмотр нового изображения -->
                            <div class="mt-2">
                                <img id="imagePreview" 
                                     src="#" 
                                     alt="Предпросмотр" 
                                     class="img-thumbnail d-none"
                                     style="max-height: 150px;">
                            </div>
                        </div>
                        
                        <!-- Краткое описание -->
                        <div class="mb-3">
                            <label for="short_text" class="form-label">Краткое описание *</label>
                            <textarea class="form-control @error('short_text') is-invalid @enderror" 
                                      id="short_text" 
                                      name="short_text"
                                      rows="3"
                                      required
                                      minlength="10"
                                      maxlength="500"
                                      oninput="updateShortTextCounter(this)">{{ old('short_text', $product->short_text) }}</textarea>
                            <div class="form-text">
                                Осталось символов: <span id="shortTextCounter">500</span>
                            </div>
                            <div class="invalid-feedback">
                                Пожалуйста, введите краткое описание (10-500 символов).
                            </div>
                            @error('short_text')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Полное описание -->
                        <div class="mb-3">
                            <label for="full_text" class="form-label">Полное описание *</label>
                            <textarea class="form-control @error('full_text') is-invalid @enderror" 
                                      id="full_text" 
                                      name="full_text"
                                      rows="5"
                                      required
                                      minlength="50">{{ old('full_text', $product->full_text) }}</textarea>
                            <div class="invalid-feedback">
                                Пожалуйста, введите полное описание (минимум 50 символов).
                            </div>
                            @error('full_text')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Кнопки -->
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
    
    <script>
        // Валидация изображения
        function validateImage(input) {
            const file = input.files[0];
            const errorElement = document.getElementById('imageError');
            const maxSize = 1024 * 1024; // 1MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (file) {
                // Проверка типа
                if (!allowedTypes.includes(file.type)) {
                    errorElement.textContent = 'Допустимы только JPEG, PNG и GIF файлы';
                    input.classList.add('is-invalid');
                    return false;
                }
                
                // Проверка размера
                if (file.size > maxSize) {
                    errorElement.textContent = 'Файл слишком большой (максимум 1MB)';
                    input.classList.add('is-invalid');
                    return false;
                }
                
                // Проверка разрешения
                const img = new Image();
                img.onload = function() {
                    if (this.width > 150 || this.height > 150) {
                        errorElement.textContent = 'Изображение должно быть не более 150x150 пикселей';
                        input.classList.add('is-invalid');
                    }
                };
                img.src = URL.createObjectURL(file);
                
                // Если всё ок
                input.classList.remove('is-invalid');
                errorElement.textContent = '';
                
                // Предпросмотр
                const preview = document.getElementById('imagePreview');
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                // Нет файла - скрываем предпросмотр
                const preview = document.getElementById('imagePreview');
                preview.classList.add('d-none');
            }
            
            return true;
        }
        
        // Счетчик символов
        function updateShortTextCounter(textarea) {
            const counter = document.getElementById('shortTextCounter');
            const currentLength = textarea.value.length;
            const remaining = 500 - currentLength;
            
            counter.textContent = remaining;
            
            if (remaining < 50) {
                counter.classList.add('text-warning');
            } else {
                counter.classList.remove('text-warning');
            }
            
            // Подсветка поля если не соответствует требованиям
            if (currentLength < 10 || currentLength > 500) {
                textarea.classList.add('is-invalid');
            } else {
                textarea.classList.remove('is-invalid');
            }
        }
        
        // Bootstrap валидация форм
        (function () {
            'use strict';
            
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();
        
        // Инициализация при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация счетчика
            const shortText = document.getElementById('short_text');
            if (shortText) {
                updateShortTextCounter(shortText);
            }
            
            // Инициализация категории
            const categorySelect = document.getElementById('category');
            if (categorySelect) {
                // Если не выбрано, показываем ошибку
                if (!categorySelect.value) {
                    categorySelect.classList.add('is-invalid');
                }
            }
        });
    </script>
@endsection