<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTaskStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('test_task_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Внешний ключ на пользователя
            $table->foreignId('test_task_id')->constrained()->onDelete('cascade'); // Внешний ключ на тестовое задание
            $table->enum('status', ['в разработке', 'выполнен', 'проверен']); // Статус тестового задания
            $table->timestamp('start_date')->nullable(); // Дата начала
            $table->timestamp('end_date')->nullable(); // Дата окончания
            $table->timestamps(); // Дата создания и обновления
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_task_statuses');
    }
}