<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id(); // Создает поле id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Создает поле user_id с внешним ключом
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Создает поле role_id с внешним ключом на таблицу roles
            $table->timestamps(); // Создает поля created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
