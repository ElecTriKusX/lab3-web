@extends('layouts.app')

@section('title', 'API Токены')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-key"></i> API Токены (Laravel Sanctum)
                    </h4>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Как использовать API</h5>
                        <p class="mb-2">1. Создайте токен с помощью формы ниже</p>
                        <p class="mb-2">2. Используйте токен в заголовке Authorization:</p>
                        <code>Authorization: Bearer YOUR_TOKEN_HERE</code>
                        <hr>
                        <p class="mb-2"><strong>Доступные endpoints:</strong></p>
                        <ul class="mb-0">
                            <li><code>GET /api/products</code> - Список продуктов</li>
                            <li><code>GET /api/products/{id}</code> - Получить продукт</li>
                            <li><code>POST /api/products</code> - Создать продукт</li>
                            <li><code>PUT /api/products/{id}</code> - Обновить продукт</li>
                            <li><code>DELETE /api/products/{id}</code> - Удалить продукт</li>
                            <li><code>GET /api/comments</code> - Список комментариев</li>
                            <li><code>GET /api/products/{id}/comments</code> - Комментарии к продукту</li>
                            <li><code>POST /api/products/{id}/comments</code> - Добавить комментарий</li>
                            <li><code>PUT /api/comments/{id}</code> - Обновить комментарий</li>
                            <li><code>DELETE /api/comments/{id}</code> - Удалить комментарий</li>
                        </ul>
                    </div>

                    <!-- Если только что создан новый токен -->
                    @if(session('token'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-check-circle"></i> Токен успешно создан!</h5>
                            <p class="mb-2"><strong>ВАЖНО:</strong> Сохраните этот токен! Он больше не будет показан:</p>
                            <div class="input-group mb-3">
                                <input type="text" 
                                       class="form-control font-monospace" 
                                       id="newToken" 
                                       value="{{ session('token') }}" 
                                       readonly>
                                <button class="btn btn-primary" 
                                        type="button" 
                                        onclick="copyToken()">
                                    <i class="fas fa-copy"></i> Копировать
                                </button>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Форма создания нового токена -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Создать новый токен</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tokens.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               name="name" 
                                               placeholder="Название токена (например: Mobile App, Postman, Desktop Client)" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-plus"></i> Создать токен
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Список существующих токенов -->
                    <h5 class="mb-3">Активные токены</h5>
                    
                    @if($tokens->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            У вас пока нет активных токенов. Создайте первый токен выше.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Название</th>
                                        <th>Токен (частично)</th>
                                        <th>Последнее использование</th>
                                        <th>Создан</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tokens as $token)
                                        <tr>
                                            <td>
                                                <strong><i class="fas fa-key"></i> {{ $token->name }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ Str::limit($token->token, 20, '...') }}</code>
                                            </td>
                                            <td>
                                                @if($token->last_used_at)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        {{ $token->last_used_at->diffForHumans() }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-times-circle"></i>
                                                        Не использовался
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $token->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('tokens.destroy', $token->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Удалить этот токен? Приложения использующие его перестанут работать.')">
                                                        <i class="fas fa-trash"></i> Удалить
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>

    <script>
        function copyToken() {
            const tokenInput = document.getElementById('newToken');
            tokenInput.select();
            tokenInput.setSelectionRange(0, 99999);
            
            navigator.clipboard.writeText(tokenInput.value).then(function() {
                const btn = event.target.closest('button');
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Скопировано!';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                }, 2000);
            }, function(err) {
                console.error('Ошибка копирования: ', err);
                alert('Не удалось скопировать токен. Скопируйте его вручную.');
            });
        }
    </script>
@endsection