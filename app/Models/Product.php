<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'image',
        'short_text',
        'full_text'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с комментариями
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    // Мутатор для created_at
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    // Мутатор для updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i');
    }

    // Аксессор для получения полного пути к изображению
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/images/' . $this->image);
        }
        return asset('storage/images/default.png');
    }

    // Связь категорий с русскими названиями
    public function getCategoryNameAttribute()
    {
        $categories = [
            'fruit' => 'Плод/Ягода',
            'vegetable' => 'Овощ/Злак',
            'flower' => 'Цветок'
        ];
        
        return $categories[$this->category] ?? 'Неизвестно';
    }

    // Events для проверки прав на уровне модели
    protected static function booted()
    {
        // Проверка перед обновлением
        static::updating(function ($product) {
            $user = auth()->user();
            
            if (!$user) {
                return false;
            }
            
            if ($product->user_id === $user->id || $user->is_admin) {
                return true;
            }
            
            return false;
        });

        // Проверка перед удалением
        static::deleting(function ($product) {
            $user = auth()->user();
            
            if (!$user) {
                return false;
            }
            
            if ($product->user_id === $user->id || $user->is_admin) {
                return true;
            }
            
            return false;
        });
    }
}