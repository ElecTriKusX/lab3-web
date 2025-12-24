// Создаем обычного пользователя
$user1 = \App\Models\User::create([
    'name' => 'Иван',
    'email' => 'ivan@example.com',
    'password' => bcrypt('password'),
    'is_admin' => false
]);

// Создаем администратора
$admin = \App\Models\User::create([
    'name' => 'Администратор',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true
]);

// Создаем еще одного пользователя
$user2 = \App\Models\User::create([
    'name' => 'Мария',
    'email' => 'maria@example.com',
    'password' => bcrypt('password'),
    'is_admin' => false
]);

// Создаем тестовые продукты для пользователей
\App\Models\Product::create([
    'user_id' => $user1->id,
    'title' => 'Яблоко',
    'category' => 'fruit',
    'short_text' => 'Сочное красное яблоко из сада.',
    'full_text' => 'Красное яблоко, выращенное в экологически чистом саду. Богато витаминами и минералами.'
]);

\App\Models\Product::create([
    'user_id' => $user2->id,
    'title' => 'Помидор',
    'category' => 'vegetable',
    'short_text' => 'Свежий спелый помидор.',
    'full_text' => 'Спелый помидор с грядки. Идеален для салатов и приготовления соусов.'
]);

exit