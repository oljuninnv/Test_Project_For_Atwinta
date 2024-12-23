<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTasksTable extends Migration
{
    public function up()
    {
        Schema::create('test_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название тестового задания
            $table->string('file'); // Файл (pdf или .docx)
            $table->integer('time_limit'); // Время на выполнение в минутах
            $table->timestamps(); // Дата создания
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_tasks');
    }
}