<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CommentController;

// Публичный маршрут для получения информации о пользователе
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'is_admin' => $user->is_admin,
        'created_at' => $user->created_at,
    ]);
});

// Группа маршрутов, требующих аутентификации через Sanctum API
Route::middleware('auth:sanctum')->group(function () {
    
    // Маршруты для продуктов
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    
    // Маршруты для комментариев
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show']);
    Route::get('/products/{product_id}/comments', [CommentController::class, 'indexByProduct']);
    Route::post('/products/{product_id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::patch('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});