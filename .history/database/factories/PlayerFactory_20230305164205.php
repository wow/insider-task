<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'team_id' => $this->faker->numberBetween(1, 100),
            'number' => $this->faker->numberBetween(1, 100),
            'position' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
