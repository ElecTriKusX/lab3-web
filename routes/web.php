<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Главная страница
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Ресурсные маршруты для продуктов
Route::resource('products', ProductController::class);

// Дополнительные маршруты для Soft Deletes
Route::get('products/trashed', [ProductController::class, 'trashed'])
    ->name('products.trashed');
Route::patch('products/{id}/restore', [ProductController::class, 'restore'])
    ->name('products.restore');
Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])
    ->name('products.force-delete');