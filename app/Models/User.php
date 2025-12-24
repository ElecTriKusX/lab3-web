<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Связь с продуктами
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Связь с комментариями
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Пользователи, на которых подписан данный пользователь
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')
            ->withTimestamps();
    }

    // Подписчики данного пользователя
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
            ->withTimestamps();
    }

    // Проверка, подписан ли текущий пользователь на другого
    public function isFollowing($userId)
    {
        return $this->following()->where('user_id', $userId)->exists();
    }

    // Проверка взаимной подписки (дружбы)
    public function isFriend($userId)
    {
        return $this->isFollowing($userId) && 
               $this->followers()->where('follower_id', $userId)->exists();
    }

    // Подписаться на пользователя
    public function follow($userId)
    {
        if ($this->id === $userId) {
            return false; // Нельзя подписаться на себя
        }

        if (!$this->isFollowing($userId)) {
            $this->following()->attach($userId);
            
            // Проверяем, подписан ли второй пользователь на первого
            // Если да, создаем обратную связь автоматически
            $otherUser = User::find($userId);
            if ($otherUser && $otherUser->isFollowing($this->id)) {
                // Уже взаимная подписка, ничего не делаем
            }
            
            return true;
        }

        return false;
    }

    // Отписаться от пользователя
    public function unfollow($userId)
    {
        if ($this->isFollowing($userId)) {
            $this->following()->detach($userId);
            return true;
        }

        return false;
    }

    // Получить ленту продуктов от друзей
    public function getFeed($perPage = 10)
    {
        $followingIds = $this->following()->pluck('user_id')->toArray();
        
        return Product::whereIn('user_id', $followingIds)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }
}