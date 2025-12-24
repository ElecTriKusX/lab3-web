@extends('layouts.app')

@section('title', 'Подписки ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Подписки пользователя: {{ $user->name }}
            <span class="badge bg-success">{{ $following->total() }}</span>
        </h1>
        <div>
            <a href="{{ route('products.users') }}" class="btn btn-secondary">
                <i class="fas fa-users"></i> Все пользователи
            </a>
            <a href="{{ route('products.user-products', $user->name) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Профиль
            </a>
        </div>
    </div>

    @if($following->isEmpty())
        <div class="alert alert-info">
            {{ $user->name }} пока ни на кого не подписан.
        </div>
    @else
        <div class="row">
            @foreach($following as $followedUser)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('products.user-products', $followedUser->name) }}">
                                    {{ $followedUser->name }}
                                </a>
                                @if($followedUser->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @endif
                                @if(auth()->check() && $user->id === auth()->id() && auth()->user()->isFriend($followedUser->id))
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-friends"></i> Друг
                                    </span>
                                @endif
                            </h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-box"></i> Продуктов: {{ $followedUser->products->count() }}<br>
                                <i class="fas fa-users"></i> Подписчиков: {{ $followedUser->followers->count() }}
                            </p>
                            
                            <div class="btn-group w-100">
                                <a href="{{ route('products.user-products', $followedUser->name) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Профиль
                                </a>
                                
                                @auth
                                    @if(auth()->id() !== $followedUser->id && auth()->id() === $user->id)
                                        <form action="{{ route('followers.unfollow', $followedUser) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-user-minus"></i> Отписаться
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-4">
            {{ $following->links() }}
        </div>
    @endif
@endsection