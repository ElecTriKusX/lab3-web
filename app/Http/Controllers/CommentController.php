<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Добавить комментарий к продукту
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'text' => 'required|string|min:3|max:1000'
        ], [
            'text.required' => 'Текст комментария обязателен',
            'text.min' => 'Комментарий должен содержать минимум 3 символа',
            'text.max' => 'Комментарий не должен превышать 1000 символов'
        ]);

        $comment = $product->comments()->create([
            'user_id' => auth()->id(),
            'text' => $validated['text']
        ]);

        return redirect()->back()->with('success', 'Комментарий успешно добавлен!');
    }

    /**
     * Удалить комментарий
     */
    public function destroy(Comment $comment)
    {
        // Проверка прав: удалять может только автор или админ
        if (auth()->id() !== $comment->user_id && !auth()->user()->is_admin) {
            abort(403, 'У вас нет прав на удаление этого комментария');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Комментарий удален!');
    }
}