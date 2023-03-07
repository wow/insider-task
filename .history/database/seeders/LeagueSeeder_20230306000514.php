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
                'name' => 'Champions League',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
