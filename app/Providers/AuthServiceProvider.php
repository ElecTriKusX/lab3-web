<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Gate для редактирования продукта
        Gate::define('update-product', function (User $user, Product $product) {
            return $user->id === $product->user_id || $user->is_admin;
        });

        // Gate для удаления продукта
        Gate::define('delete-product', function (User $user, Product $product) {
            return $user->id === $product->user_id || $user->is_admin;
        });

        // Gate для восстановления продукта (только админ)
        Gate::define('restore-product', function (User $user) {
            return $user->is_admin;
        });

        // Gate для полного удаления продукта (только админ)
        Gate::define('force-delete-product', function (User $user) {
            return $user->is_admin;
        });

        // Gate для просмотра корзины (только админ)
        Gate::define('view-trash', function (User $user) {
            return $user->is_admin;
        });
    }
}