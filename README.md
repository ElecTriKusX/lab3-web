# CropsCalculator - Веб-приложение для управления продуктами

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.5.0-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Веб-приложение на Laravel для управления каталогом сельскохозяйственных продуктов с системой комментариев, подписок и REST API.

## Основные возможности

### Веб-интерфейс
- **CRUD операции** для продуктов (плоды, овощи цветы)
- **Система комментариев** к продуктам
- **Подписки между пользователями** (система "друзей")
- **Лента новостей** от друзей
- **Корзина** для мягкого удаления (только админ)
- **Управление пользователями** с разными ролями
- **Аутентификация** и авторизация

### REST API
- **OAuth2 аутентификация** (Laravel Sanctum)
- **API для продуктов** (GET, POST, PUT, DELETE)
- **API для комментариев** (GET, POST, PUT, DELETE)
- **Признак "друга"** в ответах API (is_owner_friend, is_author_friend)
- **Управление токенами** через веб-интерфейс

## Технологии

- **Backend:** Laravel 12, PHP 8.5.0
- **Frontend:** Bootstrap 5, Blade Templates
- **База данных:** SQLite (или MySQL/PostgreSQL)
- **Аутентификация:** Laravel Breeze + Sanctum
- **API:** RESTful с JSON Resources

## Установка

### Требования

- PHP 8.5.0 или выше
- Composer
- Node.js и NPM
- SQLite или MySQL

### Шаги установки

```bash
# 1. Клонировать репозиторий
git clone <repository-url>
cd lab3-web

# 2. Установить зависимости
composer install
npm install

# 3. Настроить окружение
cp .env.example .env
php artisan key:generate

# 4. Настроить базу данных в .env
# Для SQLite:
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# 5. Опубликовать Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 6. Запустить миграции
php artisan migrate

# 7. (Опционально) Заполнить тестовыми данными
php artisan db:seed

# 8. Собрать frontend
npm run build

# 9. Создать символическую ссылку для storage
php artisan storage:link

# 10. Запустить сервер
php artisan serve
```

Приложение будет доступно по адресу: `http://127.0.0.1:8000`

## Быстрый старт

### 1. Регистрация

Откройте `http://127.0.0.1:8000/register` и создайте аккаунт.

### 2. Создание продукта

После входа нажмите **"Добавить"** в навигации и заполните форму.

### 3. Получение API токена

1. Меню профиля → **"API Токены"**
2. Введите название токена (например, "Postman")
3. Скопируйте токен (он больше не будет показан!)

### 4. Тестирование API

```bash
# Получить список продуктов
curl -X GET http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Создать продукт
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Яблоко",
    "category": "fruit",
    "short_text": "Свежее красное яблоко",
    "full_text": "Спелое яблоко из сада"
  }'
```

## API Endpoints

### Продукты

| Метод | URL | Описание |
|-------|-----|----------|
| GET | `/api/products` | Список всех продуктов |
| GET | `/api/products/{id}` | Получить конкретный продукт |
| POST | `/api/products` | Создать новый продукт |
| PUT | `/api/products/{id}` | Обновить продукт |
| DELETE | `/api/products/{id}` | Удалить продукт |

### Комментарии

| Метод | URL | Описание |
|-------|-----|----------|
| GET | `/api/comments` | Список всех комментариев |
| GET | `/api/comments/{id}` | Получить конкретный комментарий |
| GET | `/api/products/{id}/comments` | Комментарии к продукту |
| POST | `/api/products/{id}/comments` | Добавить комментарий |
| PUT | `/api/comments/{id}` | Обновить комментарий |
| DELETE | `/api/comments/{id}` | Удалить комментарий |

### Аутентификация

Все API запросы требуют заголовок:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Структура проекта

```
lab3-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                    # API контроллеры
│   │   │   │   ├── ProductController.php
│   │   │   │   └── CommentController.php
│   │   │   ├── ProductController.php   # Веб контроллер
│   │   │   ├── CommentController.php
│   │   │   ├── FollowerController.php
│   │   │   └── TokenController.php
│   │   └── Resources/                  # JSON Resources
│   │       ├── ProductResource.php
│   │       └── CommentResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   └── Comment.php
│   └── Providers/
│       └── AppServiceProvider.php      # Регистрация API маршрутов
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── products/                   # Шаблоны продуктов
│       ├── followers/                  # Шаблоны подписок
│       └── tokens/                     # Управление токенами
└── routes/
    ├── web.php                         # Веб маршруты
    └── api.php                         # API маршруты
```

## Роли пользователей

### Обычный пользователь
- Создание, редактирование и удаление своих продуктов
- Добавление комментариев к любым продуктам
- Подписка на других пользователей
- Просмотр ленты новостей от друзей

### Администратор
- Все возможности обычного пользователя
- Редактирование и удаление любых продуктов
- Доступ к корзине (восстановление/полное удаление)
- Удаление любых комментариев

## Безопасность

- **CSRF защита** для всех POST/PUT/DELETE запросов
- **Проверка прав доступа** через Gates и Policies
- **Валидация данных** на уровне контроллера и модели
- **Хеширование паролей** (bcrypt)
- **Аутентификация API** через токены Sanctum
- **Мягкое удаление** продуктов (soft delete)

## Особенности API

### Признак "друга"

API автоматически добавляет поля:

**В ProductResource:**
```json
{
  "is_owner_friend": true  // владелец продукта - друг текущего пользователя
}
```

**В CommentResource:**
```json
{
  "is_author_friend": true,  // автор комментария - друг
  "product": {               // данные продукта включены в ответ
    "id": 1,
    "title": "Яблоко"
  }
}
```

## База данных

### Основные таблицы

- `users` - пользователи
- `products` - продукты (с soft delete)
- `comments` - комментарии
- `followers` - подписки (M-N связь)
- `personal_access_tokens` - API токены Sanctum

### Связи

- User → Products (1:M)
- User → Comments (1:M)
- Product → Comments (1:M)
- User → Followers (M:N через таблицу followers)

## Учебный проект

Разработано в рамках изучения Laravel 12 и REST API.