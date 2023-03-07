<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('player_stats')->insert([
            'player_id' => 1,
            'appearances' => 135,
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
            'assists' => 1,
            'passes' => 3582,
            'accurate_long_balls' => 742,
            'yellow_cards' => 2,
            'fouls' => 1,
        ]);
    }
}
