<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // TaskSeeder 内でマルチユーザー（テストユーザーA・B）とタスクを一括生成するため、
        // ここでは TaskSeeder のみを呼び出す設計に統一します。
        $this->call([
            TaskSeeder::class,
        ]);
    }
}