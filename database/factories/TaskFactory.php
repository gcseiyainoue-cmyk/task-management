<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\RoutineTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'routine_template_id' => null,
            'title' => $this->faker->sentence(3),
            'is_completed' => $this->faker->boolean(20),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'category' => $this->faker->randomElement(['work', 'personal', 'growth', 'health', 'finance']),
            'sub_category' => $this->faker->optional()->word(),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
        ];
    }
}