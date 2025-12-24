@extends('layouts.app')

@section('title', 'Подписчики ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            Подписчики пользователя: {{ $user->name }}
            <span class="badge bg-info">{{ $followers->total() }}</span>
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

    @if($followers->isEmpty())
        <div class="alert alert-info">
            У {{ $user->name }} пока нет подписчиков.
        </div>
    @else
        <div class="row">
            @foreach($followers as $follower)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('products.user-products', $follower->name) }}">
                                    {{ $follower->name }}
                                </a>
                                @if($follower->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @endif
                                @if(auth()->check() && $user->id === auth()->id() && auth()->user()->isFriend($follower->id))
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-friends"></i> Друг
                                    </span>
                                @endif
                            </h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-box"></i> Продуктов: {{ $follower->products->count() }}<br>
                                <i class="fas fa-users"></i> Подписчиков: {{ $follower->followers->count() }}
                            </p>
                            
                            <div class="btn-group w-100">
                                <a href="{{ route('products.user-products', $follower->name) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Профиль
                                </a>
                                
                                @auth
                                    @if(auth()->id() !== $follower->id)
                                        @if(auth()->user()->isFollowing($follower->id))
                                            <form action="{{ route('followers.unfollow', $follower) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-user-minus"></i> Отписаться
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('followers.follow', $follower) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-user-plus"></i> Подписаться
                                                </button>
                                            </form>
                                        @endif
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
            {{ $followers->links() }}
        </div>
    @endif
@endsection