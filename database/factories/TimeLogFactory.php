<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1, // Replace with an appropriate user ID or generate one
            'subproject_id' => 1, // Replace with a valid subproject ID
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'total_hours' => $this->faker->randomFloat(2, 0, 8), // Assuming a maximum of 8 hours
        ];
    }
}
