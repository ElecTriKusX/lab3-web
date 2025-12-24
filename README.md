php artisan tinker:

$user1 = \App\Models\User::create([
    'name' => 'Иван',
    'email' => 'ivan@example.com',
    'password' => bcrypt('password'),
    'is_admin' => false
]);


$admin = \App\Models\User::create([
    'name' => 'Администратор',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true
]);

$user2 = \App\Models\User::create([
    'name' => 'Мария',
    'email' => 'maria@example.com',
    'password' => bcrypt('password'),
    'is_admin' => false
]);


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

