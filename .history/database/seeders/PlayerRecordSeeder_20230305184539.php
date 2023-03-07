<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PlayerRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('player_records')->insert([
            'player_id' => 1,
            'appearances' => 1,
            'clean_sheets' => 1,
            'goals' => 1,
            'assists' => 1,
        ]);
    }
}
