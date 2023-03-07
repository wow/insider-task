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
            'fixture_id' => 1,
            'goals' => 0,
            'assists' => 0,
            'clean_sheets' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
