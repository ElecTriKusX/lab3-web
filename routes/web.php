<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Маршруты для продуктов
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->middleware('auth')->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->middleware('auth')->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware('auth')->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('auth')->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('auth')->name('products.destroy');

// Маршруты для корзины (только для админов)
Route::get('/products/trashed/list', [ProductController::class, 'trashed'])->middleware('auth')->name('products.trashed');
Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->middleware('auth')->name('products.restore');
Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->middleware('auth')->name('products.force-delete');
Route::delete('/products/force-delete-all/all', [ProductController::class, 'forceDeleteAll'])->middleware('auth')->name('products.force-delete-all');

// Маршруты для пользователей
Route::get('/users', [ProductController::class, 'users'])->name('products.users');
Route::get('/users/{name}/products', [ProductController::class, 'userProducts'])->name('products.user-products');

// Маршруты для комментариев
Route::post('/products/{product}/comments', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');

// Маршруты для подписок/дружбы
Route::post('/users/{user}/follow', [FollowerController::class, 'follow'])->middleware('auth')->name('followers.follow');
Route::delete('/users/{user}/unfollow', [FollowerController::class, 'unfollow'])->middleware('auth')->name('followers.unfollow');
Route::get('/users/{user}/following', [FollowerController::class, 'following'])->name('followers.following');
Route::get('/users/{user}/followers', [FollowerController::class, 'followers'])->name('followers.followers');
Route::get('/feed', [FollowerController::class, 'feed'])->middleware('auth')->name('followers.feed');

// Маршруты для профиля
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';