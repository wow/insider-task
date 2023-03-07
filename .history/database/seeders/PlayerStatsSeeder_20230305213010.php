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
            'appearance' => 135,
            'wins' => 57,
            'losses' => 63,
            'saves' => 426,
            'penalties_saved' => 1,
            'punches' => 50,
            'high_claims' => 77,
            'catches' => 21,
            'sweeper_clearances' => 60,
            'throw_outs' => 603,
            'goal_kicks' => 845,
            'clean_sheets' => 33,
            'goals_conceded' => 189,
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
            'errors_leading_to_goal' => 4,
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
            'assists' => 1,
            'passes' => 3582,
            'passes_per_match' => 26.53,
            'big_chances_created' => 0,
            'crosses' => 0,
            'cross_accuracy' => 0,
            'through_balls' => 0,
            'accurate_long_balls' => 742,
            'yellow_cards' => 2,
            'red_cards' => 0,
            'fouls' => 1,
            'offsides' => 0,
        ]);
    }
}
