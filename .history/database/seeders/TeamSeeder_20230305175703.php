<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            'name' => 'Arsenal',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Liverpool',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Manchester City',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Chelsea',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
