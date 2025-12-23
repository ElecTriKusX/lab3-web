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
                          novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Название *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   required
                                   minlength="3"
                                   maxlength="255">
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
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            <div class="form-text">
                                Поддерживаемые форматы: JPEG, PNG, GIF. Максимальный размер: 2MB
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2">
                                <img id="imagePreview" 
                                     src="#" 
                                     alt="Предпросмотр" 
                                     class="img-thumbnail d-none"
                                     style="max-height: 200px; height: 100px">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_text" class="form-label">Краткое описание *</label>
                            <textarea class="form-control @error('short_text') is-invalid @enderror" 
                                      id="short_text" 
                                      name="short_text"
                                      rows="3"
                                      required
                                      maxlength="500">{{ old('short_text') }}</textarea>
                            <div class="form-text">
                                Осталось символов: <span id="shortTextCounter">500</span>
                            </div>
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
                                      required>{{ old('full_text') }}</textarea>
                            @error('full_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
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
        // Предпросмотр изображения
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
            }
        });
        
        // Счетчик символов для краткого описания
        const shortText = document.getElementById('short_text');
        const counter = document.getElementById('shortTextCounter');
        
        shortText.addEventListener('input', function() {
            const remaining = 500 - this.value.length;
            counter.textContent = remaining;
            
            if (remaining < 50) {
                counter.classList.add('text-warning');
            } else {
                counter.classList.remove('text-warning');
            }
        });
    </script>
@endsection