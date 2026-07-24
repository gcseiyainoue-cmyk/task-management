<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            // user_idが指定されない場合は新しいユーザーを自動生成
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'is_completed' => $this->faker->boolean(20), // 20%の確率で完了
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'category' => $this->faker->randomElement(['work', 'personal', 'growth', 'health', 'finance']),
            'sub_category' => $this->faker->optional()->word(),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
        ];
    }
}