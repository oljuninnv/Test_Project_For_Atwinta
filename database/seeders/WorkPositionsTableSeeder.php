<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkPositionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('work_positions')->insert([
            ['name' => 'Руководитель отдела'],
            ['name' => 'Frontend-разработчик'],
            ['name' => 'Backend-разработчик'],
            ['name' => 'Тестировщик'],
            ['name' => 'HR-менеджер'],
            ['name' => 'Менеджер по продажам'],
            ['name' => 'Бухгалтер'],
            ['name' => 'Экономист'],
        ]);
    }
}
