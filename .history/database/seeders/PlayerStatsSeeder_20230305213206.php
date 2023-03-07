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
            'throw_outs' => 603,
            'goal_kicks' => 845,
            'clean_sheets' => 33,
            'goals_conceded' => 189,
            'tackles' => 0,
            'tackle_success' => 0,
            'blocked_shots' => 0,
            'interceptions' => 0,
            'clearances' => 0,
            'recoveries' => 0,
            'duels_won' => 0,
            'duels_lost' => 0,
            'own_goals' => 0,
            'goals' => 0,
            'head_goals' => 0,
            'penalties_scored' => 0,
            'free_kicks_scored' => 0,
            'shots' => 0,
            'hit_woodwork' => 0,
            'big_chances_missed' => 0,
            'assists' => 1,
            'passes' => 3582,
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
