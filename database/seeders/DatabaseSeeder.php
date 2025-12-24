<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Создаем обычных пользователей
        $user1 = User::create([
            'name' => 'Иван',
            'email' => 'ivan@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $user2 = User::create([
            'name' => 'Мария',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $user3 = User::create([
            'name' => 'Петр',
            'email' => 'petr@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Создаем подписки (взаимные друзья)
        $user1->follow($user2->id);
        $user2->follow($user1->id); // Теперь user1 и user2 - друзья

        $user1->follow($user3->id);
        $user2->follow($user3->id);

        // Создаем продукты для каждого пользователя
        $product1 = Product::create([
            'user_id' => $user1->id,
            'title' => 'Яблоко',
            'category' => 'fruit',
            'short_text' => 'Свежее красное яблоко из сада.',
            'full_text' => 'Спелое красное яблоко, выращенное в экологически чистом саду. Богато витаминами и минералами.',
            'image' => null,
        ]);

        $product2 = Product::create([
            'user_id' => $user1->id,
            'title' => 'Помидор',
            'category' => 'vegetable',
            'short_text' => 'Сочный спелый помидор.',
            'full_text' => 'Красный помидор, выращенный на собственном огороде. Идеально подходит для салатов.',
            'image' => null,
        ]);

        $product3 = Product::create([
            'user_id' => $user2->id,
            'title' => 'Роза',
            'category' => 'flower',
            'short_text' => 'Красивая алая роза.',
            'full_text' => 'Роскошная алая роза с нежными лепестками. Отличный подарок для любимых людей.',
            'image' => null,
        ]);

        $product4 = Product::create([
            'user_id' => $user2->id,
            'title' => 'Клубника',
            'category' => 'fruit',
            'short_text' => 'Ароматная садовая клубника.',
            'full_text' => 'Крупная сладкая клубника, собранная на пике спелости. Богата антиоксидантами.',
            'image' => null,
        ]);

        $product5 = Product::create([
            'user_id' => $user3->id,
            'title' => 'Тюльпан',
            'category' => 'flower',
            'short_text' => 'Весенний тюльпан ярко-желтого цвета.',
            'full_text' => 'Символ весны и обновления. Желтые тюльпаны дарят радость и поднимают настроение.',
            'image' => null,
        ]);

        $product6 = Product::create([
            'user_id' => $admin->id,
            'title' => 'Пшеница',
            'category' => 'vegetable',
            'short_text' => 'Золотистая пшеница высшего качества.',
            'full_text' => 'Органическая пшеница, выращенная без применения химикатов. Основа здорового питания.',
            'image' => null,
        ]);

        // Создаем комментарии
        Comment::create([
            'product_id' => $product1->id,
            'user_id' => $user2->id, // Комментарий от друга
            'text' => 'Отличное яблоко! Очень вкусное.',
        ]);

        Comment::create([
            'product_id' => $product1->id,
            'user_id' => $user3->id,
            'text' => 'Где можно купить такие яблоки?',
        ]);

        Comment::create([
            'product_id' => $product3->id,
            'user_id' => $user1->id, // Комментарий от друга
            'text' => 'Великолепная роза! Жена будет в восторге.',
        ]);

        Comment::create([
            'product_id' => $product4->id,
            'user_id' => $user3->id,
            'text' => 'Клубника выглядит очень аппетитно!',
        ]);

        Comment::create([
            'product_id' => $product6->id,
            'user_id' => $user1->id,
            'text' => 'Хороший урожай пшеницы в этом году.',
        ]);

        $this->command->info('База данных успешно заполнена тестовыми данными!');
        $this->command->info('Учетные данные:');
        $this->command->info('Админ: admin@example.com / password');
        $this->command->info('Иван: ivan@example.com / password');
        $this->command->info('Мария: maria@example.com / password');
        $this->command->info('Петр: petr@example.com / password');
    }
}