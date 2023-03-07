<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('simulations')->insert([
            'team_id' => 1
        ]);

        DB::table('simulations')->insert([
            'team_id' => 2
        ]);

        DB::table('simulations')->insert([
            'team_id' => 3
        ]);

        DB::table('simulations')->insert([
            'team_id' => 4
        ]);
    }
}
