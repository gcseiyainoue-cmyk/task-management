<?php

namespace Database\Factories;

use App\Models\RoutineTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoutineTemplateFactory extends Factory
{
    protected $model = RoutineTemplate::class;

    public function definition(): array
    {
        $frequencyType = $this->faker->randomElement(['day_of_week', 'interval']);

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'category' => $this->faker->randomElement(['work', 'personal', 'growth', 'health', 'finance']),
            'sub_category' => $this->faker->optional()->word(),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
            'is_active' => true,
            'frequency_type' => $frequencyType,
            'interval_days' => $frequencyType === 'interval' ? $this->faker->numberBetween(1, 7) : null,
            'day_of_week' => $frequencyType === 'day_of_week' ? $this->faker->numberBetween(0, 6) : null,
        ];
    }

    /**
     * 曜日指定（day_of_week）の状態
     */
    public function dayOfWeek(int $day = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency_type' => 'day_of_week',
            'day_of_week' => $day,
            'interval_days' => null,
        ]);
    }

    /**
     * インターバル指定（interval）の状態
     */
    public function interval(int $days = 3): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency_type' => 'interval',
            'interval_days' => $days,
            'day_of_week' => null,
        ]);
    }

    /**
     * 非アクティブ状態を定義
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}