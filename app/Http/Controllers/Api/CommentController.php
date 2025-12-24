<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Получить список всех комментариев
     * GET /api/comments
     */
    public function index()
    {
        $comments = Comment::with(['user', 'product.user'])->latest()->paginate(20);
        
        return CommentResource::collection($comments);
    }

    /**
     * Получить комментарии к конкретному продукту
     * GET /api/products/{product_id}/comments
     */
    public function indexByProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $comments = $product->comments()->with(['user', 'product.user'])->latest()->get();
        
        return CommentResource::collection($comments);
    }

    /**
     * Получить конкретный комментарий
     * GET /api/comments/{id}
     */
    public function show($id)
    {
        $comment = Comment::with(['user', 'product.user'])->findOrFail($id);
        
        return new CommentResource($comment);
    }

    /**
     * Создать новый комментарий
     * POST /api/products/{product_id}/comments
     */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:3|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = $product->comments()->create([
            'user_id' => auth('sanctum')->id(),
            'text' => $request->text,
        ]);

        $comment->load(['user', 'product.user']);

        return new CommentResource($comment);
    }

    /**
     * Обновить комментарий
     * PUT/PATCH /api/comments/{id}
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Проверка прав доступа
        if (auth('sanctum')->id() !== $comment->user_id && !auth('sanctum')->user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на редактирование этого комментария'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:3|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'text' => $request->text,
        ]);

        $comment->load(['user', 'product.user']);

        return new CommentResource($comment);
    }

    /**
     * Удалить комментарий
     * DELETE /api/comments/{id}
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Проверка прав доступа
        if (auth('sanctum')->id() !== $comment->user_id && !auth('sanctum')->user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на удаление этого комментария'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Комментарий успешно удален'
        ], 200);
    }
}