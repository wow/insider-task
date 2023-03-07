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
            'league_id' => $this->faker->numberBetween(1, 100),
            'game_id' => $this->faker->numberBetween(1, 100),
            'home_team_id' => $this->faker->numberBetween(1, 100),
            'away_team_id' => $this->faker->numberBetween(1, 100),
            'week' => $this->faker->numberBetween(1, 6),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
