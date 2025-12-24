<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'text'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Связь с продуктом
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Связь с пользователем (автором комментария)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Проверка, является ли автор комментария другом текущего пользователя
    public function isFromFriend()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->isFollowing($this->user_id);
    }
}