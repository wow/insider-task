<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Team 1 - Arsenal
        DB::table('players')->insert([
            'name' => 'Aaron Ramsdale',
            'team_id' => 1,
            'number' => 1,
            'position' => 'Goalkeeper',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Mohamed Salah',
            'team_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Sergio Aguero',
            'team_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Tammy Abraham',
            'team_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
