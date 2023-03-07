<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamStatsSeeder extends Seeder
{
    /**
     * Run the team stats seeds.
     */
    public function run(): void
    {
        DB::table('team_stats')->insert([
            'team_id' => 1,
            'matches_played' => 0,
            'wins' => 0,
            'losses' => 0,
            'saves' => 0,
            'penalties_saved' => 0,
            'punches' => 0,
            'high_claims' => 0,
            'catches' => 0,
            'throw_outs' => 0,
            'goal_kicks' => 0,
            'clean_sheets' => 0,
            'goals_conceded' => 0,

        ]);
    }
}
