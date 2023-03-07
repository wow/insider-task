<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Arsenal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liverpool',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manchester City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chelsea',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($teams as $team) {
            DB::table('teams')->insert($team);
        }
    }
}
