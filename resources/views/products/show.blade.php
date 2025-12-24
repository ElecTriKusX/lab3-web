@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <img src="{{ $product->image_url }}" 
                     class="card-img-top" 
                     alt="{{ $product->title }}"
                     style="height: 200px;">
                
                <div class="card-body">
                    <span class="badge category-{{ $product->category }}">{{ $product->category_name }}</span>
                    <h5 class="card-title mt-2">{{ $product->title }}</h5>
                    
                    <!-- Автор продукта -->
                    <p class="text-muted small mt-2">
                        <i class="fas fa-user"></i> Автор: 
                        <a href="{{ route('products.user-products', $product->user->name) }}">
                            {{ $product->user->name }}
                        </a>
                        @if($product->user->is_admin)
                            <span class="badge bg-danger">Admin</span>
                        @endif
                    </p>
                    
                    <!-- Кнопка подписки/отписки -->
                    @auth
                        @if(auth()->id() !== $product->user_id)
                            <div class="mt-2">
                                @if(auth()->user()->isFollowing($product->user_id))
                                    <form action="{{ route('followers.unfollow', $product->user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="fas fa-user-minus"></i> Отписаться
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('followers.follow', $product->user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class="fas fa-user-plus"></i> Подписаться
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                    
                    <div class="mt-3">
                        <small class="text-muted">Создано: {{ $product->created_at }}</small><br>
                        <small class="text-muted">Обновлено: {{ $product->updated_at }}</small>
                    </div>
                    
                    <!-- Кнопки действий -->
                    @auth
                        @if(auth()->id() === $product->user_id || auth()->user()->is_admin)
                            <div class="btn-group mt-3 w-100 gap-2">
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i> Редактировать
                                </a>
                        
                                <form action="{{ route('products.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Вы уверены, что хотите удалить этот продукт?')">
                                        <i class="fas fa-trash"></i> Удалить
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Описание продукта -->
            <div class="card mb-4">
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

            <!-- Комментарии -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        Комментарии 
                        <span class="badge bg-primary">{{ $product->comments->count() }}</span>
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Форма добавления комментария -->
                    @auth
                        <form action="{{ route('comments.store', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="text" class="form-label">Добавить комментарий</label>
                                <textarea class="form-control @error('text') is-invalid @enderror" 
                                          id="text" 
                                          name="text" 
                                          rows="3" 
                                          placeholder="Напишите ваш комментарий..."
                                          required></textarea>
                                @error('text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment"></i> Отправить
                            </button>
                        </form>
                        <hr>
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
                        </div>
                    @endauth
                    
                    <!-- Список комментариев -->
                    @forelse($product->comments as $comment)
                        <div class="card mb-3 @if($comment->isFromFriend()) border-primary @endif">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-2">
                                            <a href="{{ route('products.user-products', $comment->user->name) }}">
                                                {{ $comment->user->name }}
                                            </a>
                                            @if($comment->user->is_admin)
                                                <span class="badge bg-danger">Admin</span>
                                            @endif
                                            @if($comment->isFromFriend())
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user-friends"></i> Друг
                                                </span>
                                            @endif
                                            <small class="text-muted ms-2">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </h6>
                                        <p class="card-text">{{ $comment->text }}</p>
                                    </div>
                                    
                                    @auth
                                        @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                                            <form action="{{ route('comments.destroy', $comment) }}" 
                                                  method="POST" 
                                                  class="ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Удалить комментарий?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Пока нет комментариев. Будьте первым!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection