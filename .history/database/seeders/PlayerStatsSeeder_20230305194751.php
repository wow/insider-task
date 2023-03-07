<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('player_stats')->insert([
            'player_id' => 1,
            'appearance' => 0,
            'wins' => 0,
            'losses' => 0,
            'saves' => 0,
            'penalties_saved' => 0,
            'punches' => 0,
            'high_claims' => 0,
            'catches' => 0,
            'sweeper_clearances' => 0,
            'throw_outs' => 0,
            'goal_kicks' => 0,
            'clean_sheets' => 0,
            'goals_conceded' => 0,
            'tackles' => 0,
            'tackle_success' => 0,
            'last_man_tackles' => 0,
            'blocked_shots' => 0,
            'interceptions' => 0,
            'clearances' => 0,
            'headed_clearances' => 0,
            'clearances_off_line' => 0,
            'recoveries' => 0,
            'duels_won' => 0,
            'duels_lost' => 0,
            'successful_50_50s' => 0,
            'aerial_battles_won' => 0,
            'aerial_battles_lost' => 0,
            'own_goals' => 0,
            'errors_leading_to_goal' => 0,
            'goals' => 0,
            'goals_per_match' => 0,
            'head_goals' => 0,
            'goals_with_left_foot' => 0,
            'goals_with_right_foot' => 0,
            'penalties_scored' => 0,
            'free_kicks_scored' => 0,
            'shots' => 0,
            'shots_on_target' => 0,
            'shooting_accuracy' => 0,
            'hit_woodwork' => 0,
            'big_chances_missed' => 0,
            'assists' => 0,
            'passes' => 0,
            'passes_per_match' => 0,
            'big_chances_created' => 0,
            'crosses' => 0,
            'cross_accuracy' => 0,
            'through_balls' => 0,
            'accurate_long_balls' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'fouls' => 0,
            'offsides' => 0,
        ]);
    }
}
