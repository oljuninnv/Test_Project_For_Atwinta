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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();

            $table->foreign("user_id")->references("id")->on("users")
                ->onDelete("set null")->onUpdate("cascade");
            $table->foreign("department_id")->references("id")->on("departments")
                ->onDelete("set null")->onUpdate("cascade"); 
            $table->foreign("position_id")->references("id")->on("work_positions")
                ->onDelete("set null")->onUpdate("cascade");  
            $table->date('adopted_at'); // Дата поступления
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
