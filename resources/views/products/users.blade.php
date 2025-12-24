@extends('layouts.app')

@section('title', 'Список пользователей')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Список пользователей</h1>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> К продуктам
        </a>
    </div>

    @if($users->isEmpty())
        <div class="alert alert-info">
            Нет зарегистрированных пользователей.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Продуктов</th>
                        <th>Подписчики</th>
                        <th>Подписки</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Администратор</span>
                                @else
                                    <span class="badge bg-secondary">Пользователь</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->products_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('followers.followers', $user) }}" class="badge bg-info text-decoration-none">
                                    {{ $user->followers->count() }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('followers.following', $user) }}" class="badge bg-success text-decoration-none">
                                    {{ $user->following->count() }}
                                </a>
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.user-products', $user->name) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Продукты
                                    </a>
                                    
                                    @auth
                                        @if(auth()->id() !== $user->id)
                                            @if(auth()->user()->isFollowing($user->id))
                                                <form action="{{ route('followers.unfollow', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-secondary">
                                                        <i class="fas fa-user-minus"></i> Отписаться
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('followers.follow', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-user-plus"></i> Подписаться
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @endif
@endsection