<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Simulation>
 */
class SimulationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => $this->faker->numberBetween(1, 20),
            'played' => $this->faker->numberBetween(1, 38),
            'won' => $this->faker->numberBetween(1, 38),
            'drawn' => $this->faker->numberBetween(1, 38),
            'lost' => $this->faker->numberBetween(1, 38),
            'goals_for' => $this->faker->numberBetween(1, 38),
            'goals_against' => $this->faker->numberBetween(1, 38),
            'goal_difference' => $this->faker->numberBetween(1, 38),
            'points' => $this->faker->numberBetween(1, 38),
        ];
    }
}
