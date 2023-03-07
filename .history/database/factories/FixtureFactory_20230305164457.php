<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fixture>
 */
class FixtureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_team_id' => $this->faker->numberBetween(1, 100),
            'away_team_id' => $this->faker->numberBetween(1, 100),
            'league_id' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
