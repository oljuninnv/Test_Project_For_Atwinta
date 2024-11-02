<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->insert([
            ['name' => 'Отдел разработки'],
            ['name' => 'HR-отдел'],
            ['name' => 'Отдел маркетинга'],
            ['name' => 'Финансовый отдел'],
        ]);
    }
}