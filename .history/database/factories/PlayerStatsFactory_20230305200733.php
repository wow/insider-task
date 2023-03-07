<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerStats>
 */
class PlayerStatsFactory extends Factory
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
            'wins' => $this->faker->numberBetween(0, 100),
            'losses' => $this->faker->numberBetween(0, 100),
            'saves' => $this->faker->numberBetween(0, 100),
            'penalties_saved' => $this->faker->numberBetween(0, 100),
            'punches' => $this->faker->numberBetween(0, 100),
            'high_claims' => $this->faker->numberBetween(0, 100),
            'catches' => $this->faker->numberBetween(0, 100),
            'sweeper_clearances' => $this->faker->numberBetween(0, 100),
            'throw_outs' => $this->faker->numberBetween(0, 100),
            'goal_kicks' => $this->faker->numberBetween(0, 100),
            'clean_sheets' => $this->faker->numberBetween(0, 100),
            'goals_conceded' => $this->faker->numberBetween(0, 100),
            'tackles' => $this->faker->numberBetween(0, 100),
            'tackle_success' => $this->faker->numberBetween(0, 100),
            'last_man_tackles' => $this->faker->numberBetween(0, 100),
            'blocked_shots' => $this->faker->numberBetween(0, 100),
            'interceptions' => $this->faker->numberBetween(0, 100),
            'clearances' => $this->faker->numberBetween(0, 100),
            'recoveries' => $this->faker->numberBetween(0, 100),
            'duels_won' => $this->faker->numberBetween(0, 100),
            'duels_lost' => $this->faker->numberBetween(0, 100),
            'own_goals' => $this->faker->numberBetween(0, 100),
            'errors_leading_to_goal' => $this->faker->numberBetween(0, 100),
            'goals' => $this->faker->numberBetween(0, 100),
            'goals_per_match' => $this->faker->randomFloat(2, 0, 100),
            'head_goals' => $this->faker->numberBetween(0, 100),
            'goals_with_left_foot' => $this->faker->numberBetween(0, 100),
            'goals_with_right_foot' => $this->faker->numberBetween(0, 100),
            'penalties_scored' => $this->faker->numberBetween(0, 100),
            'free_kicks_scored' => $this->faker->numberBetween(0, 100),
            'shots' => $this->faker->numberBetween(0, 100),
            'shots_on_target' => $this->faker->numberBetween(0, 100),
            'shooting_accuracy' => $this->faker->randomFloat(2, 0, 100),
            'hit_woodwork' => $this->faker->numberBetween(0, 100),
            'big_chances_missed' => $this->faker->numberBetween(0, 100),
            'assists' => $this->faker->numberBetween(0, 100),
            'passes' => $this->faker->numberBetween(0, 100),
            'passes_per_match' => $this->faker->randomFloat(2, 0, 100),
            'big_chances_created' => $this->faker->numberBetween(0, 100),
            'crosses' => $this->faker->numberBetween(0, 100),
            'cross_accuracy' => $this->faker->randomFloat(2, 0, 100),
            'through_balls' => $this->faker->numberBetween(0, 100),
            'accurate_long_balls' => $this->faker->numberBetween(0, 100),
            'yellow_cards' => $this->faker->numberBetween(0, 100),
            'red_cards' => $this->faker->numberBetween(0, 100),
            'fouls' => $this->faker->numberBetween(0, 100),
            'offsides' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
