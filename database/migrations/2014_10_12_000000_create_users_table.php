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
            // $table->binary('image')->nullable();
            $table->string('image')->nullable();
            $table->text('about')->nullable();
            $table->enum('type', ['front', 'back']); // Ограничение для поля type
            $table->string('github')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->string('password');
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
