<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image')->nullable(); // Ссылка на изображение
            $table->text('about')->nullable(); // Описание пользователя
            $table->string('type'); // Тип пользователя
            $table->string('github')->nullable(); // Ссылка на GitHub
            $table->string('city')->nullable(); // Город
            $table->boolean('is_finished')->default(false); // Завершен ли профиль
            $table->string('phone')->nullable(); // Телефон
            $table->date('birthday')->nullable(); // Дата рождения
            $table->string('password'); // Пароль
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
