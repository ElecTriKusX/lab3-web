@extends('layouts.app')

@section('title', 'Добавить продукт')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Добавить новый продукт</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('products.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          class="needs-validation"
                          novalidate>
                        @csrf
                        
                        <!-- Название -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Название *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
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
                                <option value="fruit" {{ old('category') == 'fruit' ? 'selected' : '' }}>
                                    Плод/Ягода
                                </option>
                                <option value="vegetable" {{ old('category') == 'vegetable' ? 'selected' : '' }}>
                                    Овощ/Злак
                                </option>
                                <option value="flower" {{ old('category') == 'flower' ? 'selected' : '' }}>
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
                            
                            <!-- Предпросмотр -->
                            <div class="mt-2">
                                <img id="imagePreview" 
                                     src="#" 
                                     alt="Предпросмотр" 
                                     class="img-thumbnail d-none"
                                     style="height: 150px;">
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
                                      oninput="updateShortTextCounter(this)">{{ old('short_text') }}</textarea>
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
                                      minlength="50">{{ old('full_text') }}</textarea>
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
                                Сохранить продукт
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
                        return false;
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
            
            // Подсветка поля если превышен лимит
            if (currentLength < 10) {
                textarea.classList.add('is-invalid');
            } else if (currentLength > 500) {
                textarea.classList.add('is-invalid');
            } else {
                textarea.classList.remove('is-invalid');
            }
        }
        
        // Bootstrap валидация форм
        (function () {
            'use strict';
            
            // Получаем все формы с классом needs-validation
            const forms = document.querySelectorAll('.needs-validation');
            
            // Останавливаем отправку, если форма не валидна
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
        
        // Инициализация счетчика при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            const shortText = document.getElementById('short_text');
            if (shortText) {
                updateShortTextCounter(shortText);
            }
        });
    </script>
@endsection