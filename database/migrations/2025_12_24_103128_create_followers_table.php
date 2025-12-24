<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Уникальный индекс: один пользователь не может подписаться дважды
            $table->unique(['user_id', 'follower_id']);
            
            // Индексы для быстрых запросов
            $table->index('user_id');
            $table->index('follower_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
    }
};