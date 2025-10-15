<?php
namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(TaskStatus::cases()),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::COMPLETED->value,
        ]);
    }

    /**
     * Indicate that the task is cancelled.
     */
    public function incomplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::INCOMPLETE->value,
        ]);
    }
}
