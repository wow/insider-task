<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerRecord>
 */
class PlayerRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => $this->faker->numberBetween(1, 100),
            'appearances' => $this->faker->numberBetween(0, 100),
            'clean_sheets' => $this->faker->numberBetween(0, 100),
            'goals' => $this->faker->numberBetween(0, 100),
            'assists' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
