<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'category' => $this->category,
            'category_name' => $this->category_name,
            'image_url' => $this->image_url,
            'short_text' => $this->short_text,
            'full_text' => $this->full_text,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'is_admin' => $this->user->is_admin,
            ],
            'is_owner_friend' => $this->isOwnerFriend(),
            'comments_count' => $this->comments->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Проверка, является ли владелец продукта другом текущего пользователя
     */
    private function isOwnerFriend(): bool
    {
        $user = auth('sanctum')->user();
        
        if (!$user || $user->id === $this->user_id) {
            return false;
        }
        
        return $user->isFriend($this->user_id);
    }
}