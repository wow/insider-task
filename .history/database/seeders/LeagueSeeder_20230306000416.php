<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leagues')->insert([
            [
                'name' => 'Premier League',
                'country' => 'England',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
