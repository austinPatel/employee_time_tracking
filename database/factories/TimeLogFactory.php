<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubProject;

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
        $userIds = User::pluck('id')->toArray();
        $subprojectIds = Subproject::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($userIds), // Pick a random user ID
            'subproject_id' => $this->faker->randomElement($subprojectIds), // Pick a random subproject ID
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'total_hours' => $this->faker->randomFloat(2, 1, 8),
        ];

    }
}
