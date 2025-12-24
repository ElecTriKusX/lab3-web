<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Публичные маршруты (без авторизации)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Список всех пользователей
Route::get('/users', [ProductController::class, 'users'])->name('products.users');

// Список продуктов конкретного пользователя
Route::get('/users/{name}/products', [ProductController::class, 'userProducts'])
    ->name('products.user-products');

// Маршруты, требующие аутентификации
Route::middleware('auth')->group(function () {
    // CRUD операции
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Корзина удаленных продуктов (только для админа)
    Route::get('/products-trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    
    // Восстановление продукта (только для админа)
    Route::patch('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    
    // Полное удаление продукта (только для админа)
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.force-delete');
    
    // Очистка всей корзины (только для админа)
    Route::delete('/products-force-delete-all', [ProductController::class, 'forceDeleteAll'])
        ->name('products.force-delete-all');
});

require __DIR__.'/auth.php';