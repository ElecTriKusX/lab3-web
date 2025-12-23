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

    // Мутатор для created_at (преобразование формата даты)
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
}