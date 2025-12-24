<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'is_admin' => $this->user->is_admin,
            ],
            'is_author_friend' => $this->isAuthorFriend(),
            // Данные основной сущности (продукта)
            'product' => [
                'id' => $this->product->id,
                'title' => $this->product->title,
                'category' => $this->product->category,
                'category_name' => $this->product->category_name,
                'user' => [
                    'id' => $this->product->user->id,
                    'name' => $this->product->user->name,
                ],
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Проверка, является ли автор комментария другом текущего пользователя
     */
    private function isAuthorFriend(): bool
    {
        $user = auth('sanctum')->user();
        
        if (!$user || $user->id === $this->user_id) {
            return false;
        }
        
        return $user->isFriend($this->user_id);
    }
}